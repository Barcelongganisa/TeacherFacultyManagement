@extends('superadmin.layouts.app')

@section('title', 'Department Details - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-building me-2"></i>Department Details</h1>
            <p class="text-muted">Viewing details for {{ $department->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.departments.edit', $department->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Department
            </a>
            <a href="{{ route('superadmin.departments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Departments
            </a>
        </div>
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

    <div class="row">

        {{-- Department Info Card --}}
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Department Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-semibold text-muted" style="width: 40%;">Department Name</td>
                                <td><strong>{{ $department->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Code</td>
                                <td><code>{{ $department->code }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Campus</td>
                                <td>
                                    <i class="fas fa-university me-1 text-muted"></i>
                                    {{ $department->campus_name ?? '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Total Courses</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $courses->count() }}
                                    </span>
                                </td>
                            </tr>
                            @if($department->created_at)
                            <tr>
                                <td class="fw-semibold text-muted">Created</td>
                                <td>{{ date('M d, Y', strtotime($department->created_at)) }}</td>
                            </tr>
                            @endif
                            @if($department->updated_at)
                            <tr>
                                <td class="fw-semibold text-muted">Last Updated</td>
                                <td>{{ date('M d, Y', strtotime($department->updated_at)) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="flex-between">
                        <h5><i class="fas fa-book me-2"></i>Courses Under This Department</h5>
                        {{-- Adjust route name to match your courses create route --}}
                        @if(Route::has('superadmin.courses.create'))
                            <a href="{{ route('superadmin.courses.create') }}?department_id={{ $department->id }}"
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Course
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($courses->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-book-open fa-3x mb-3 d-block"></i>
                            <p>No courses found for this department.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Course Name</th>
                                        <th>Code</th>
                                        @if(isset($courses->first()->actions))
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                    <tr>
                                        <td class="text-muted small">{{ $loop->iteration }}</td>
                                        <td><strong>{{ $course->name }}</strong></td>
                                        <td><code>{{ $course->code }}</code></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection