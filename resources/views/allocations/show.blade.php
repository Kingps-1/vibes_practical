@extends('layouts.app')

@section('title', 'Allocation Slip #' . $allocation->id)

@section('content')
<!-- Actions & Print Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 d-print-none">
  <div>
    <a href="{{ route('allocations.index') }}" class="text-decoration-none text-muted small">
      <i class="bi bi-arrow-left me-1"></i> Back to All Allocations
    </a>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-white-outline d-flex align-items-center gap-1">
      <i class="bi bi-printer"></i>
      <span>Print Slip</span>
    </button>
    <a href="{{ route('allocations.edit', $allocation) }}" class="btn btn-white-outline d-flex align-items-center gap-1">
      <i class="bi bi-pencil"></i>
      <span>Edit</span>
    </a>
    @if($allocation->status === 'active')
      <form action="{{ route('allocations.vacate', $allocation) }}" method="POST" onsubmit="return confirm('Vacate this resident and release bed?');">
        @csrf
        <button type="submit" class="btn btn-outline-warning d-flex align-items-center gap-1">
          <i class="bi bi-box-arrow-right"></i>
          <span>Vacate Bed</span>
        </button>
      </form>
    @endif
  </div>
</div>

<!-- Formal Allotment Slip Card -->
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="white-card p-4 p-md-5 border" style="background-color: #ffffff;">
      
      <!-- Institutional Letterhead -->
      <div class="text-center pb-4 mb-4 border-bottom">
        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
          <div class="brand-icon-wrapper" style="width: 42px; height: 42px;">
            <i class="bi bi-building"></i>
          </div>
          <span class="h4 fw-bold text-dark mb-0">UNIVERSITY HOSTEL RESIDENCE</span>
        </div>
        <p class="text-muted small mb-1">Campus Board of Residence &bull; Student Welfare Division</p>
        <span class="badge-clean badge-clean-subtle text-uppercase fw-bold letter-spacing-1">Official Room Allotment Slip</span>
      </div>

      <!-- Slip Meta Details -->
      <div class="row g-3 mb-4">
        <div class="col-sm-6">
          <div class="text-muted small">Allotment Slip ID:</div>
          <div class="font-monospace fw-bold text-dark">#AL-{{ str_pad($allocation->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="col-sm-6 text-sm-end">
          <div class="text-muted small">Date of Issue:</div>
          <div class="fw-semibold text-dark">{{ $allocation->allocation_date->format('F d, Y') }}</div>
        </div>
      </div>

      <!-- Student Particulars -->
      <div class="mb-4">
        <h6 class="text-uppercase fw-bold text-muted small border-bottom pb-2 mb-3">1. Student Resident Particulars</h6>
        <div class="row g-3">
          <div class="col-sm-6">
            <span class="d-block text-muted small">Student Full Name:</span>
            <span class="fw-bold text-dark">{{ $allocation->student->full_name }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Enrollment / Roll Number:</span>
            <span class="fw-semibold text-dark">{{ $allocation->student->student_id_number }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Course &amp; Semester:</span>
            <span class="text-dark">{{ $allocation->student->course }} (Semester {{ $allocation->student->semester }})</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Contact Phone &amp; Email:</span>
            <span class="text-dark">{{ $allocation->student->phone }} &bull; {{ $allocation->student->email }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Emergency / Guardian Contact:</span>
            <span class="text-dark">{{ $allocation->student->guardian_name }} ({{ $allocation->student->guardian_phone }})</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Gender &amp; Blood Group:</span>
            <span class="text-dark">{{ $allocation->student->gender }} &bull; {{ $allocation->student->blood_group ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <!-- Room Allocation Particulars -->
      <div class="mb-4">
        <h6 class="text-uppercase fw-bold text-muted small border-bottom pb-2 mb-3">2. Allotted Room &amp; Hostel Details</h6>
        <div class="row g-3">
          <div class="col-sm-6">
            <span class="d-block text-muted small">Hostel Wing / Block:</span>
            <span class="fw-bold text-dark">{{ $allocation->room->block }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Assigned Room Number:</span>
            <span class="h5 fw-bold text-primary mb-0">{{ $allocation->room->room_number }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Bed Identifier:</span>
            <span class="badge-clean badge-clean-subtle fw-semibold">{{ $allocation->bed_number }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Room Type &amp; Floor:</span>
            <span class="text-dark">{{ $allocation->room->room_type }} &bull; Floor {{ $allocation->room->floor }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Monthly Hostel Fee:</span>
            <span class="fw-bold text-dark">₹{{ number_format($allocation->room->monthly_fee, 2) }}</span>
          </div>
          <div class="col-sm-6">
            <span class="d-block text-muted small">Current Fee Status:</span>
            @if($allocation->fee_status === 'paid')
              <span class="badge-clean badge-clean-success">Fee Fully Paid</span>
            @elseif($allocation->fee_status === 'pending')
              <span class="badge-clean badge-clean-warning">Payment Pending</span>
            @else
              <span class="badge-clean badge-clean-info">Partial Payment</span>
            @endif
          </div>
        </div>
      </div>

      <!-- Remarks / Status -->
      @if($allocation->remarks)
        <div class="p-3 bg-light rounded-2 border mb-4">
          <span class="text-muted small fw-semibold d-block mb-1">Office Remarks:</span>
          <span class="text-secondary small">{{ $allocation->remarks }}</span>
        </div>
      @endif

      @if($allocation->status === 'vacated')
        <div class="alert alert-clean alert-clean-warning mb-4">
          <i class="bi bi-info-circle text-warning fs-5"></i>
          <div>
            <strong>Notice of Vacation</strong>
            <p class="mb-0 small text-secondary">This resident has vacated this room on {{ $allocation->vacate_date ? $allocation->vacate_date->format('F d, Y') : 'Record' }}. Bed is released.</p>
          </div>
        </div>
      @endif

      <!-- Signatures Footer -->
      <div class="row pt-5 mt-4 border-top">
        <div class="col-6 text-center">
          <div style="height: 40px;"></div>
          <div class="border-top pt-2 text-muted small">Signature of Student Resident</div>
        </div>
        <div class="col-6 text-center">
          <div style="height: 40px;" class="fw-bold text-dark d-flex align-items-center justify-content-center">
            {{ $allocation->allocatedByUser ? $allocation->allocatedByUser->name : 'Hostel Warden' }}
          </div>
          <div class="border-top pt-2 text-muted small">Authorized Warden / Administrator</div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
