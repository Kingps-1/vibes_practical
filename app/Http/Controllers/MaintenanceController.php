<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['room', 'student']);

        // If student is logged in, show only their requests
        $user = Auth::user();
        if ($user && $user->role === 'student' && $user->student) {
            $query->where('student_id', $user->student->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $requests = $query->latest('reported_at')->paginate(10)->withQueryString();
        $rooms = Room::orderBy('room_number')->get();

        return view('maintenance.index', compact('requests', 'rooms'));
    }

    public function create()
    {
        $user = Auth::user();
        $student = $user && $user->role === 'student' ? $user->student : null;
        $rooms = Room::orderBy('room_number')->get();
        $students = Student::orderBy('full_name')->get();

        return view('maintenance.create', compact('rooms', 'students', 'student'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'student_id' => ['required', 'exists:students,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ]);

        MaintenanceRequest::create([
            'room_id' => $validated['room_id'],
            'student_id' => $validated['student_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'reported',
            'reported_at' => now(),
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance issue reported successfully! Our hostel maintenance team has been notified.');
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenance)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:reported,in_progress,resolved'],
        ]);

        $maintenance->status = $validated['status'];
        if ($validated['status'] === 'resolved') {
            $maintenance->resolved_at = now();
        }
        $maintenance->save();

        return back()->with('success', "Maintenance request status updated to {$maintenance->status}.");
    }
}
