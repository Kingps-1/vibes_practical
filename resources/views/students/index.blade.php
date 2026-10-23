@extends('layouts.app')

@section('title', 'Students Directory - Hostel Residents')

@section('content')
<!-- Header -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div>
    <h1 class="h3 fw-bold text-dark mb-1">Students Directory</h1>
    <p class="text-muted mb-0">Registered hostel students, contact directory, and current room status</p>
  </div>
  @auth
    <a href="{{ route('students.create') }}" class="btn btn-white-primary d-flex align-items-center gap-2">
      <i class="bi bi-person-plus"></i>
      <span>Add New Student</span>
    </a>
  @endauth
</div>

<!-- Search & Course Filter -->
<div class="white-card mb-4">
  <div class="white-card-body p-3 p-md-4">
    <form action="{{ route('students.index') }}" method="GET">
      <div class="row g-2 align-items-end">
        <div class="col-md-7">
          <label for="search" class="form-label-clean">Search Students</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" id="search" class="form-control form-control-clean border-start-0" placeholder="Student name, roll number, email, course..." value="{{ request('search') }}">
          </div>
        </div>

        <div class="col-md-3">
          <label for="course" class="form-label-clean">Course</label>
          <select name="course" id="course" class="form-select form-select-clean">
            <option value="">All Academic Courses</option>
            @foreach($courses as $c)
              <option value="{{ $c }}" {{ request('course') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-white-primary flex-grow-1">
            <i class="bi bi-funnel me-1"></i> Search
          </button>
          @if(request()->hasAny(['search', 'course']))
            <a href="{{ route('students.index') }}" class="btn btn-white-outline" title="Reset Filters">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Students Table -->
<div class="white-table-container">
  <div class="table-responsive">
    <table class="white-table">
      <thead>
        <tr>
          <th>Enrollment ID</th>
          <th>Student Name</th>
          <th>Course &amp; Semester</th>
          <th>Contact Info</th>
          <th>Guardian Info</th>
          <th>Current Room</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $s)
          <tr>
            <td>
              <span class="font-monospace fw-bold text-dark small">{{ $s->student_id_number }}</span>
            </td>
            <td>
              <a href="{{ route('students.show', $s) }}" class="fw-bold text-dark text-decoration-none">
                {{ $s->full_name }}
              </a>
              <div class="text-muted small">{{ $s->gender }} &bull; Blood: {{ $s->blood_group ?? 'N/A' }}</div>
            </td>
            <td>
              <span class="text-dark">{{ $s->course }}</span>
              <div class="text-muted small">Semester {{ $s->semester }}</div>
            </td>
            <td class="small">
              <div><i class="bi bi-telephone text-muted me-1"></i> {{ $s->phone }}</div>
              <div class="text-muted"><i class="bi bi-envelope text-muted me-1"></i> {{ $s->email }}</div>
            </td>
            <td class="small">
              <div class="text-dark fw-semibold">{{ $s->guardian_name }}</div>
              <div class="text-muted">{{ $s->guardian_phone }}</div>
            </td>
            <td>
              @if($s->activeAllocation)
                <a href="{{ route('rooms.show', $s->activeAllocation->room) }}" class="badge-clean badge-clean-success text-decoration-none">
                  <i class="bi bi-door-open"></i> Room {{ $s->activeAllocation->room->room_number }} ({{ $s->activeAllocation->bed_number }})
                </a>
              @else
                <span class="badge-clean badge-clean-subtle text-muted">
                  Not Allotted
                </span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-1">
                <a href="{{ route('students.show', $s) }}" class="btn btn-white-outline btn-sm py-1 px-2" title="View Profile">
                  <i class="bi bi-person"></i>
                </a>
                <a href="{{ route('students.edit', $s) }}" class="btn btn-white-subtle btn-sm py-1 px-2" title="Edit Student">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('students.destroy', $s) }}" method="POST" onsubmit="return confirm('Delete student profile for {{ $s->full_name }}?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="Delete Student">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
              No students found matching your search.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination -->
<div class="mt-4 d-flex justify-content-center">
  {{ $students->links('pagination::bootstrap-5') }}
</div>
@endsection
