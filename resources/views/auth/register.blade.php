@extends('layouts.app')

@section('title', 'Create Account - Hostel Management Portal')

@section('content')
<div class="row justify-content-center py-4">
  <div class="col-md-7 col-lg-6">
    
    <div class="white-card">
      <div class="white-card-body p-4 p-md-5">
        
        <!-- Header -->
        <div class="text-center mb-4">
          <div class="brand-icon-wrapper mb-3" style="width: 48px; height: 48px; font-size: 1.4rem;">
            <i class="bi bi-person-plus"></i>
          </div>
          <h2 class="h4 fw-bold text-dark mb-1">Create an Account</h2>
          <p class="text-muted small mb-0">Join the HostelEase portal to access room allocation services</p>
        </div>

        <!-- Registration Form -->
        <form action="{{ route('register') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="name" class="form-label-clean">Full Name</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
              <input type="text" name="name" id="name" class="form-control form-control-clean border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. Rahul Sharma">
            </div>
            @error('name')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-7">
              <label for="email" class="form-label-clean">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control form-control-clean border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="name@hostel.edu">
              </div>
              @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-5">
              <label for="phone" class="form-label-clean">Contact Phone</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                <input type="text" name="phone" id="phone" class="form-control form-control-clean border-start-0 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+91 98765 00000">
              </div>
              @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="role" class="form-label-clean">Portal Account Role</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
              <select name="role" id="role" class="form-select form-select-clean border-start-0 @error('role') is-invalid @enderror" required>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student (Resident / Applicant)</option>
                <option value="warden" {{ old('role') === 'warden' ? 'selected' : '' }}>Hostel Warden (Staff / Officer)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>System Administrator</option>
              </select>
            </div>
            <div class="form-text small text-muted">Choosing Student will automatically link your profile to student services.</div>
            @error('role')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label for="password" class="form-label-clean">Password (min 6 chars)</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control form-control-clean border-start-0 @error('password') is-invalid @enderror" required placeholder="••••••••">
              </div>
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="password_confirmation" class="form-label-clean">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-clean border-start-0" required placeholder="••••••••">
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-white-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2">
            <span>Register &amp; Proceed to Dashboard</span>
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <div class="text-center mt-4 text-muted small">
          Already registered? 
          <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign In</a>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
