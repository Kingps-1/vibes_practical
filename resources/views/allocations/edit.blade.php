@extends('layouts.app')

@section('title', 'Edit Allocation #' . $allocation->id)

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    
    <!-- Breadcrumb -->
    <div class="mb-3">
      <a href="{{ route('allocations.show', $allocation) }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Allocation Slip
      </a>
    </div>

    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h1 class="h5 fw-bold text-dark mb-0">Edit Allocation Record</h1>
          <span class="text-muted small">Update bed, fee status, and vacation dates for {{ $allocation->student->full_name }}</span>
        </div>
      </div>

      <div class="white-card-body p-4">
        
        <!-- Summary Strip -->
        <div class="p-3 bg-light rounded-3 border mb-4 d-flex justify-content-between align-items-center">
          <div>
            <span class="fw-bold text-dark">{{ $allocation->student->full_name }}</span> ({{ $allocation->student->student_id_number }})
            <div class="text-muted small">Room {{ $allocation->room->room_number }} &bull; {{ $allocation->room->block }}</div>
          </div>
          <div>
            <span class="badge-clean badge-clean-subtle">Slip #AL-{{ str_pad($allocation->id, 4, '0', STR_PAD_LEFT) }}</span>
          </div>
        </div>

        <form action="{{ route('allocations.update', $allocation) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="bed_number" class="form-label-clean">Assigned Bed <span class="text-danger">*</span></label>
              <select name="bed_number" id="bed_number" class="form-select form-select-clean @error('bed_number') is-invalid @enderror" required>
                <option value="Bed 1" {{ old('bed_number', $allocation->bed_number) === 'Bed 1' ? 'selected' : '' }}>Bed 1 (Window Side)</option>
                <option value="Bed 2" {{ old('bed_number', $allocation->bed_number) === 'Bed 2' ? 'selected' : '' }}>Bed 2 (Door Side)</option>
                <option value="Bed 3" {{ old('bed_number', $allocation->bed_number) === 'Bed 3' ? 'selected' : '' }}>Bed 3 (Balcony Side)</option>
                <option value="Single Allotment" {{ old('bed_number', $allocation->bed_number) === 'Single Allotment' ? 'selected' : '' }}>Full Room Allotment</option>
              </select>
              @error('bed_number')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="fee_status" class="form-label-clean">Fee Status <span class="text-danger">*</span></label>
              <select name="fee_status" id="fee_status" class="form-select form-select-clean @error('fee_status') is-invalid @enderror" required>
                <option value="paid" {{ old('fee_status', $allocation->fee_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ old('fee_status', $allocation->fee_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="partial" {{ old('fee_status', $allocation->fee_status) === 'partial' ? 'selected' : '' }}>Partial Payment</option>
              </select>
              @error('fee_status')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="allocation_date" class="form-label-clean">Allotment Date <span class="text-danger">*</span></label>
              <input type="date" name="allocation_date" id="allocation_date" class="form-control form-control-clean @error('allocation_date') is-invalid @enderror" value="{{ old('allocation_date', $allocation->allocation_date->format('Y-m-d')) }}" required>
              @error('allocation_date')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="status" class="form-label-clean">Resident Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select form-select-clean @error('status') is-invalid @enderror" required>
                <option value="active" {{ old('status', $allocation->status) === 'active' ? 'selected' : '' }}>Active (Occupying Bed)</option>
                <option value="vacated" {{ old('status', $allocation->status) === 'vacated' ? 'selected' : '' }}>Vacated (Bed Released)</option>
                <option value="cancelled" {{ old('status', $allocation->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
              @error('status')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="vacate_date" class="form-label-clean">Vacate Date (if applicable)</label>
              <input type="date" name="vacate_date" id="vacate_date" class="form-control form-control-clean @error('vacate_date') is-invalid @enderror" value="{{ old('vacate_date', $allocation->vacate_date ? $allocation->vacate_date->format('Y-m-d') : '') }}">
              @error('vacate_date')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-4">
            <label for="remarks" class="form-label-clean">Remarks / History Log</label>
            <textarea name="remarks" id="remarks" rows="3" class="form-control form-control-clean @error('remarks') is-invalid @enderror">{{ old('remarks', $allocation->remarks) }}</textarea>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('allocations.show', $allocation) }}" class="btn btn-white-outline">Cancel</a>
            <button type="submit" class="btn btn-white-primary">
              <i class="bi bi-check-lg me-1"></i> Update Allocation
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
