<nav class="navbar navbar-expand-lg white-navbar">
  <div class="container">
    <!-- Brand / Logo -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
      <div class="brand-icon-wrapper">
        <i class="bi bi-building"></i>
      </div>
      <div>
        <span class="brand-text">HostelEase</span>
        <span class="brand-tag">Program 29</span>
      </div>
    </a>

    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav Links & Auth Controls -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
        <li class="nav-item">
          <a class="nav-link white-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link white-nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
            <i class="bi bi-door-open me-1"></i> Rooms
          </a>
        </li>
        @auth
          <li class="nav-item">
            <a class="nav-link white-nav-link {{ request()->routeIs('allocations.*') ? 'active' : '' }}" href="{{ route('allocations.index') }}">
              <i class="bi bi-person-check me-1"></i> Allocations
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link white-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
              <i class="bi bi-mortarboard me-1"></i> Students
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link white-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
              <i class="bi bi-bar-chart-line me-1"></i> Occupancy Report
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link white-nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}">
              <i class="bi bi-tools me-1"></i> Maintenance
            </a>
          </li>
        @endauth
      </ul>

      <!-- User Account Actions -->
      <div class="d-flex align-items-center gap-2">
        @auth
          <div class="dropdown">
            <button class="btn btn-white-outline dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle text-primary"></i>
              <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
              <span class="badge-clean badge-clean-subtle text-uppercase">{{ Auth::user()->role }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle p-2" style="border-radius: var(--radius-md); min-width: 220px;">
              <li class="px-2 py-1 text-muted small border-bottom mb-2">
                Signed in as<br>
                <strong class="text-dark">{{ Auth::user()->email }}</strong>
              </li>
              <li>
                <a class="dropdown-item py-1 px-2 rounded" href="{{ route('dashboard') }}">
                  <i class="bi bi-house me-2 text-muted"></i> Overview
                </a>
              </li>
              @if(Auth::user()->role === 'student' && Auth::user()->student)
                <li>
                  <a class="dropdown-item py-1 px-2 rounded" href="{{ route('students.show', Auth::user()->student) }}">
                    <i class="bi bi-card-text me-2 text-muted"></i> My Student Profile
                  </a>
                </li>
              @endif
              <li><hr class="dropdown-divider my-2"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item py-1 px-2 text-danger rounded">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn btn-white-outline">Sign In</a>
          <a href="{{ route('register') }}" class="btn btn-white-primary">Register</a>
        @endauth
      </div>
    </div>
  </div>
</nav>
