@extends('superadmin.layouts.app')

@section('title', 'Department Management - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-building me-2"></i>Department Management</h1>
            <p class="text-muted">Manage all departments across campuses</p>
        </div>
        <a href="{{ route('superadmin.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Department
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

    <!-- Departments Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Departments List</h5>
                <form method="GET" action="{{ route('superadmin.departments.index') }}" class="d-flex gap-2">
                    <select name="campus_id" class="form-select form-select-sm" style="width: 180px;">
                        <option value="">All Campuses</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}"
                                {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->campus_name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search departments..." value="{{ request('search') }}" style="width: 220px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search') || request('campus_id'))
                        <a href="{{ route('superadmin.departments.index') }}" class="btn btn-sm btn-outline-secondary">
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
                            <th>Department</th>
                            <th>Code</th>
                            <th>Campus</th>
                            <th class="text-center">Courses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td><strong>{{ $department->name }}</strong></td>
                            <td><code>{{ $department->code }}</code></td>
                            <td>
                                <small>
                                    <i class="fas fa-university me-1 text-muted"></i>
                                    {{ $department->campus_name ?? '—' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">
                                    {{ DB::table('courses')->where('department_id', $department->id)->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.departments.edit', $department->id) }}"
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('superadmin.departments.show', $department->id) }}"
                                       class="btn btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-building fa-3x mb-3 d-block"></i>
                                <p>No departments found</p>
                                <a href="{{ route('superadmin.departments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Add Your First Department
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $departments->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection