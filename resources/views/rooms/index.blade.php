@extends('layouts.app')

@section('title', 'Hostel Rooms Catalog & Availability')

@section('content')
<!-- Header & Add Button -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Hostel Rooms Catalog</h1>
    <p class="text-muted mb-0">Browse rooms, check bed vacancies, and manage specifications</p>
  </div>
  @auth
    <a href="{{ route('rooms.create') }}" class="btn btn-white-primary d-flex align-items-center gap-2">
      <i class="bi bi-plus-lg"></i>
      <span>Add New Room</span>
    </a>
  @endauth
</div>

<!-- Search & Multi-Filter Card -->
<div class="white-card mb-4">
  <div class="white-card-body p-3 p-md-4">
    <form action="{{ route('rooms.index') }}" method="GET">
      <div class="row g-2 align-items-end">
        
        <!-- Keyword Search -->
        <div class="col-md-4">
          <label for="search" class="form-label-clean">Search Rooms</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" id="search" class="form-control form-control-clean border-start-0" placeholder="Room no, block, AC, Wi-Fi..." value="{{ request('search') }}">
          </div>
        </div>

        <!-- Block Filter -->
        <div class="col-sm-6 col-md-2">
          <label for="block" class="form-label-clean">Block</label>
          <select name="block" id="block" class="form-select form-select-clean">
            <option value="">All Blocks</option>
            @foreach($blocks as $b)
              <option value="{{ $b }}" {{ request('block') == $b ? 'selected' : '' }}>{{ $b }}</option>
            @endforeach
          </select>
        </div>

        <!-- Room Type Filter -->
        <div class="col-sm-6 col-md-2">
          <label for="room_type" class="form-label-clean">Room Type</label>
          <select name="room_type" id="room_type" class="form-select form-select-clean">
            <option value="">All Types</option>
            @foreach($roomTypes as $t)
              <option value="{{ $t }}" {{ request('room_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <!-- Status Filter -->
        <div class="col-sm-6 col-md-2">
          <label for="status" class="form-label-clean">Status</label>
          <select name="status" id="status" class="form-select form-select-clean">
            <option value="">All Statuses</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="full" {{ request('status') == 'full' ? 'selected' : '' }}>Full</option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
          </select>
        </div>

        <!-- Action Buttons -->
        <div class="col-sm-6 col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-white-primary flex-grow-1">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
          @if(request()->hasAny(['search', 'block', 'room_type', 'status', 'floor']))
            <a href="{{ route('rooms.index') }}" class="btn btn-white-outline" title="Reset Filters">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Rooms Grid Listing -->
<div class="row g-4">
  @forelse($rooms as $room)
    <div class="col-md-6 col-lg-4">
      <div class="white-card white-card-hover h-100 d-flex flex-direction-column justify-content-between">
        
        <div>
          <!-- Card Header Info -->
          <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
              <span class="h5 fw-bold text-dark mb-0">{{ $room->room_number }}</span>
              <span class="text-muted small ms-2">Floor {{ $room->floor }}</span>
            </div>
            <div>
              @if($room->status === 'available')
                <span class="badge-clean badge-clean-success">
                  <i class="bi bi-check2"></i> Available
                </span>
              @elseif($room->status === 'full')
                <span class="badge-clean badge-clean-danger">
                  <i class="bi bi-lock-fill"></i> Full
                </span>
              @else
                <span class="badge-clean badge-clean-warning">
                  <i class="bi bi-wrench"></i> In Maintenance
                </span>
              @endif
            </div>
          </div>

          <!-- Card Body -->
          <div class="p-3">
            <div class="mb-2">
              <div class="text-dark fw-semibold">{{ $room->block }}</div>
              <div class="text-muted small">{{ $room->room_type }}</div>
            </div>

            <!-- Occupancy Progress Indicator -->
            <div class="my-3 p-2 bg-light rounded-2 border">
              <div class="d-flex justify-content-between align-items-center text-muted small mb-1">
                <span>Occupancy</span>
                <span class="fw-semibold text-dark">{{ $room->current_occupancy }} / {{ $room->capacity }} Beds</span>
              </div>
              @php
                $pct = $room->capacity > 0 ? ($room->current_occupancy / $room->capacity) * 100 : 0;
              @endphp
              <div class="progress" style="height: 6px;">
                <div class="progress-bar {{ $pct >= 100 ? 'bg-danger' : ($pct > 0 ? 'bg-primary' : 'bg-success') }}" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <!-- Amenities Badges -->
            @if($room->amenities)
              <div class="d-flex flex-wrap gap-1 mb-2">
                @foreach(explode(',', $room->amenities) as $amenity)
                  <span class="badge-clean badge-clean-subtle small py-0 px-2" style="font-size: 0.72rem;">
                    {{ trim($amenity) }}
                  </span>
                @endforeach
              </div>
            @endif

            <div class="d-flex align-items-baseline justify-content-between mt-3 pt-2 border-top">
              <span class="text-muted small">Monthly Fee:</span>
              <span class="h5 fw-bold text-dark mb-0">₹{{ number_format($room->monthly_fee) }}<span class="text-muted small fw-normal">/mo</span></span>
            </div>
          </div>
        </div>

        <!-- Card Footer Actions -->
        <div class="p-3 border-top bg-light-subtle d-flex justify-content-between align-items-center">
          <a href="{{ route('rooms.show', $room) }}" class="btn btn-white-outline btn-sm">
            <i class="bi bi-eye me-1"></i> Details
          </a>

          @auth
            <div class="d-flex gap-1">
              @if($room->status === 'available')
                <a href="{{ route('allocations.create', ['room_id' => $room->id]) }}" class="btn btn-white-primary btn-sm">
                  <i class="bi bi-plus-circle me-1"></i> Allocate
                </a>
              @endif
              <a href="{{ route('rooms.edit', $room) }}" class="btn btn-white-subtle btn-sm" title="Edit Room">
                <i class="bi bi-pencil"></i>
              </a>
            </div>
          @endauth
        </div>

      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="white-card text-center p-5">
        <i class="bi bi-search text-muted fs-1 mb-3 d-block"></i>
        <h3 class="h5 fw-bold text-dark">No rooms match your search criteria</h3>
        <p class="text-muted small mb-3">Try adjusting your filters or search keywords.</p>
        <a href="{{ route('rooms.index') }}" class="btn btn-white-outline btn-sm">Clear All Filters</a>
      </div>
    </div>
  @endforelse
</div>

<!-- Pagination Links -->
<div class="mt-4 d-flex justify-content-center">
  {{ $rooms->links('pagination::bootstrap-5') }}
</div>
@endsection
