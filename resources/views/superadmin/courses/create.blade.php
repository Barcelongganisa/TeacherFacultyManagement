@extends('superadmin.layouts.app')

@section('title', 'Create Course - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book-open me-2"></i>Create Course</h1>
            <p class="text-muted">Add a new course to a department</p>
        </div>
        <a href="{{ route('superadmin.courses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Courses
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-plus-circle me-2"></i>Course Information</h5>
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

                    <form action="{{ route('superadmin.courses.store') }}" method="POST">
                        @csrf

                        {{-- Campus → Department (dynamic) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Campus <span class="text-danger">*</span>
                                </label>
                                <select id="campus_id" class="form-select">
                                    <option value="">-- Select Campus --</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}"
                                            {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                            {{ $campus->campus_name }} ({{ $campus->campus_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Department <span class="text-danger">*</span>
                                </label>
                                <select name="department_id" id="department_id"
                                        class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">-- Select Campus First --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            data-campus="{{ $department->campus_id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Course Name & Code --}}
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    Course Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g. Bachelor of Science in Computer Science"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Course Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code"
                                       class="form-control @error('code') is-invalid @enderror"
                                       placeholder="e.g. BSCS"
                                       value="{{ old('code') }}"
                                       style="text-transform: uppercase;">
                                <small class="text-muted">Max 20 characters</small>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief description of this course...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Coordinator & Status --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Coordinator</label>
                                <select name="coordinator_id"
                                        class="form-select @error('coordinator_id') is-invalid @enderror">
                                    <option value="">No Coordinator</option>
                                    @foreach($coordinators as $coordinator)
                                        <option value="{{ $coordinator->id }}"
                                            {{ old('coordinator_id') == $coordinator->id ? 'selected' : '' }}>
                                            {{ $coordinator->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Instructor assigned to manage this course</small>
                                @error('coordinator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select name="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                    <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Course
                            </button>
                            <a href="{{ route('superadmin.courses.index') }}" class="btn btn-outline-secondary">
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

@push('scripts')
<script>
    // Filter departments based on selected campus
    const campusSelect     = document.getElementById('campus_id');
    const departmentSelect = document.getElementById('department_id');
    const allOptions       = Array.from(departmentSelect.options);

    campusSelect.addEventListener('change', function () {
        const selectedCampus = this.value;

        // Reset department
        departmentSelect.innerHTML = '';

        if (!selectedCampus) {
            departmentSelect.add(new Option('-- Select Campus First --', ''));
            return;
        }

        departmentSelect.add(new Option('-- Select Department --', ''));

        allOptions.forEach(option => {
            if (option.dataset.campus === selectedCampus) {
                departmentSelect.add(new Option(option.text, option.value));
            }
        });
    });

    // Restore state on validation error (old values)
    const oldCampus = "{{ old('campus_id') }}";
    const oldDept   = "{{ old('department_id') }}";

    if (oldCampus) {
        campusSelect.value = oldCampus;
        campusSelect.dispatchEvent(new Event('change'));
        setTimeout(() => { departmentSelect.value = oldDept; }, 50);
    }
</script>
@endpush