<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\RoomAllocation;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // High level hostel metrics
        $totalRooms = Room::count();
        $totalCapacity = (int) Room::sum('capacity');
        $totalOccupied = (int) Room::sum('current_occupancy');
        $availableBeds = max(0, $totalCapacity - $totalOccupied);
        $occupancyRate = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 1) : 0;

        $totalStudents = Student::count();
        $activeAllocationsCount = RoomAllocation::where('status', 'active')->count();
        $pendingFeesCount = RoomAllocation::where('status', 'active')->where('fee_status', 'pending')->count();
        $pendingMaintenance = MaintenanceRequest::where('status', '!=', 'resolved')->count();

        // Recent records for table previews
        $recentAllocations = RoomAllocation::with(['room', 'student', 'allocatedByUser'])
            ->latest()
            ->take(5)
            ->get();

        $featuredRooms = Room::withCount('activeAllocations')
            ->orderBy('block')
            ->orderBy('room_number')
            ->take(6)
            ->get();

        // Student personal allocation if logged in as student
        $myAllocation = null;
        if ($user && $user->role === 'student' && $user->student) {
            $myAllocation = $user->student->activeAllocation()->with('room')->first();
        }

        return view('dashboard', compact(
            'totalRooms',
            'totalCapacity',
            'totalOccupied',
            'availableBeds',
            'occupancyRate',
            'totalStudents',
            'activeAllocationsCount',
            'pendingFeesCount',
            'pendingMaintenance',
            'recentAllocations',
            'featuredRooms',
            'myAllocation'
        ));
    }
}
