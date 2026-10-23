<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of hostel rooms with multi-criteria search and filter.
     */
    public function index(Request $request)
    {
        $query = Room::query()->withCount('activeAllocations');

        // 1. Keyword search (room number, block, amenities)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('block', 'like', "%{$search}%")
                  ->orWhere('amenities', 'like', "%{$search}%")
                  ->orWhere('room_type', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Block
        if ($request->filled('block')) {
            $query->where('block', $request->input('block'));
        }

        // 3. Filter by Room Type
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->input('room_type'));
        }

        // 4. Filter by Status (available, full, maintenance)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 5. Filter by Floor
        if ($request->filled('floor')) {
            $query->where('floor', $request->input('floor'));
        }

        // Sorting
        $sortBy = $request->input('sort', 'room_number');
        $sortDir = $request->input('direction', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $rooms = $query->paginate(9)->withQueryString();

        // Unique blocks and room types for dropdown filters
        $blocks = Room::distinct()->pluck('block')->sort();
        $roomTypes = Room::distinct()->pluck('room_type')->sort();

        return view('rooms.index', compact('rooms', 'blocks', 'roomTypes'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:50', 'unique:rooms,room_number'],
            'block' => ['required', 'string', 'max:100'],
            'room_type' => ['required', 'string', 'in:Single Deluxe,Double Sharing,Triple Sharing,Four Sharing'],
            'capacity' => ['required', 'integer', 'min:1', 'max:6'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
            'floor' => ['required', 'integer', 'min:0', 'max:20'],
            'amenities' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:available,full,maintenance'],
        ]);

        $room = Room::create($validated);
        $room->syncOccupancy();

        return redirect()->route('rooms.show', $room)
            ->with('success', "Room {$room->room_number} was successfully added!");
    }

    /**
     * Display the specified room details.
     */
    public function show(Room $room)
    {
        $room->load(['activeAllocations.student', 'allocations.student', 'maintenanceRequests.student']);
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:50', Rule::unique('rooms')->ignore($room->id)],
            'block' => ['required', 'string', 'max:100'],
            'room_type' => ['required', 'string', 'in:Single Deluxe,Double Sharing,Triple Sharing,Four Sharing'],
            'capacity' => ['required', 'integer', 'min:' . max(1, $room->current_occupancy), 'max:6'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
            'floor' => ['required', 'integer', 'min:0', 'max:20'],
            'amenities' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:available,full,maintenance'],
        ]);

        $room->update($validated);
        $room->syncOccupancy();

        return redirect()->route('rooms.show', $room)
            ->with('success', "Room {$room->room_number} was updated successfully!");
    }

    /**
     * Remove the specified room from storage with relational safety check.
     */
    public function destroy(Room $room)
    {
        $activeCount = $room->activeAllocations()->count();
        if ($activeCount > 0) {
            return back()->with('error', "Cannot delete Room {$room->room_number} because it has {$activeCount} active student allocation(s). Please vacate all beds first.");
        }

        $roomNumber = $room->room_number;
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', "Room {$roomNumber} has been permanently deleted.");
    }
}
