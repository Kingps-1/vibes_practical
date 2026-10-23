@extends('layouts.app')

@section('title', 'Hostel Overview & Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Hostel Management Dashboard</h1>
    <p class="text-muted mb-0">Overview of room occupancy, allocations, and residence activity</p>
  </div>
  @auth
    <div class="d-flex gap-2">
      <a href="{{ route('allocations.create') }}" class="btn btn-white-primary d-flex align-items-center gap-1">
        <i class="bi bi-person-plus"></i>
        <span>Allocate Room</span>
      </a>
      <a href="{{ route('rooms.create') }}" class="btn btn-white-outline d-flex align-items-center gap-1">
        <i class="bi bi-plus-lg"></i>
        <span>Add Room</span>
      </a>
    </div>
  @endauth
</div>

<!-- Student Specific Alert Banner if logged in as student -->
@if(isset($myAllocation) && $myAllocation)
  <div class="white-card mb-4 border-primary border-opacity-25" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
    <div class="white-card-body p-4">
      <div class="row align-items-center gy-3">
        <div class="col-md-8">
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-clean badge-clean-success">
              <i class="bi bi-check2-circle"></i> Active Allotment
            </span>
            <span class="text-muted small">Allotted on {{ $myAllocation->allocation_date->format('M d, Y') }}</span>
          </div>
          <h3 class="h4 fw-bold text-dark mb-1">
            Your Room: <span class="text-primary">{{ $myAllocation->room->room_number }}</span> ({{ $myAllocation->bed_number }})
          </h3>
          <p class="text-muted mb-0 small">
            {{ $myAllocation->room->block }} &bull; Floor {{ $myAllocation->room->floor }} &bull; {{ $myAllocation->room->room_type }} &bull; Fee Status: <strong class="text-uppercase text-dark">{{ $myAllocation->fee_status }}</strong>
          </p>
        </div>
        <div class="col-md-4 text-md-end">
          <a href="{{ route('maintenance.create') }}" class="btn btn-white-outline btn-sm me-2">
            <i class="bi bi-tools text-primary me-1"></i> Request Maintenance
          </a>
          <a href="{{ route('allocations.show', $myAllocation) }}" class="btn btn-white-primary btn-sm">
            View Slip
          </a>
        </div>
      </div>
    </div>
  </div>
@endif

<!-- High-Level KPI Metric Cards -->
<div class="row g-3 mb-4">
  <!-- Total Rooms -->
  <div class="col-sm-6 col-lg-3">
    <div class="kpi-card">
      <div>
        <div class="kpi-icon kpi-icon-primary">
          <i class="bi bi-door-closed"></i>
        </div>
        <div class="kpi-title">Total Rooms</div>
        <div class="kpi-value">{{ $totalRooms }}</div>
      </div>
      <div class="kpi-subtitle">
        Across 3 Campus Blocks
      </div>
    </div>
  </div>

  <!-- Bed Capacity & Occupancy -->
  <div class="col-sm-6 col-lg-3">
    <div class="kpi-card">
      <div>
        <div class="kpi-icon kpi-icon-success">
          <i class="bi bi-people"></i>
        </div>
        <div class="kpi-title">Occupancy Rate</div>
        <div class="kpi-value">{{ $occupancyRate }}%</div>
      </div>
      <div class="kpi-subtitle">
        {{ $totalOccupied }} of {{ $totalCapacity }} beds occupied
      </div>
    </div>
  </div>

  <!-- Available Beds -->
  <div class="col-sm-6 col-lg-3">
    <div class="kpi-card">
      <div>
        <div class="kpi-icon kpi-icon-info">
          <i class="bi bi-check-circle"></i>
        </div>
        <div class="kpi-title">Available Beds</div>
        <div class="kpi-value">{{ $availableBeds }}</div>
      </div>
      <div class="kpi-subtitle">
        Ready for immediate allotment
      </div>
    </div>
  </div>

  <!-- Registered Students -->
  <div class="col-sm-6 col-lg-3">
    <div class="kpi-card">
      <div>
        <div class="kpi-icon kpi-icon-warning">
          <i class="bi bi-mortarboard"></i>
        </div>
        <div class="kpi-title">Active Residents</div>
        <div class="kpi-value">{{ $activeAllocationsCount }}</div>
      </div>
      <div class="kpi-subtitle">
        {{ $totalStudents }} total enrolled students
      </div>
    </div>
  </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="white-card mb-4">
  <div class="white-card-body p-3">
    <div class="row g-2 align-items-center">
      <div class="col-md-3">
        <span class="fw-bold text-dark small text-uppercase tracking-wider">
          <i class="bi bi-lightning-charge text-primary me-1"></i> Quick Nav
        </span>
      </div>
      <div class="col-md-9 d-flex flex-wrap gap-2 justify-content-md-end">
        <a href="{{ route('rooms.index') }}" class="btn btn-white-subtle">
          <i class="bi bi-search me-1"></i> Search Rooms Catalog
        </a>
        <a href="{{ route('reports.index') }}" class="btn btn-white-subtle">
          <i class="bi bi-file-earmark-bar-graph me-1"></i> Occupancy Summary Report
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-white-subtle">
          <i class="bi bi-people me-1"></i> Students Directory
        </a>
        <a href="{{ route('maintenance.index') }}" class="btn btn-white-subtle">
          <i class="bi bi-wrench me-1"></i> Maintenance Issues ({{ $pendingMaintenance }})
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Recent Allocations Table -->
  <div class="col-lg-7">
    <div class="white-card h-100">
      <div class="white-card-header">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Recent Room Allocations</h2>
          <span class="text-muted small">Latest student allotment entries</span>
        </div>
        @auth
          <a href="{{ route('allocations.index') }}" class="btn btn-white-subtle btn-sm">
            View All <i class="bi bi-arrow-right"></i>
          </a>
        @endauth
      </div>

      <div class="table-responsive">
        <table class="white-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Room &amp; Bed</th>
              <th>Allotment Date</th>
              <th>Fee Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentAllocations as $alloc)
              <tr>
                <td>
                  <div class="fw-semibold text-dark">{{ $alloc->student->full_name }}</div>
                  <div class="text-muted small">{{ $alloc->student->student_id_number }}</div>
                </td>
                <td>
                  <span class="fw-semibold text-primary">{{ $alloc->room->room_number }}</span>
                  <span class="badge-clean badge-clean-subtle ms-1">{{ $alloc->bed_number }}</span>
                  <div class="text-muted small">{{ $alloc->room->block }}</div>
                </td>
                <td class="text-muted small">
                  {{ $alloc->allocation_date->format('d M Y') }}
                </td>
                <td>
                  @if($alloc->fee_status === 'paid')
                    <span class="badge-clean badge-clean-success">Paid</span>
                  @elseif($alloc->fee_status === 'pending')
                    <span class="badge-clean badge-clean-warning">Pending</span>
                  @else
                    <span class="badge-clean badge-clean-info">Partial</span>
                  @endif
                </td>
                <td>
                  @auth
                    <a href="{{ route('allocations.show', $alloc) }}" class="btn btn-white-outline btn-sm py-0 px-2">
                      Details
                    </a>
                  @else
                    <span class="text-muted small">Login</span>
                  @endauth
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                  No allocation records found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Featured Rooms Availability -->
  <div class="col-lg-5">
    <div class="white-card h-100">
      <div class="white-card-header">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Rooms Status Snapshot</h2>
          <span class="text-muted small">Current status across blocks</span>
        </div>
        <a href="{{ route('rooms.index') }}" class="btn btn-white-subtle btn-sm">
          Browse Catalog <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="white-card-body p-3">
        <div class="row g-2">
          @foreach($featuredRooms as $rm)
            <div class="col-12">
              <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between hover-shadow">
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">{{ $rm->room_number }}</span>
                    @if($rm->status === 'available')
                      <span class="badge-clean badge-clean-success">Available</span>
                    @elseif($rm->status === 'full')
                      <span class="badge-clean badge-clean-danger">Full</span>
                    @else
                      <span class="badge-clean badge-clean-warning">Maintenance</span>
                    @endif
                  </div>
                  <div class="text-muted small mt-1">
                    {{ $rm->block }} &bull; {{ $rm->room_type }}
                  </div>
                </div>

                <div class="text-end">
                  <div class="fw-bold text-dark">₹{{ number_format($rm->monthly_fee) }}<span class="text-muted fw-normal small">/mo</span></div>
                  <div class="text-muted small">
                    {{ $rm->current_occupancy }}/{{ $rm->capacity }} Beds
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
