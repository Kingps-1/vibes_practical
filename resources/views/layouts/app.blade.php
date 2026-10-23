<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Hostel Room Allocation System') - Program 29</title>
  
  <!-- Modern Clean Typography (Plus Jakarta Sans) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Custom Clean White Design System -->
  <link rel="stylesheet" href="{{ asset('css/clean-white.css') }}">

  @stack('styles')
</head>
<body>

  <!-- Navigation Bar -->
  @include('layouts.navbar')

  <!-- Main Content Area -->
  <main class="py-4 flex-grow-1">
    <div class="container">
      
      <!-- Flash Messages -->
      @if(session('success'))
        <div class="alert alert-clean alert-clean-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill text-success fs-5"></i>
          <div class="flex-grow-1">
            <strong class="d-block text-dark">Success</strong>
            <span class="text-secondary">{{ session('success') }}</span>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-clean alert-clean-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
          <div class="flex-grow-1">
            <strong class="d-block text-dark">Notice</strong>
            <span class="text-secondary">{{ session('error') }}</span>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-clean alert-clean-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-x-circle-fill text-danger fs-5"></i>
          <div class="flex-grow-1">
            <strong class="d-block text-dark">Please review form input errors:</strong>
            <ul class="mb-0 ps-3 mt-1 text-secondary">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <!-- Page Yield -->
      @yield('content')
      
    </div>
  </main>

  <!-- Clean Minimal White Footer -->
  <footer class="clean-footer">
    <div class="container">
      <div class="row align-items-center gy-2">
        <div class="col-md-6 text-center text-md-start">
          <span class="fw-semibold text-dark">HostelEase Portal</span> &bull; Program No. 29 Major Project &bull; Database-Driven Web Application
        </div>
        <div class="col-md-6 text-center text-md-end text-muted small">
          Built with <span class="text-danger">&hearts;</span> using Laravel, Blade &amp; AI Assistant &bull; White &amp; Clean UI
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3 Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  @stack('scripts')
</body>
</html>
