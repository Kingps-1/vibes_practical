@extends('layouts.app')

@section('title', 'Student Profile - ' . $student->full_name)

@section('content')
<!-- Header & Actions -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <div class="mb-1">
      <a href="{{ route('students.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Students Directory
      </a>
    </div>
    <div class="d-flex align-items-center gap-2">
      <h1 class="h3 fw-bold text-dark mb-0">{{ $student->full_name }}</h1>
      <span class="badge-clean badge-clean-subtle font-monospace">{{ $student->student_id_number }}</span>
    </div>
  </div>

  @auth
    <div class="d-flex gap-2">
      <a href="{{ route('students.edit', $student) }}" class="btn btn-white-outline d-flex align-items-center gap-1">
        <i class="bi bi-pencil"></i>
        <span>Edit Details</span>
      </a>
      @if(!$student->activeAllocation)
        <a href="{{ route('allocations.create') }}" class="btn btn-white-primary d-flex align-items-center gap-1">
          <i class="bi bi-door-open"></i>
          <span>Allot Room</span>
        </a>
      @endif
    </div>
  @endauth
</div>

<div class="row g-4">
  <!-- Profile Particulars Card -->
  <div class="col-lg-4">
    <div class="white-card mb-4">
      <div class="white-card-header">
        <h2 class="h6 fw-bold text-dark mb-0">Student Particulars</h2>
      </div>
      <div class="white-card-body p-3">
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Course</span>
          <span class="fw-semibold text-dark">{{ $student->course }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Semester</span>
          <span class="fw-semibold text-dark">Semester {{ $student->semester }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Gender</span>
          <span class="text-dark">{{ $student->gender }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Blood Group</span>
          <span class="text-dark">{{ $student->blood_group ?? 'Not specified' }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Student Email</span>
          <span class="text-dark small">{{ $student->email }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Student Mobile</span>
          <span class="text-dark">{{ $student->phone }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted small">Parent / Guardian</span>
          <span class="fw-semibold text-dark">{{ $student->guardian_name }}</span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <span class="text-muted small">Guardian Contact</span>
          <span class="text-dark">{{ $student->guardian_phone }}</span>
        </div>
        @if($student->address)
          <div class="pt-2 mt-2 border-top">
            <span class="text-muted small d-block">Permanent Address:</span>
            <span class="text-secondary small">{{ $student->address }}</span>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Residence & Allotment History -->
  <div class="col-lg-8">
    
    <!-- Active Room Allotment Status -->
    <div class="white-card mb-4">
      <div class="white-card-header">
        <h2 class="h6 fw-bold text-dark mb-0">Current Room Allotment</h2>
      </div>
      <div class="white-card-body p-4">
        @if($student->activeAllocation)
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
              <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge-clean badge-clean-success">Active Resident</span>
                <span class="text-muted small">Since {{ $student->activeAllocation->allocation_date->format('M d, Y') }}</span>
              </div>
              <h3 class="h4 fw-bold text-dark mb-1">
                Room {{ $student->activeAllocation->room->room_number }} &bull; {{ $student->activeAllocation->bed_number }}
              </h3>
              <p class="text-muted mb-0 small">
                {{ $student->activeAllocation->room->block }} &bull; {{ $student->activeAllocation->room->room_type }} &bull; ₹{{ number_format($student->activeAllocation->room->monthly_fee) }}/mo
              </p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
              <a href="{{ route('allocations.show', $student->activeAllocation) }}" class="btn btn-white-outline btn-sm">
                <i class="bi bi-printer me-1"></i> Slip
              </a>
              @auth
                <form action="{{ route('allocations.vacate', $student->activeAllocation) }}" method="POST" onsubmit="return confirm('Vacate student from Room {{ $student->activeAllocation->room->room_number }}?');">
                  @csrf
                  <button type="submit" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Vacate Bed
                  </button>
                </form>
              @endauth
            </div>
          </div>
        @else
          <div class="text-center py-4">
            <i class="bi bi-house-door text-muted fs-2 d-block mb-1"></i>
            <span class="fw-semibold text-dark">No Active Room Allotment</span>
            <p class="text-muted small mb-3">This student is currently not allotted to any hostel room or bed.</p>
            @auth
              <a href="{{ route('allocations.create') }}" class="btn btn-white-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Assign Room Now
              </a>
            @endauth
          </div>
        @endif
      </div>
    </div>

    <!-- Allocation History Table -->
    <div class="white-card mb-4">
      <div class="white-card-header">
        <h2 class="h6 fw-bold text-dark mb-0">Allotment History &amp; Records</h2>
      </div>
      <div class="table-responsive">
        <table class="white-table">
          <thead>
            <tr>
              <th>Room</th>
              <th>Bed</th>
              <th>Allotted On</th>
              <th>Vacated On</th>
              <th>Status</th>
              <th>Fee Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($student->allocations as $h)
              <tr>
                <td>
                  <a href="{{ route('rooms.show', $h->room) }}" class="fw-semibold text-dark text-decoration-none">
                    {{ $h->room->room_number }}
                  </a>
                  <div class="text-muted small">{{ $h->room->block }}</div>
                </td>
                <td>{{ $h->bed_number }}</td>
                <td class="small text-muted">{{ $h->allocation_date->format('d M Y') }}</td>
                <td class="small text-muted">
                  {{ $h->vacate_date ? $h->vacate_date->format('d M Y') : '—' }}
                </td>
                <td>
                  @if($h->status === 'active')
                    <span class="badge-clean badge-clean-success">Active</span>
                  @else
                    <span class="badge-clean badge-clean-subtle">Vacated</span>
                  @endif
                </td>
                <td>
                  @if($h->fee_status === 'paid')
                    <span class="badge-clean badge-clean-success">Paid</span>
                  @else
                    <span class="badge-clean badge-clean-warning">Pending</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-3 text-muted small">No past allocation history.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
