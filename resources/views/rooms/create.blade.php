@extends('layouts.app')

@section('title', 'Add New Hostel Room')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    
    <!-- Breadcrumb & Nav -->
    <div class="mb-3">
      <a href="{{ route('rooms.index') }}" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Rooms Catalog
      </a>
    </div>

    <div class="white-card">
      <div class="white-card-header">
        <div>
          <h1 class="h5 fw-bold text-dark mb-0">Add New Hostel Room</h1>
          <span class="text-muted small">Enter room specifications, block location, and capacity</span>
        </div>
      </div>

      <div class="white-card-body p-4">
        <form action="{{ route('rooms.store') }}" method="POST">
          @csrf

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="room_number" class="form-label-clean">Room Number / Identifier <span class="text-danger">*</span></label>
              <input type="text" name="room_number" id="room_number" class="form-control form-control-clean @error('room_number') is-invalid @enderror" value="{{ old('room_number') }}" required placeholder="e.g. A-103 or B-204">
              <div class="form-text small text-muted">Must be unique across all hostel blocks.</div>
              @error('room_number')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="block" class="form-label-clean">Hostel Block <span class="text-danger">*</span></label>
              <select name="block" id="block" class="form-select form-select-clean @error('block') is-invalid @enderror" required>
                <option value="Block A (Boys)" {{ old('block') === 'Block A (Boys)' ? 'selected' : '' }}>Block A (Boys Wing)</option>
                <option value="Block B (Girls)" {{ old('block') === 'Block B (Girls)' ? 'selected' : '' }}>Block B (Girls Wing)</option>
                <option value="Block C (Scholars)" {{ old('block') === 'Block C (Scholars)' ? 'selected' : '' }}>Block C (Postgraduate &amp; Scholars Wing)</option>
              </select>
              @error('block')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="room_type" class="form-label-clean">Room Configuration <span class="text-danger">*</span></label>
              <select name="room_type" id="room_type" class="form-select form-select-clean @error('room_type') is-invalid @enderror" required>
                <option value="Single Deluxe" {{ old('room_type') === 'Single Deluxe' ? 'selected' : '' }}>Single Deluxe</option>
                <option value="Double Sharing" {{ old('room_type', 'Double Sharing') === 'Double Sharing' ? 'selected' : '' }}>Double Sharing</option>
                <option value="Triple Sharing" {{ old('room_type') === 'Triple Sharing' ? 'selected' : '' }}>Triple Sharing</option>
                <option value="Four Sharing" {{ old('room_type') === 'Four Sharing' ? 'selected' : '' }}>Four Sharing</option>
              </select>
              @error('room_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="capacity" class="form-label-clean">Bed Capacity (Beds) <span class="text-danger">*</span></label>
              <input type="number" name="capacity" id="capacity" class="form-control form-control-clean @error('capacity') is-invalid @enderror" value="{{ old('capacity', 2) }}" min="1" max="6" required>
              @error('capacity')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label for="floor" class="form-label-clean">Floor Number <span class="text-danger">*</span></label>
              <input type="number" name="floor" id="floor" class="form-control form-control-clean @error('floor') is-invalid @enderror" value="{{ old('floor', 1) }}" min="0" max="20" required>
              @error('floor')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="monthly_fee" class="form-label-clean">Monthly Fee (₹) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted">₹</span>
                <input type="number" step="0.01" name="monthly_fee" id="monthly_fee" class="form-control form-control-clean border-start-0 @error('monthly_fee') is-invalid @enderror" value="{{ old('monthly_fee', 5000) }}" min="0" required placeholder="5000.00">
              </div>
              @error('monthly_fee')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label-clean">Initial Status <span class="text-danger">*</span></label>
              <select name="status" id="status" class="form-select form-select-clean @error('status') is-invalid @enderror" required>
                <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Available for Allotment</option>
                <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                <option value="full" {{ old('status') === 'full' ? 'selected' : '' }}>Fully Occupied</option>
              </select>
              @error('status')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="amenities" class="form-label-clean">Room Amenities &amp; Features</label>
            <input type="text" name="amenities" id="amenities" class="form-control form-control-clean @error('amenities') is-invalid @enderror" value="{{ old('amenities', 'Wi-Fi, Attached Washroom, Study Table, Wardrobe') }}" placeholder="Comma separated: Wi-Fi, AC, Attached Bath, Balcony">
            <div class="form-text small text-muted">Comma-separated list of amenities to display as tags.</div>
          </div>

          <div class="mb-4">
            <label for="description" class="form-label-clean">Additional Notes / Description</label>
            <textarea name="description" id="description" rows="3" class="form-control form-control-clean @error('description') is-invalid @enderror" placeholder="Optional notes regarding room orientation, sunlight, furniture details...">{{ old('description') }}</textarea>
          </div>

          <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('rooms.index') }}" class="btn btn-white-outline">Cancel</a>
            <button type="submit" class="btn btn-white-primary">
              <i class="bi bi-check-lg me-1"></i> Save &amp; Create Room
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection
