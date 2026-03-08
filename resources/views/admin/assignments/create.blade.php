@extends('admin.layouts.app')

@section('title', 'Create Assignment')

@section('page-header')
<div class="flex-between">
    <div>
        <h1><i class="fas fa-tasks-plus me-2 text-success"></i>Create Assignment</h1>
        <p class="text-muted">Assign a subject to a professor</p>
    </div>
    <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assignment Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.assignments.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="teacher_id" class="form-label">Select Professor <span class="text-danger">*</span></label>
                        <select class="form-select @error('teacher_id') is-invalid @enderror"
                            id="teacher_id"
                            name="teacher_id"
                            required>
                            <option value="">Choose a professor...</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->name }} 
                            </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="subject_id" class="form-label">Select Subject <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror"
                            id="subject_id"
                            name="subject_id"
                            required>
                            <option value="">Choose a subject...</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->subject_name }} ({{ $subject->subject_code }}) - {{ $subject->credits }} credits
                            </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <select class="form-select @error('academic_year') is-invalid @enderror"
                                id="academic_year"
                                name="academic_year">
                                <option value="">Select Year</option>
                                @for($year = date('Y'); $year >= date('Y')-4; $year--)
                                <option value="{{ $year }}" {{ old('academic_year', date('Y')) == $year ? 'selected' : '' }}>
                                    {{ $year }}-{{ $year+1 }}
                                </option>
                                @endfor
                            </select>
                            @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror"
                                id="semester"
                                name="semester">
                                <option value="">Select Semester</option>
                                <option value="1" {{ old('semester', '1') == '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                                <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>Semester 3</option>
                                <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">About Assignments</h6>
                                <p class="mb-0 small">This assigns a subject to a professor. The professor will be able to view this subject in their portal and create schedules for it.</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Create Assignment
                        </button>
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Cards -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-success"></i>Selected Professor Preview</h6>
            </div>
            <div class="card-body" id="teacherPreview">
                <div class="text-center text-muted py-3">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                    <p>Select a professor to see details</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-success"></i>Selected Subject Preview</h6>
            </div>
            <div class="card-body" id="subjectPreview">
                <div class="text-center text-muted py-3">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <p>Select a subject to see details</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Teacher preview
        $('#teacher_id').change(function() {
            const teacherId = $(this).val();
            if (teacherId) {
                $.ajax({
                    url: '{{ route("admin.teachers.show", ":id") }}'.replace(':id', teacherId),
                    method: 'GET',
                    success: function(response) {
                        // This is simplified - you'd need to create an API endpoint for this
                        $('#teacherPreview').html(`
                        <div class="text-center">
                            <div class="avatar-circle-large bg-success text-white mx-auto mb-3">
                                ${response.first_name?.charAt(0) || ''}${response.last_name?.charAt(0) || ''}
                            </div>
                            <h6>${response.first_name} ${response.last_name}</h6>
                            <p class="text-muted mb-1">${response.email}</p>
                            <p class="text-muted small">${response.department || 'No Department'}</p>
                        </div>
                    `);
                    },
                    error: function() {
                        $('#teacherPreview').html(`
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <p>Could not load teacher details</p>
                        </div>
                    `);
                    }
                });
            } else {
                $('#teacherPreview').html(`
                <div class="text-center text-muted py-3">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                    <p>Select a professor to see details</p>
                </div>
            `);
            }
        });

        // Subject preview
        $('#subject_id').change(function() {
            const subjectId = $(this).val();
            if (subjectId) {
                $.ajax({
                    url: '{{ route("admin.subjects.show", ":id") }}'.replace(':id', subjectId),
                    method: 'GET',
                    success: function(response) {
                        $('#subjectPreview').html(`
                        <div>
                            <h6>${response.subject_name}</h6>
                            <p class="text-muted mb-2"><strong>Code:</strong> ${response.subject_code}</p>
                            <p class="text-muted mb-2"><strong>Credits:</strong> ${response.credits}</p>
                            <p class="text-muted mb-2"><strong>Department:</strong> ${response.department || 'N/A'}</p>
                            <p class="text-muted small mb-0"><strong>Description:</strong> ${response.description || 'No description'}</p>
                        </div>
                    `);
                    },
                    error: function() {
                        $('#subjectPreview').html(`
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <p>Could not load subject details</p>
                        </div>
                    `);
                    }
                });
            } else {
                $('#subjectPreview').html(`
                <div class="text-center text-muted py-3">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <p>Select a subject to see details</p>
                </div>
            `);
            }
        });
    });

    <
    style >
        .avatar - circle - large {
            width: 80 px;
            height: 80 px;
            border - radius: 50 % ;
            display: flex;
            align - items: center;
            justify - content: center;
            font - weight: 600;
            font - size: 2 rem;
            margin: 0 auto;
        } <
        /style>
</script>
@endsection