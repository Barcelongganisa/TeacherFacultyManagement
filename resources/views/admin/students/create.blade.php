@extends('admin.layouts.app')

@section('title', 'Add Student')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-plus me-2 text-success"></i>Add New Student</h1>
            <p class="text-muted">Create a new student account</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Student Information</h5>
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

                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   placeholder="Enter first name"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   placeholder="Enter last name"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Campus & Department --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
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

                        <div class="col-md-6">
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

                    {{-- Course & Year Level --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
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

                        <div class="col-md-6">
                            <label class="form-label">Year Level <span class="text-danger">*</span></label>
                            <select name="year_level"
                                    class="form-select @error('year_level') is-invalid @enderror"
                                    required>
                                <option value="">-- Select Year Level --</option>
                                <option value="1st" {{ old('year_level') == '1st' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd" {{ old('year_level') == '2nd' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd" {{ old('year_level') == '3rd' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th" {{ old('year_level') == '4th' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('year_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Minimum 8 characters"
                               required>
                        <small class="text-muted">Minimum 8 characters</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
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

        courseSelect.innerHTML = '';
        courseSelect.add(new Option('-- Select Course --', ''));

        if (!selectedDept) return;

        allCourseOptions.forEach(option => {
            if (option.dataset.department === selectedDept) {
                courseSelect.add(new Option(option.text, option.value));
            }
        });
    });

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