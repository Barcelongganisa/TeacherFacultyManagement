@extends('superadmin.layouts.app')

@section('title', 'Edit Course - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-edit me-2"></i>Edit Course</h1>
            <p class="text-muted">Update details for {{ $course->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.courses.show', $course->id) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            <a href="{{ route('superadmin.courses.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Courses
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-pen me-2"></i>Course Information</h5>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Campus (read-only via department selection) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                College / Department <span class="text-danger">*</span>
                            </label>
                            <select name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Select College --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $course->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Course Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Course Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Bachelor of Science in Information Technology"
                                   value="{{ old('name', $course->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Course Code --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Course Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   placeholder="e.g. BSIT"
                                   value="{{ old('code', $course->code) }}"
                                   style="text-transform: uppercase;">
                            <small class="text-muted">Short unique code for this course (max 20 characters)</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Coordinator --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Coordinator</label>
                            <select name="coordinator_id"
                                    class="form-select @error('coordinator_id') is-invalid @enderror">
                                <option value="">-- Unassigned --</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->id }}"
                                        {{ old('coordinator_id', $course->coordinator_id) == $coordinator->id ? 'selected' : '' }}>
                                        {{ $coordinator->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coordinator_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                <option value="active"   {{ old('status', $course->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Optional description for this course...">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Course
                            </button>
                            <a href="{{ route('superadmin.courses.show', $course->id) }}"
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection