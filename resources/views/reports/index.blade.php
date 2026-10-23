@extends('layouts.app')

@section('title', 'Hostel Occupancy & Room Allocation Summary Report')

@section('content')
<!-- Actions & Print Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 d-print-none">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Hostel Occupancy &amp; Summary Report</h1>
    <p class="text-muted mb-0">Official analytical summary of room utilization, bed vacancies, and fee collection</p>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-white-primary d-flex align-items-center gap-2">
      <i class="bi bi-printer"></i>
      <span>Print / Export PDF Report</span>
    </button>
  </div>
</div>

<!-- Formal Report Header (Visible in print & web) -->
<div class="white-card mb-4">
  <div class="white-card-body p-4">
    <div class="row align-items-center border-bottom pb-3 mb-3">
      <div class="col-sm-8">
        <div class="d-flex align-items-center gap-2 mb-1">
          <div class="brand-icon-wrapper" style="width: 38px; height: 38px;">
            <i class="bi bi-bar-chart-line"></i>
          </div>
          <span class="h5 fw-bold text-dark mb-0">CAMPUS HOSTEL OCCUPANCY &amp; RESIDENCE REPORT</span>
        </div>
        <p class="text-muted small mb-0">Board of Student Welfare &bull; Academic Year 2026</p>
      </div>
      <div class="col-sm-4 text-sm-end text-muted small">
        <div>Generated: <strong class="text-dark">{{ now()->format('d M Y, h:i A') }}</strong></div>
        <div>Report Authority: <span class="badge-clean badge-clean-subtle">Authorized Officer</span></div>
      </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="row g-3">
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Total Rooms</div>
        <div class="h4 fw-bold text-dark mb-0">{{ $totalRooms }}</div>
      </div>
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Total Beds</div>
        <div class="h4 fw-bold text-dark mb-0">{{ $totalCapacity }}</div>
      </div>
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Occupied Beds</div>
        <div class="h4 fw-bold text-primary mb-0">{{ $totalOccupied }}</div>
      </div>
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Available Beds</div>
        <div class="h4 fw-bold text-success mb-0">{{ $availableBeds }}</div>
      </div>
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Occupancy Rate</div>
        <div class="h4 fw-bold text-dark mb-0">{{ $occupancyRate }}%</div>
      </div>
      <div class="col-6 col-md-2">
        <div class="text-muted small text-uppercase">Est. Monthly Revenue</div>
        <div class="h4 fw-bold text-dark mb-0">₹{{ number_format($expectedMonthlyRevenue) }}</div>
      </div>
    </div>
  </div>
</div>

<!-- Block-by-Block Utilization Table -->
<div class="white-card mb-4">
  <div class="white-card-header">
    <div>
      <h2 class="h6 fw-bold text-dark mb-0">1. Block-wise Capacity &amp; Utilization Summary</h2>
      <span class="text-muted small">Occupancy breakdown aggregated per hostel residential block</span>
    </div>
  </div>
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Hostel Wing / Block</th>
          <th class="text-center">Total Rooms</th>
          <th class="text-center">Total Beds</th>
          <th class="text-center">Occupied Beds</th>
          <th class="text-center">Available Beds</th>
          <th>Occupancy %</th>
          <th class="text-end">Monthly Revenue</th>
        </tr>
      </thead>
      <tbody>
        @foreach($blockSummaries as $block)
          <tr>
            <td class="fw-bold text-dark">{{ $block->block }}</td>
            <td class="text-center">{{ $block->total_rooms }}</td>
            <td class="text-center">{{ $block->total_capacity }}</td>
            <td class="text-center fw-semibold text-primary">{{ $block->occupied_beds }}</td>
            <td class="text-center fw-semibold text-success">{{ $block->available_beds }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $block->occupancy_rate }}%"></div>
                </div>
                <span class="small fw-semibold">{{ $block->occupancy_rate }}%</span>
              </div>
            </td>
            <td class="text-end fw-bold text-dark">₹{{ number_format($block->estimated_revenue, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Active Room Allocations Master List -->
<div class="white-card mb-4">
  <div class="white-card-header">
    <div>
      <h2 class="h6 fw-bold text-dark mb-0">2. Active Resident Allocations Master Listing</h2>
      <span class="text-muted small">Records of all currently residing students from the database</span>
    </div>
    <span class="badge-clean badge-clean-success">{{ $activeAllocations->count() }} Active Allotments</span>
  </div>
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Slip #</th>
          <th>Student Name</th>
          <th>Roll Number</th>
          <th>Course</th>
          <th>Room &amp; Bed</th>
          <th>Block</th>
          <th>Allotted Date</th>
          <th>Fee Status</th>
          <th class="text-end">Tariff</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activeAllocations as $alloc)
          <tr>
            <td class="font-monospace text-muted small">#{{ $alloc->id }}</td>
            <td class="fw-bold text-dark">{{ $alloc->student->full_name }}</td>
            <td class="font-monospace small">{{ $alloc->student->student_id_number }}</td>
            <td class="small">{{ $alloc->student->course }}</td>
            <td>
              <span class="fw-bold text-primary">{{ $alloc->room->room_number }}</span>
              <span class="badge-clean badge-clean-subtle ms-1">{{ $alloc->bed_number }}</span>
            </td>
            <td class="text-secondary small">{{ $alloc->room->block }}</td>
            <td class="text-muted small">{{ $alloc->allocation_date->format('d M Y') }}</td>
            <td>
              @if($alloc->fee_status === 'paid')
                <span class="badge-clean badge-clean-success">Paid</span>
              @elseif($alloc->fee_status === 'pending')
                <span class="badge-clean badge-clean-warning">Pending</span>
              @else
                <span class="badge-clean badge-clean-info">Partial</span>
              @endif
            </td>
            <td class="text-end fw-semibold text-dark">₹{{ number_format($alloc->room->monthly_fee) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-4 text-muted">No active student allocations found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Current Vacant Rooms Table -->
<div class="white-card">
  <div class="white-card-header">
    <div>
      <h2 class="h6 fw-bold text-dark mb-0">3. Current Vacant Rooms / Available Beds Roster</h2>
      <span class="text-muted small">Rooms with immediate capacity ready for incoming students</span>
    </div>
    <span class="badge-clean badge-clean-info">{{ $vacantRooms->count() }} Available Rooms</span>
  </div>
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Room Number</th>
          <th>Hostel Wing</th>
          <th>Configuration</th>
          <th>Floor</th>
          <th>Occupancy</th>
          <th>Vacant Beds</th>
          <th>Monthly Fee</th>
          <th>Amenities</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vacantRooms as $vr)
          <tr>
            <td class="fw-bold text-primary">{{ $vr->room_number }}</td>
            <td>{{ $vr->block }}</td>
            <td>{{ $vr->room_type }}</td>
            <td>Floor {{ $vr->floor }}</td>
            <td>{{ $vr->current_occupancy }} / {{ $vr->capacity }}</td>
            <td>
              <span class="badge-clean badge-clean-success fw-bold">{{ $vr->availableBeds() }} Bed(s) Free</span>
            </td>
            <td class="fw-bold text-dark">₹{{ number_format($vr->monthly_fee) }}</td>
            <td class="text-muted small">{{ Str::limit($vr->amenities, 45) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">No vacant rooms currently available.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
