@extends('superadmin.layouts.app')

@section('title', 'Course Management - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book-open me-2"></i>Course Management</h1>
            <p class="text-muted">Manage all courses across departments</p>
        </div>
        <a href="{{ route('superadmin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Course
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Courses Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Courses List</h5>
                <form method="GET" action="{{ route('superadmin.courses.index') }}" class="d-flex gap-2">
                    <select name="department_id" class="form-select form-select-sm" style="width: 180px;">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select form-select-sm" style="width: 140px;">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search courses..." value="{{ request('search') }}"
                           style="width: 200px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search') || request('department_id') || request('status'))
                        <a href="{{ route('superadmin.courses.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Campus</th>
                            <th>Coordinator</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $course->name }}</strong>
                                @if($course->description)
                                    <br><small class="text-muted">{{ Str::limit($course->description, 40) }}</small>
                                @endif
                            </td>
                            <td><code>{{ $course->code }}</code></td>
                            <td>{{ $course->department_name ?? '—' }}</td>
                            <td>
                                <small>
                                    <i class="fas fa-university me-1 text-muted"></i>
                                    {{ $course->campus_name ?? '—' }}
                                </small>
                            </td>
                            <td>
                                @if($course->coordinator_name)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                             style="width:28px;height:28px;font-size:11px;">
                                            {{ strtoupper(substr($course->coordinator_name, 0, 1)) }}
                                        </div>
                                        <span class="small">{{ $course->coordinator_name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @if($course->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($course->status == 'inactive')
                                    <span class="badge bg-warning">Inactive</span>
                                @else
                                    <span class="badge bg-secondary">Archived</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.courses.edit', $course->id) }}"
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('superadmin.courses.show', $course->id) }}"
                                       class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('superadmin.courses.destroy', $course->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete {{ $course->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-book-open fa-3x mb-3 d-block"></i>
                                <p>No courses found</p>
                                <a href="{{ route('superadmin.courses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Add Your First Course
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $courses->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>
@endsection