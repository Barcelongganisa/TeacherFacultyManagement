@extends('admin.layouts.app')

@section('title', 'Add Professor')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-plus me-2 text-success"></i>Add New Professor</h1>
            <p class="text-muted">Create a new professor account</p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Professor Information</h5>
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

                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf

                    {{-- Name --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Employee ID & Email --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('employee_id') is-invalid @enderror"
                                   name="employee_id"
                                   value="{{ old('employee_id') }}"
                                   required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone & Password --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   name="phone"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   required>
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campus & Department --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Campus <span class="text-danger">*</span></label>
                            <select id="campus_id" name="campus_id"
                                    class="form-select @error('campus_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Campus --</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}"
                                        {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->campus_name }} ({{ $campus->campus_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('campus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror">
                                <option value="">-- Select Campus First --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        data-campus="{{ $department->campus_id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Course --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course</label>
                            <select name="course_id" id="course_id"
                                    class="form-select @error('course_id') is-invalid @enderror">
                                <option value="">-- Select Department First --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        data-department="{{ $course->department_id }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Qualification --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Qualification</label>
                            <input type="text"
                                   class="form-control @error('qualification') is-invalid @enderror"
                                   name="qualification"
                                   value="{{ old('qualification') }}"
                                   placeholder="e.g., PhD, Masters">
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hire Date & Status --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hire Date</label>
                            <input type="date"
                                   class="form-control @error('hire_date') is-invalid @enderror"
                                   name="hire_date"
                                   value="{{ old('hire_date') }}">
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div class="mb-4">
                        <label class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                  name="bio"
                                  rows="4">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Professor
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const campusSelect     = document.getElementById('campus_id');
    const departmentSelect = document.getElementById('department_id');
    const courseSelect     = document.getElementById('course_id');

    const allDeptOptions   = Array.from(departmentSelect.options);
    const allCourseOptions = Array.from(courseSelect.options);

    // Step 1 — Campus changes → filter departments
    campusSelect.addEventListener('change', function () {
        const selectedCampus = this.value;

        // Reset department and course
        departmentSelect.innerHTML = '';
        courseSelect.innerHTML = '';
        departmentSelect.add(new Option('-- Select Department --', ''));
        courseSelect.add(new Option('-- Select Department First --', ''));

        if (!selectedCampus) return;

        allDeptOptions.forEach(option => {
            if (option.dataset.campus === selectedCampus) {
                departmentSelect.add(new Option(option.text, option.value));
            }
        });
    });

    // Step 2 — Department changes → filter courses
    departmentSelect.addEventListener('change', function () {
        const selectedDept = this.value;

        // Reset course
        courseSelect.innerHTML = '';
        courseSelect.add(new Option('-- Select Course --', ''));

        if (!selectedDept) return;

        allCourseOptions.forEach(option => {
            if (option.dataset.department === selectedDept) {
                courseSelect.add(new Option(option.text, option.value));
            }
        });
    });

    // Restore old values on validation error
    const oldCampus = "{{ old('campus_id') }}";
    const oldDept   = "{{ old('department_id') }}";
    const oldCourse = "{{ old('course_id') }}";

    if (oldCampus) {
        campusSelect.value = oldCampus;
        campusSelect.dispatchEvent(new Event('change'));

        setTimeout(() => {
            departmentSelect.value = oldDept;
            departmentSelect.dispatchEvent(new Event('change'));

            setTimeout(() => {
                courseSelect.value = oldCourse;
            }, 50);
        }, 50);
    }
</script>
@endpush