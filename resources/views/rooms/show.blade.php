@extends('layouts.app')

@section('title', 'Room ' . $room->room_number . ' Details')

@section('content')
<!-- Breadcrumbs & Actions -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <div class="mb-1">
      <a href="{{ route('rooms.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Rooms Catalog
      </a>
    </div>
    <div class="d-flex align-items-center gap-2">
      <h1 class="h3 fw-bold text-dark mb-0">Room {{ $room->room_number }}</h1>
      @if($room->status === 'available')
        <span class="badge-clean badge-clean-success">Available</span>
      @elseif($room->status === 'full')
        <span class="badge-clean badge-clean-danger">Full</span>
      @else
        <span class="badge-clean badge-clean-warning">Under Maintenance</span>
      @endif
    </div>
  </div>

  @auth
    <div class="d-flex gap-2">
      @if($room->status === 'available')
        <a href="{{ route('allocations.create', ['room_id' => $room->id]) }}" class="btn btn-white-primary d-flex align-items-center gap-1">
          <i class="bi bi-person-plus"></i>
          <span>Allocate Student</span>
        </a>
      @endif
      <a href="{{ route('rooms.edit', $room) }}" class="btn btn-white-outline d-flex align-items-center gap-1">
        <i class="bi bi-pencil"></i>
        <span>Edit</span>
      </a>
      <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete Room {{ $room->room_number }}?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">
          <i class="bi bi-trash"></i>
        </button>
      </form>
    </div>
  @endauth
</div>

<div class="row g-4">
  <!-- Specifications Overview Card -->
  <div class="col-lg-4">
    <div class="white-card mb-4">
      <div class="white-card-header">
        <h2 class="h6 fw-bold text-dark mb-0">Room Specifications</h2>
      </div>
      <div class="white-card-body p-3">
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Hostel Wing / Block</span>
          <span class="fw-semibold text-dark">{{ $room->block }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Room Type</span>
          <span class="fw-semibold text-dark">{{ $room->room_type }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Floor</span>
          <span class="fw-semibold text-dark">Floor {{ $room->floor }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Capacity</span>
          <span class="fw-semibold text-dark">{{ $room->capacity }} Bed(s)</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Current Occupancy</span>
          <span class="fw-bold text-primary">{{ $room->current_occupancy }} / {{ $room->capacity }} Beds</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Available Vacancies</span>
          <span class="fw-bold {{ $room->availableBeds() > 0 ? 'text-success' : 'text-danger' }}">
            {{ $room->availableBeds() }} Vacant
          </span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <span class="text-muted small">Monthly Tariff</span>
          <span class="fw-bold text-dark h6 mb-0">₹{{ number_format($room->monthly_fee, 2) }}</span>
        </div>
      </div>
    </div>

    <!-- Amenities & Description -->
    <div class="white-card">
      <div class="white-card-header">
        <h2 class="h6 fw-bold text-dark mb-0">Facilities &amp; Amenities</h2>
      </div>
      <div class="white-card-body p-3">
        @if($room->amenities)
          <div class="d-flex flex-wrap gap-1 mb-3">
            @foreach(explode(',', $room->amenities) as $amenity)
              <span class="badge-clean badge-clean-subtle py-1 px-2">
                <i class="bi bi-check2 text-primary me-1"></i> {{ trim($amenity) }}
              </span>
            @endforeach
          </div>
        @else
          <p class="text-muted small mb-3">Standard hostel room furniture provided.</p>
        @endif

        @if($room->description)
          <div class="pt-2 border-top">
            <span class="d-block text-muted small fw-semibold mb-1">Room Notes</span>
            <p class="text-secondary small mb-0">{{ $room->description }}</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Active Occupants & Maintenance History -->
  <div class="col-lg-8">
    <!-- Active Students List -->
    <div class="white-card mb-4">
      <div class="white-card-header">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Current Occupants (Active Allocations)</h2>
          <span class="text-muted small">Students currently residing in this room</span>
        </div>
        @auth
          @if($room->status === 'available')
            <a href="{{ route('allocations.create', ['room_id' => $room->id]) }}" class="btn btn-white-subtle btn-sm">
              <i class="bi bi-person-plus me-1"></i> Allot Bed
            </a>
          @endif
        @endauth
      </div>

      <div class="table-responsive">
        <table class="white-table">
          <thead>
            <tr>
              <th>Bed</th>
              <th>Student Details</th>
              <th>Course / Sem</th>
              <th>Allotted On</th>
              <th>Fee Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($room->activeAllocations as $alloc)
              <tr>
                <td>
                  <span class="badge-clean badge-clean-subtle fw-bold">{{ $alloc->bed_number }}</span>
                </td>
                <td>
                  <a href="{{ route('students.show', $alloc->student) }}" class="fw-semibold text-dark text-decoration-none">
                    {{ $alloc->student->full_name }}
                  </a>
                  <div class="text-muted small">{{ $alloc->student->student_id_number }}</div>
                </td>
                <td class="small">
                  {{ $alloc->student->course }} <span class="text-muted">(Sem {{ $alloc->student->semester }})</span>
                </td>
                <td class="small text-muted">
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
                    <div class="d-flex gap-1">
                      <a href="{{ route('allocations.show', $alloc) }}" class="btn btn-white-outline btn-sm py-0 px-2" title="View Slip">
                        <i class="bi bi-file-text"></i>
                      </a>
                      <form action="{{ route('allocations.vacate', $alloc) }}" method="POST" onsubmit="return confirm('Vacate this student and free up the bed?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm py-0 px-2" title="Vacate Student">
                          <i class="bi bi-box-arrow-right"></i>
                        </button>
                      </form>
                    </div>
                  @else
                    -
                  @endauth
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  <i class="bi bi-door-open fs-2 d-block mb-1 text-muted"></i>
                  This room currently has no active student occupants.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Maintenance Records -->
    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Maintenance &amp; Service History</h2>
          <span class="text-muted small">Work orders &amp; repair requests for Room {{ $room->room_number }}</span>
        </div>
        @auth
          <a href="{{ route('maintenance.create') }}" class="btn btn-white-subtle btn-sm">
            <i class="bi bi-wrench me-1"></i> Report Issue
          </a>
        @endauth
      </div>

      <div class="table-responsive">
        <table class="white-table">
          <thead>
            <tr>
              <th>Issue</th>
              <th>Reported By</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($room->maintenanceRequests as $m)
              <tr>
                <td>
                  <span class="fw-semibold text-dark">{{ $m->title }}</span>
                  <div class="text-muted small">{{ Str::limit($m->description, 50) }}</div>
                </td>
                <td class="small">{{ $m->student ? $m->student->full_name : 'Warden' }}</td>
                <td>
                  @if($m->priority === 'urgent')
                    <span class="badge-clean badge-clean-danger">Urgent</span>
                  @elseif($m->priority === 'high')
                    <span class="badge-clean badge-clean-warning">High</span>
                  @else
                    <span class="badge-clean badge-clean-subtle text-capitalize">{{ $m->priority }}</span>
                  @endif
                </td>
                <td>
                  @if($m->status === 'resolved')
                    <span class="badge-clean badge-clean-success">Resolved</span>
                  @elseif($m->status === 'in_progress')
                    <span class="badge-clean badge-clean-info">In Progress</span>
                  @else
                    <span class="badge-clean badge-clean-warning">Reported</span>
                  @endif
                </td>
                <td class="text-muted small">{{ $m->reported_at->format('d M Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-3 text-muted small">
                  No maintenance issues logged for this room.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
