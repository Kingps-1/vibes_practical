@extends('layouts.app')

@section('title', 'Allocate Hostel Room')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    
    <!-- Breadcrumb -->
    <div class="mb-3">
      <a href="{{ route('allocations.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Allocations
      </a>
    </div>

    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h1 class="h5 fw-bold text-dark mb-0">Allocate Hostel Room to Student</h1>
          <span class="text-muted small">Assign a vacant bed and establish residence record</span>
        </div>
      </div>

      <div class="white-card-body p-4">
        
        @if($students->isEmpty())
          <div class="alert alert-clean alert-clean-info mb-4">
            <i class="bi bi-info-circle text-info fs-5"></i>
            <div>
              <strong>No Unallocated Students Found</strong>
              <p class="mb-2 small text-secondary">All registered students currently have active room allocations, or no students are in the database.</p>
              <a href="{{ route('students.create') }}" class="btn btn-white-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i> Register New Student
              </a>
            </div>
          </div>
        @endif

        @if($rooms->isEmpty())
          <div class="alert alert-clean alert-clean-danger mb-4">
            <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
            <div>
              <strong>No Available Rooms Found</strong>
              <p class="mb-2 small text-secondary">All rooms are currently full or under maintenance. You must add a new room or vacate an existing resident.</p>
              <a href="{{ route('rooms.create') }}" class="btn btn-white-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Room
              </a>
            </div>
          </div>
        @endif

        <form action="{{ route('allocations.store') }}" method="POST">
          @csrf

          <!-- Student Selection -->
          <div class="mb-3">
            <label for="student_id" class="form-label-clean">Select Student Resident <span class="text-danger">*</span></label>
            <select name="student_id" id="student_id" class="form-select form-select-clean @error('student_id') is-invalid @enderror" required>
              <option value="">-- Choose unallocated student --</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                  {{ $s->full_name }} ({{ $s->student_id_number }}) - {{ $s->course }} - {{ $s->gender }}
                </option>
              @endforeach
            </select>
            @error('student_id')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Room Selection -->
          <div class="mb-3">
            <label for="room_id" class="form-label-clean">Select Available Room <span class="text-danger">*</span></label>
            <select name="room_id" id="room_id" class="form-select form-select-clean @error('room_id') is-invalid @enderror" required>
              <option value="">-- Choose vacant room --</option>
              @foreach($rooms as $r)
                <option value="{{ $r->id }}" {{ (old('room_id', $selectedRoomId) == $r->id) ? 'selected' : '' }}>
                  {{ $r->room_number }} - {{ $r->block }} ({{ $r->room_type }}) - {{ $r->availableBeds() }} Vacant Bed(s) - ₹{{ number_format($r->monthly_fee) }}/mo
                </option>
              @endforeach
            </select>
            @error('room_id')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3 mb-3">
            <!-- Bed Identifier -->
            <div class="col-md-4">
              <label for="bed_number" class="form-label-clean">Bed Identifier <span class="text-danger">*</span></label>
              <select name="bed_number" id="bed_number" class="form-select form-select-clean @error('bed_number') is-invalid @enderror" required>
                <option value="Bed 1" {{ old('bed_number') === 'Bed 1' ? 'selected' : '' }}>Bed 1 (Window Side)</option>
                <option value="Bed 2" {{ old('bed_number', 'Bed 2') === 'Bed 2' ? 'selected' : '' }}>Bed 2 (Door Side)</option>
                <option value="Bed 3" {{ old('bed_number') === 'Bed 3' ? 'selected' : '' }}>Bed 3 (Balcony Side)</option>
                <option value="Single Allotment" {{ old('bed_number') === 'Single Allotment' ? 'selected' : '' }}>Full Room Allotment</option>
              </select>
              @error('bed_number')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Allocation Date -->
            <div class="col-md-4">
              <label for="allocation_date" class="form-label-clean">Allotment Date <span class="text-danger">*</span></label>
              <input type="date" name="allocation_date" id="allocation_date" class="form-control form-control-clean @error('allocation_date') is-invalid @enderror" value="{{ old('allocation_date', date('Y-m-d')) }}" required>
              @error('allocation_date')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <!-- Fee Status -->
            <div class="col-md-4">
              <label for="fee_status" class="form-label-clean">Hostel Fee Status <span class="text-danger">*</span></label>
              <select name="fee_status" id="fee_status" class="form-select form-select-clean @error('fee_status') is-invalid @enderror" required>
                <option value="paid" {{ old('fee_status', 'paid') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ old('fee_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="partial" {{ old('fee_status') === 'partial' ? 'selected' : '' }}>Partial Payment</option>
              </select>
              @error('fee_status')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Remarks -->
          <div class="mb-4">
            <label for="remarks" class="form-label-clean">Allotment Remarks / Conditions</label>
            <textarea name="remarks" id="remarks" rows="3" class="form-control form-control-clean @error('remarks') is-invalid @enderror" placeholder="e.g. Identity verified, luggage allowed, signed hostel agreement...">{{ old('remarks') }}</textarea>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('allocations.index') }}" class="btn btn-white-outline">Cancel</a>
            <button type="submit" class="btn btn-white-primary" {{ ($students->isEmpty() || $rooms->isEmpty()) ? 'disabled' : '' }}>
              <i class="bi bi-check-circle me-1"></i> Confirm &amp; Allot Room
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
