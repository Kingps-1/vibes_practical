<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\RoomAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomAllocationController extends Controller
{
    /**
     * Display a listing of room allocations with search and filters.
     */
    public function index(Request $request)
    {
        $query = RoomAllocation::with(['room', 'student', 'allocatedByUser']);

        // Search by student name, roll number, or room number
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($sq) use ($search) {
                    $sq->where('full_name', 'like', "%{$search}%")
                       ->orWhere('student_id_number', 'like', "%{$search}%");
                })->orWhereHas('room', function ($rq) use ($search) {
                    $rq->where('room_number', 'like', "%{$search}%")
                       ->orWhere('block', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Status (active, vacated)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by Fee Status (paid, pending, partial)
        if ($request->filled('fee_status')) {
            $query->where('fee_status', $request->input('fee_status'));
        }

        $allocations = $query->latest()->paginate(10)->withQueryString();

        return view('allocations.index', compact('allocations'));
    }

    /**
     * Show the form for creating a new allocation.
     */
    public function create(Request $request)
    {
        // Available rooms that have capacity and are not in maintenance
        $rooms = Room::where('status', '!=', 'maintenance')
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->orderBy('block')
            ->orderBy('room_number')
            ->get();

        // If a specific room was preselected from the rooms list
        $selectedRoomId = $request->query('room_id');

        // Students without an active allocation
        $activeStudentIds = RoomAllocation::where('status', 'active')->pluck('student_id');
        $students = Student::whereNotIn('id', $activeStudentIds)->orderBy('full_name')->get();

        return view('allocations.create', compact('rooms', 'students', 'selectedRoomId'));
    }

    /**
     * Store a newly created allocation in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'student_id' => ['required', 'exists:students,id'],
            'bed_number' => ['required', 'string', 'max:50'],
            'allocation_date' => ['required', 'date'],
            'fee_status' => ['required', 'in:paid,pending,partial'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $room = Room::findOrFail($validated['room_id']);

        // Check room capacity
        if ($room->current_occupancy >= $room->capacity) {
            return back()->withInput()->with('error', "Room {$room->room_number} is currently full. Please choose another available room.");
        }

        // Check if student already has active allocation
        $existing = RoomAllocation::where('student_id', $validated['student_id'])
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return back()->withInput()->with('error', "This student already has an active allocation in Room {$existing->room->room_number}.");
        }

        $allocation = RoomAllocation::create([
            'room_id' => $validated['room_id'],
            'student_id' => $validated['student_id'],
            'allocated_by' => Auth::id(),
            'bed_number' => $validated['bed_number'],
            'allocation_date' => $validated['allocation_date'],
            'status' => 'active',
            'fee_status' => $validated['fee_status'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Sync room occupancy
        $room->syncOccupancy();

        return redirect()->route('allocations.show', $allocation)
            ->with('success', "Room allocated successfully to {$allocation->student->full_name}!");
    }

    /**
     * Display the specified allocation details.
     */
    public function show(RoomAllocation $allocation)
    {
        $allocation->load(['room', 'student', 'allocatedByUser']);
        return view('allocations.show', compact('allocation'));
    }

    /**
     * Show the form for editing the specified allocation.
     */
    public function edit(RoomAllocation $allocation)
    {
        $allocation->load(['room', 'student']);
        return view('allocations.edit', compact('allocation'));
    }

    /**
     * Update the specified allocation in storage.
     */
    public function update(Request $request, RoomAllocation $allocation)
    {
        $validated = $request->validate([
            'bed_number' => ['required', 'string', 'max:50'],
            'allocation_date' => ['required', 'date'],
            'vacate_date' => ['nullable', 'date', 'after_or_equal:allocation_date'],
            'status' => ['required', 'in:active,vacated,cancelled'],
            'fee_status' => ['required', 'in:paid,pending,partial'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $allocation->update($validated);

        // Sync room occupancy
        $allocation->room->syncOccupancy();

        return redirect()->route('allocations.show', $allocation)
            ->with('success', "Allocation details updated successfully.");
    }

    /**
     * Vacate a student from the room and release the bed.
     */
    public function vacate(Request $request, RoomAllocation $allocation)
    {
        $allocation->update([
            'status' => 'vacated',
            'vacate_date' => now()->toDateString(),
            'remarks' => trim(($allocation->remarks ?? '') . ' | Vacated on ' . now()->toFormattedDateString()),
        ]);

        $allocation->room->syncOccupancy();

        return redirect()->route('allocations.index')
            ->with('success', "Student {$allocation->student->full_name} has been vacated from Room {$allocation->room->room_number}. Bed is now available.");
    }

    /**
     * Remove the specified allocation from storage.
     */
    public function destroy(RoomAllocation $allocation)
    {
        $room = $allocation->room;
        $studentName = $allocation->student->full_name;

        $allocation->delete();
        $room->syncOccupancy();

        return redirect()->route('allocations.index')
            ->with('success', "Allocation record for {$studentName} has been deleted.");
    }
}
