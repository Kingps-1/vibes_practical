@extends('layouts.app')

@section('title', 'Room Allocations Management')

@section('content')
<!-- Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Room Allocations</h1>
    <p class="text-muted mb-0">Manage resident bed assignments, check fee statuses, and process vacates</p>
  </div>
  @auth
    <a href="{{ route('allocations.create') }}" class="btn btn-white-primary d-flex align-items-center gap-2">
      <i class="bi bi-person-plus"></i>
      <span>New Room Allocation</span>
    </a>
  @endauth
</div>

<!-- Search & Filter Bar -->
<div class="white-card mb-4">
  <div class="white-card-body p-3 p-md-4">
    <form action="{{ route('allocations.index') }}" method="GET">
      <div class="row g-2 align-items-end">
        
        <div class="col-md-5">
          <label for="search" class="form-label-clean">Search Allocations</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" id="search" class="form-control form-control-clean border-start-0" placeholder="Student name, roll no, or room number..." value="{{ request('search') }}">
          </div>
        </div>

        <div class="col-sm-6 col-md-3">
          <label for="status" class="form-label-clean">Allocation Status</label>
          <select name="status" id="status" class="form-select form-select-clean">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Residents</option>
            <option value="vacated" {{ request('status') === 'vacated' ? 'selected' : '' }}>Vacated History</option>
          </select>
        </div>

        <div class="col-sm-6 col-md-2">
          <label for="fee_status" class="form-label-clean">Fee Status</label>
          <select name="fee_status" id="fee_status" class="form-select form-select-clean">
            <option value="">All Fees</option>
            <option value="paid" {{ request('fee_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('fee_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="partial" {{ request('fee_status') === 'partial' ? 'selected' : '' }}>Partial</option>
          </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-white-primary flex-grow-1">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
          @if(request()->hasAny(['search', 'status', 'fee_status']))
            <a href="{{ route('allocations.index') }}" class="btn btn-white-outline" title="Reset Filters">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Allocations Table -->
<div class="white-table-container">
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Slip ID</th>
          <th>Student Resident</th>
          <th>Room &amp; Bed</th>
          <th>Hostel Wing</th>
          <th>Allotted Date</th>
          <th>Fee Status</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($allocations as $alloc)
          <tr>
            <td>
              <span class="font-monospace text-muted small">#AL-{{ str_pad($alloc->id, 4, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td>
              <a href="{{ route('students.show', $alloc->student) }}" class="fw-bold text-dark text-decoration-none">
                {{ $alloc->student->full_name }}
              </a>
              <div class="text-muted small">{{ $alloc->student->student_id_number }} &bull; {{ $alloc->student->course }}</div>
            </td>
            <td>
              <a href="{{ route('rooms.show', $alloc->room) }}" class="fw-semibold text-primary text-decoration-none">
                {{ $alloc->room->room_number }}
              </a>
              <span class="badge-clean badge-clean-subtle ms-1">{{ $alloc->bed_number }}</span>
            </td>
            <td class="text-secondary small">
              {{ $alloc->room->block }}
            </td>
            <td class="text-muted small">
              {{ $alloc->allocation_date->format('d M Y') }}
              @if($alloc->status === 'vacated' && $alloc->vacate_date)
                <div class="text-danger small">Vacated: {{ $alloc->vacate_date->format('d M Y') }}</div>
              @endif
            </td>
            <td>
              @if($alloc->fee_status === 'paid')
                <span class="badge-clean badge-clean-success">
                  <i class="bi bi-check-circle"></i> Paid
                </span>
              @elseif($alloc->fee_status === 'pending')
                <span class="badge-clean badge-clean-warning">
                  <i class="bi bi-clock"></i> Pending
                </span>
              @else
                <span class="badge-clean badge-clean-info">
                  <i class="bi bi-pie-chart"></i> Partial
                </span>
              @endif
            </td>
            <td>
              @if($alloc->status === 'active')
                <span class="badge-clean badge-clean-success">Active</span>
              @else
                <span class="badge-clean badge-clean-subtle text-muted">Vacated</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-1">
                <a href="{{ route('allocations.show', $alloc) }}" class="btn btn-white-outline btn-sm py-1 px-2" title="Print Allotment Slip">
                  <i class="bi bi-printer"></i>
                </a>
                <a href="{{ route('allocations.edit', $alloc) }}" class="btn btn-white-subtle btn-sm py-1 px-2" title="Edit Allocation">
                  <i class="bi bi-pencil"></i>
                </a>
                @if($alloc->status === 'active')
                  <form action="{{ route('allocations.vacate', $alloc) }}" method="POST" onsubmit="return confirm('Vacate student from Room {{ $alloc->room->room_number }}? This releases the bed.');">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning btn-sm py-1 px-2" title="Vacate Student">
                      <i class="bi bi-box-arrow-right"></i>
                    </button>
                  </form>
                @endif
                <form action="{{ route('allocations.destroy', $alloc) }}" method="POST" onsubmit="return confirm('Permanently delete this allocation record?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Delete Record">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-person-x fs-1 text-muted d-block mb-2"></i>
              No allocation records match the filter criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination -->
<div class="mt-4 d-flex justify-content-center">
  {{ $allocations->links('pagination::bootstrap-5') }}
</div>
@endsection
