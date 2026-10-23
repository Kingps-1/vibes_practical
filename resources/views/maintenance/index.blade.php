@extends('layouts.app')

@section('title', 'Hostel Maintenance & Repair Requests')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Hostel Maintenance &amp; Repairs</h1>
    <p class="text-muted mb-0">Track facility repair tickets, electrical/plumbing issues, and work order statuses</p>
  </div>
  @auth
    <a href="{{ route('maintenance.create') }}" class="btn btn-white-primary d-flex align-items-center gap-2">
      <i class="bi bi-wrench"></i>
      <span>Report Maintenance Issue</span>
    </a>
  @endauth
</div>

<!-- Filter Bar -->
<div class="white-card mb-4">
  <div class="white-card-body p-3">
    <form action="{{ route('maintenance.index') }}" method="GET" class="row g-2 align-items-center">
      <div class="col-md-4">
        <select name="status" class="form-select form-select-clean">
          <option value="">All Statuses</option>
          <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>Reported (Pending)</option>
          <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
          <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>
      </div>
      <div class="col-md-4">
        <select name="priority" class="form-select form-select-clean">
          <option value="">All Priorities</option>
          <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
          <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
          <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
          <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
        </select>
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-white-primary flex-grow-1">Filter</button>
        @if(request()->hasAny(['status', 'priority']))
          <a href="{{ route('maintenance.index') }}" class="btn btn-white-outline">Reset</a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Maintenance Requests Table -->
<div class="white-table-container">
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Ticket #</th>
          <th>Room</th>
          <th>Reported Issue</th>
          <th>Resident</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Reported On</th>
          <th class="text-end">Update Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($requests as $req)
          <tr>
            <td class="font-monospace text-muted small">#TCK-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td>
              <a href="{{ route('rooms.show', $req->room) }}" class="fw-bold text-primary text-decoration-none">
                {{ $req->room->room_number }}
              </a>
              <div class="text-muted small">{{ $req->room->block }}</div>
            </td>
            <td>
              <div class="fw-semibold text-dark">{{ $req->title }}</div>
              <div class="text-secondary small">{{ $req->description }}</div>
            </td>
            <td class="small">
              {{ $req->student ? $req->student->full_name : 'Staff' }}
            </td>
            <td>
              @if($req->priority === 'urgent')
                <span class="badge-clean badge-clean-danger">Urgent</span>
              @elseif($req->priority === 'high')
                <span class="badge-clean badge-clean-warning">High</span>
              @elseif($req->priority === 'medium')
                <span class="badge-clean badge-clean-info">Medium</span>
              @else
                <span class="badge-clean badge-clean-subtle">Low</span>
              @endif
            </td>
            <td>
              @if($req->status === 'resolved')
                <span class="badge-clean badge-clean-success">
                  <i class="bi bi-check-circle"></i> Resolved
                </span>
                @if($req->resolved_at)
                  <div class="text-muted small" style="font-size: 0.72rem;">{{ $req->resolved_at->format('d M') }}</div>
                @endif
              @elseif($req->status === 'in_progress')
                <span class="badge-clean badge-clean-info">
                  <i class="bi bi-gear-wide-connected"></i> In Progress
                </span>
              @else
                <span class="badge-clean badge-clean-warning">
                  <i class="bi bi-clock"></i> Reported
                </span>
              @endif
            </td>
            <td class="text-muted small">
              {{ $req->reported_at->format('d M Y') }}
            </td>
            <td class="text-end">
              @auth
                <form action="{{ route('maintenance.updateStatus', $req) }}" method="POST" class="d-inline-flex gap-1">
                  @csrf
                  @method('PATCH')
                  <select name="status" class="form-select form-select-sm py-0 px-2" style="font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                    <option value="reported" {{ $req->status === 'reported' ? 'selected' : '' }}>Reported</option>
                    <option value="in_progress" {{ $req->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $req->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                  </select>
                </form>
              @else
                -
              @endauth
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-tools fs-1 text-muted d-block mb-2"></i>
              No maintenance issues found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4 d-flex justify-content-center">
  {{ $requests->links('pagination::bootstrap-5') }}
</div>
@endsection
