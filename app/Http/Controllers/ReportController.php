<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Models\RoomAllocation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a comprehensive hostel occupancy and room allocation summary report.
     */
    public function index(Request $request)
    {
        // Overall Hostel Capacity & Occupancy KPI metrics
        $totalRooms = Room::count();
        $totalCapacity = (int) Room::sum('capacity');
        $totalOccupied = (int) Room::sum('current_occupancy');
        $availableBeds = max(0, $totalCapacity - $totalOccupied);
        $occupancyRate = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 1) : 0;

        // Estimated revenue calculation
        $expectedMonthlyRevenue = Room::where('status', '!=', 'maintenance')->sum('monthly_fee');
        $activeAllocations = RoomAllocation::with(['room', 'student'])
            ->where('status', 'active')
            ->get();

        // Fee collection stats
        $paidCount = $activeAllocations->where('fee_status', 'paid')->count();
        $pendingCount = $activeAllocations->where('fee_status', 'pending')->count();
        $partialCount = $activeAllocations->where('fee_status', 'partial')->count();

        // Block-by-Block aggregation
        $blockSummaries = Room::selectRaw('
                block,
                COUNT(id) as total_rooms,
                SUM(capacity) as total_capacity,
                SUM(current_occupancy) as occupied_beds,
                SUM(monthly_fee) as estimated_revenue
            ')
            ->groupBy('block')
            ->get()
            ->map(function ($item) {
                $item->available_beds = max(0, $item->total_capacity - $item->occupied_beds);
                $item->occupancy_rate = $item->total_capacity > 0 ? round(($item->occupied_beds / $item->total_capacity) * 100, 1) : 0;
                return $item;
            });

        // Room Type breakdown
        $typeSummaries = Room::selectRaw('
                room_type,
                COUNT(id) as count,
                SUM(capacity) as total_capacity,
                SUM(current_occupancy) as occupied_beds
            ')
            ->groupBy('room_type')
            ->get();

        // Vacant/Available rooms list
        $vacantRooms = Room::where('status', 'available')
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->orderBy('block')
            ->orderBy('room_number')
            ->get();

        return view('reports.index', compact(
            'totalRooms',
            'totalCapacity',
            'totalOccupied',
            'availableBeds',
            'occupancyRate',
            'expectedMonthlyRevenue',
            'activeAllocations',
            'paidCount',
            'pendingCount',
            'partialCount',
            'blockSummaries',
            'typeSummaries',
            'vacantRooms'
        ));
    }
}
