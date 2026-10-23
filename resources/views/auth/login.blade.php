@extends('layouts.app')

@section('title', 'Sign In - Hostel Management Portal')

@section('content')
<div class="row justify-content-center py-4">
  <div class="col-md-6 col-lg-5">
    
    <div class="white-card">
      <div class="white-card-body p-4 p-md-5">
        
        <!-- Header -->
        <div class="text-center mb-4">
          <div class="brand-icon-wrapper mb-3" style="width: 48px; height: 48px; font-size: 1.4rem;">
            <i class="bi bi-shield-lock"></i>
          </div>
          <h2 class="h4 fw-bold text-dark mb-1">Welcome Back</h2>
          <p class="text-muted small mb-0">Sign in to manage rooms, allocations &amp; records</p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label-clean">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" id="email" class="form-control form-control-clean border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', 'admin@hostel.edu') }}" required autofocus placeholder="name@hostel.edu">
            </div>
            @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="password" class="form-label-clean mb-0">Password</label>
            </div>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
              <input type="password" name="password" id="password" class="form-control form-control-clean border-start-0 border-end-0 @error('password') is-invalid @enderror" value="password" required placeholder="••••••••">
              <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePasswordBtn">
                <i class="bi bi-eye" id="toggleEyeIcon"></i>
              </button>
            </div>
            @error('password')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
              <label class="form-check-label text-muted small" for="remember">
                Keep me signed in
              </label>
            </div>
          </div>

          <button type="submit" class="btn btn-white-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2">
            <span>Sign In to Portal</span>
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <!-- Demo Accounts Hint Pill -->
        <div class="mt-4 p-3 bg-light rounded-3 border">
          <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-semibold small">
            <i class="bi bi-info-circle text-primary"></i>
            <span>Demo Credentials (Password: <code>password</code>):</span>
          </div>
          <div class="d-flex flex-wrap gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary bg-white py-0 px-2 small" onclick="fillCreds('admin@hostel.edu')">Admin (Dr. Sharma)</button>
            <button type="button" class="btn btn-sm btn-outline-secondary bg-white py-0 px-2 small" onclick="fillCreds('warden@hostel.edu')">Warden (Prof. Sunita)</button>
            <button type="button" class="btn btn-sm btn-outline-secondary bg-white py-0 px-2 small" onclick="fillCreds('student@hostel.edu')">Student (Aarav)</button>
          </div>
        </div>

        <div class="text-center mt-4 text-muted small">
          Don't have an account yet? 
          <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Create Account</a>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  function fillCreds(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
  }

  const toggleBtn = document.getElementById('togglePasswordBtn');
  const pwdInput = document.getElementById('password');
  const eyeIcon = document.getElementById('toggleEyeIcon');

  if (toggleBtn && pwdInput) {
    toggleBtn.addEventListener('click', () => {
      const isPwd = pwdInput.type === 'password';
      pwdInput.type = isPwd ? 'text' : 'password';
      eyeIcon.className = isPwd ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  }
</script>
@endpush
