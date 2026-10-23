@extends('layouts.app')

@section('title', 'Edit Student ' . $student->full_name)

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    
    <div class="mb-3">
      <a href="{{ route('students.show', $student) }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Student Profile
      </a>
    </div>

    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h1 class="h5 fw-bold text-dark mb-0">Edit Student: {{ $student->full_name }}</h1>
          <span class="text-muted small">Update enrollment ID, contact details, or guardian records</span>
        </div>
      </div>

      <div class="white-card-body p-4">
        <form action="{{ route('students.update', $student) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="student_id_number" class="form-label-clean">Enrollment / Roll Number <span class="text-danger">*</span></label>
              <input type="text" name="student_id_number" id="student_id_number" class="form-control form-control-clean @error('student_id_number') is-invalid @enderror" value="{{ old('student_id_number', $student->student_id_number) }}" required>
              @error('student_id_number')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="full_name" class="form-label-clean">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="full_name" id="full_name" class="form-control form-control-clean @error('full_name') is-invalid @enderror" value="{{ old('full_name', $student->full_name) }}" required>
              @error('full_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="gender" class="form-label-clean">Gender <span class="text-danger">*</span></label>
              <select name="gender" id="gender" class="form-select form-select-clean @error('gender') is-invalid @enderror" required>
                <option value="Male" {{ old('gender', $student->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $student->gender) === 'Other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('gender')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="course" class="form-label-clean">Academic Course <span class="text-danger">*</span></label>
              <input type="text" name="course" id="course" class="form-control form-control-clean @error('course') is-invalid @enderror" value="{{ old('course', $student->course) }}" required>
              @error('course')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="semester" class="form-label-clean">Current Semester <span class="text-danger">*</span></label>
              <input type="number" name="semester" id="semester" class="form-control form-control-clean @error('semester') is-invalid @enderror" value="{{ old('semester', $student->semester) }}" min="1" max="12" required>
              @error('semester')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="email" class="form-label-clean">Student Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="email" class="form-control form-control-clean @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}" required>
              @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label-clean">Student Phone <span class="text-danger">*</span></label>
              <input type="text" name="phone" id="phone" class="form-control form-control-clean @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}" required>
              @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="guardian_name" class="form-label-clean">Guardian Name <span class="text-danger">*</span></label>
              <input type="text" name="guardian_name" id="guardian_name" class="form-control form-control-clean @error('guardian_name') is-invalid @enderror" value="{{ old('guardian_name', $student->guardian_name) }}" required>
              @error('guardian_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="guardian_phone" class="form-label-clean">Guardian Phone <span class="text-danger">*</span></label>
              <input type="text" name="guardian_phone" id="guardian_phone" class="form-control form-control-clean @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone', $student->guardian_phone) }}" required>
              @error('guardian_phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-8">
              <label for="address" class="form-label-clean">Permanent Address</label>
              <input type="text" name="address" id="address" class="form-control form-control-clean @error('address') is-invalid @enderror" value="{{ old('address', $student->address) }}">
            </div>

            <div class="col-md-4">
              <label for="blood_group" class="form-label-clean">Blood Group</label>
              <select name="blood_group" id="blood_group" class="form-select form-select-clean">
                <option value="">Unknown</option>
                <option value="A+" {{ old('blood_group', $student->blood_group) === 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A-" {{ old('blood_group', $student->blood_group) === 'A-' ? 'selected' : '' }}>A-</option>
                <option value="B+" {{ old('blood_group', $student->blood_group) === 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B-" {{ old('blood_group', $student->blood_group) === 'B-' ? 'selected' : '' }}>B-</option>
                <option value="O+" {{ old('blood_group', $student->blood_group) === 'O+' ? 'selected' : '' }}>O+</option>
                <option value="O-" {{ old('blood_group', $student->blood_group) === 'O-' ? 'selected' : '' }}>O-</option>
                <option value="AB+" {{ old('blood_group', $student->blood_group) === 'AB+' ? 'selected' : '' }}>AB+</option>
                <option value="AB-" {{ old('blood_group', $student->blood_group) === 'AB-' ? 'selected' : '' }}>AB-</option>
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('students.show', $student) }}" class="btn btn-white-outline">Cancel</a>
            <button type="submit" class="btn btn-white-primary">
              <i class="bi bi-check-lg me-1"></i> Update Profile
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
