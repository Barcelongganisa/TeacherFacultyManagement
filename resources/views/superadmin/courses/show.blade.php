@extends('superadmin.layouts.app')

@section('title', 'Course Details - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book-open me-2"></i>Course Details</h1>
            <p class="text-muted">Viewing details for {{ $course->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.courses.edit', $course->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Course
            </a>
            <a href="{{ route('superadmin.courses.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Courses
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

        {{-- Course Info Card --}}
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Course Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-semibold text-muted" style="width: 40%;">Course Name</td>
                                <td><strong>{{ $course->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Code</td>
                                <td><code>{{ $course->code }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Status</td>
                                <td>
                                    @if($course->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($course->status == 'inactive')
                                        <span class="badge bg-warning">Inactive</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">College</td>
                                <td>{{ $course->department_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Campus</td>
                                <td>
                                    <i class="fas fa-university me-1 text-muted"></i>
                                    {{ $course->campus_name ?? '—' }}
                                    @if($course->campus_code)
                                        <code class="ms-1">{{ $course->campus_code }}</code>
                                    @endif
                                </td>
                            </tr>
                            @if($course->description)
                            <tr>
                                <td class="fw-semibold text-muted">Description</td>
                                <td>{{ $course->description }}</td>
                            </tr>
                            @endif
                            @if($course->created_at)
                            <tr>
                                <td class="fw-semibold text-muted">Created</td>
                                <td>{{ date('M d, Y', strtotime($course->created_at)) }}</td>
                            </tr>
                            @endif
                            @if($course->updated_at)
                            <tr>
                                <td class="fw-semibold text-muted">Last Updated</td>
                                <td>{{ date('M d, Y', strtotime($course->updated_at)) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Coordinator Card --}}
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-user-tie me-2"></i>Coordinator Information</h5>
                </div>
                <div class="card-body">
                    @if($course->coordinator_name)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                 style="width:56px;height:56px;font-size:22px;flex-shrink:0;">
                                {{ strtoupper(substr($course->coordinator_name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $course->coordinator_name }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-envelope me-1"></i>
                                    {{ $course->coordinator_email ?? 'No email on record' }}
                                </small>
                            </div>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-muted" style="width: 40%;">Name</td>
                                    <td>{{ $course->coordinator_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-muted">Email</td>
                                    <td>{{ $course->coordinator_email ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user-slash fa-3x mb-3 d-block"></i>
                            <p>No coordinator assigned to this course.</p>
                            <a href="{{ route('superadmin.courses.edit', $course->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>Assign Coordinator
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection