@extends('layouts.app')

@section('title', 'Report Maintenance Issue')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    
    <div class="mb-3">
      <a href="{{ route('maintenance.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Maintenance Tickets
      </a>
    </div>

    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h1 class="h5 fw-bold text-dark mb-0">Report Hostel Maintenance / Repair Issue</h1>
          <span class="text-muted small">Submit facility issues (plumbing, electrical, furniture, AC, Wi-Fi)</span>
        </div>
      </div>

      <div class="white-card-body p-4">
        <form action="{{ route('maintenance.store') }}" method="POST">
          @csrf

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="room_id" class="form-label-clean">Affected Room Number <span class="text-danger">*</span></label>
              <select name="room_id" id="room_id" class="form-select form-select-clean @error('room_id') is-invalid @enderror" required>
                <option value="">-- Choose Room --</option>
                @foreach($rooms as $r)
                  <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                    Room {{ $r->room_number }} ({{ $r->block }})
                  </option>
                @endforeach
              </select>
              @error('room_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="student_id" class="form-label-clean">Reporting Resident / Student <span class="text-danger">*</span></label>
              <select name="student_id" id="student_id" class="form-select form-select-clean @error('student_id') is-invalid @enderror" required>
                <option value="">-- Choose Student --</option>
                @foreach($students as $s)
                  <option value="{{ $s->id }}" {{ (old('student_id', $student ? $student->id : null) == $s->id) ? 'selected' : '' }}>
                    {{ $s->full_name }} ({{ $s->student_id_number }})
                  </option>
                @endforeach
              </select>
              @error('student_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label for="title" class="form-label-clean">Issue Subject / Summary <span class="text-danger">*</span></label>
              <input type="text" name="title" id="title" class="form-control form-control-clean @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Bathroom tap leaking, tube light blinking, AC not cooling">
              @error('title')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="priority" class="form-label-clean">Urgency Priority <span class="text-danger">*</span></label>
              <select name="priority" id="priority" class="form-select form-select-clean @error('priority') is-invalid @enderror" required>
                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (Minor fix)</option>
                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (Routine)</option>
                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Needs Attention)</option>
                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent (Immediate Safety/Water/Power)</option>
              </select>
              @error('priority')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-4">
            <label for="description" class="form-label-clean">Detailed Description of Problem <span class="text-danger">*</span></label>
            <textarea name="description" id="description" rows="4" class="form-control form-control-clean @error('description') is-invalid @enderror" required placeholder="Describe the location in the room, when it started, and any symptoms...">{{ old('description') }}</textarea>
            @error('description')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('maintenance.index') }}" class="btn btn-white-outline">Cancel</a>
            <button type="submit" class="btn btn-white-primary">
              <i class="bi bi-send me-1"></i> Submit Maintenance Ticket
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
