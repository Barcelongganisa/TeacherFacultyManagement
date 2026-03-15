@extends('admin.layouts.app')

@section('title', 'Create Assignment')

@section('page-header')
<div class="flex-between">
    <div>
        <h1><i class="fas fa-chalkboard-teacher me-2 text-success"></i>Create Assignment</h1>
        <p class="text-muted mb-0">Assign a subject to a professor for a specific term</p>
    </div>
    <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>
@endsection

@section('content')

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-center mb-3" role="alert">
    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex gap-2 align-items-center mb-3" role="alert">
    <i class="fas fa-check-circle flex-shrink-0"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<style>
    .form-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; }
    .form-card .card-header { background: linear-gradient(135deg, #198754 0%, #0f5132 100%); color: white; padding: 1.1rem 1.5rem; border-bottom: none; }
    .form-card .card-header h5 { font-weight: 600; letter-spacing: 0.3px; }
    .form-card .card-body { padding: 2rem; }
    .form-label { font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: #495057; margin-bottom: 0.5rem; }
    .form-select, .form-control { border-radius: 8px; border: 1.5px solid #dee2e6; padding: 0.6rem 0.9rem; font-size: 0.95rem; transition: border-color 0.2s, box-shadow 0.2s; }
    .form-select:focus, .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,0.15); }
    .form-select:disabled { background-color: #f8f9fa; cursor: not-allowed; opacity: 0.7; }
    .section-divider { border: none; border-top: 1.5px dashed #dee2e6; margin: 1.5rem 0; }
    .section-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #198754; margin-bottom: 1rem; }
    .duration-badge { background: #d1fae5; color: #065f46; border-radius: 8px; padding: 0.55rem 1rem; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem; border: 1.5px solid #a7f3d0; }
    .time-preview-box { background: #f0fdf4; border: 1.5px solid #a7f3d0; border-radius: 10px; padding: 0.75rem 1.1rem; font-size: 0.88rem; color: #065f46; display: none; }
    .time-preview-box.show { display: block; }
    .preview-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); transition: box-shadow 0.2s; }
    .preview-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.11); }
    .preview-card .card-header { background: #f8f9fa; border-bottom: 1.5px solid #e9ecef; border-radius: 12px 12px 0 0 !important; padding: 0.85rem 1.25rem; }
    .avatar-circle-large { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.6rem; margin: 0 auto; background: linear-gradient(135deg, #198754, #20c997); color: white; box-shadow: 0 4px 12px rgba(25,135,84,0.3); }
    .preview-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.45rem 0; border-bottom: 1px solid #f1f3f5; font-size: 0.9rem; }
    .preview-detail-row:last-child { border-bottom: none; }
    .preview-detail-label { color: #6c757d; font-weight: 500; font-size: 0.82rem; }
    .preview-detail-value { font-weight: 600; color: #212529; text-align: right; }
    .btn-success { background: linear-gradient(135deg, #198754 0%, #157347 100%); border: none; border-radius: 8px; padding: 0.6rem 1.5rem; font-weight: 600; letter-spacing: 0.3px; box-shadow: 0 2px 8px rgba(25,135,84,0.3); transition: transform 0.15s, box-shadow 0.15s; }
    .btn-success:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(25,135,84,0.4); }
    .alert-info { background: #e7f3ff; border: 1.5px solid #b6d9fb; border-radius: 10px; color: #0c63e4; }
    option:disabled { color: #adb5bd; background-color: #f8f9fa; }
</style>

<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card form-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Assignment Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.assignments.store') }}" method="POST">
                    @csrf

                    <p class="section-label"><i class="fas fa-users me-1"></i> People & Course</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="teacher_id" class="form-label">
                                Professor <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror"
                                id="teacher_id" name="teacher_id" required>
                                <option value="">Choose a professor...</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="subject_id" class="form-label">
                                Subject <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('subject_id') is-invalid @enderror"
    id="subject_id" name="subject_id" required disabled>
    <option value="">Select a professor first...</option>
</select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="section-divider">

                    <p class="section-label"><i class="fas fa-calendar-alt me-1"></i> Schedule Details</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <select class="form-select @error('academic_year') is-invalid @enderror"
                                id="academic_year" name="academic_year">
                                <option value="">Select Year</option>
                                @for($year = date('Y') - 1; $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}"
                                        {{ old('academic_year', date('Y') - 1) == $year ? 'selected' : '' }}>
                                        {{ $year }}-{{ $year + 1 }}
                                    </option>
                                @endfor
                            </select>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror"
                                id="semester" name="semester">
                                <option value="">Select Semester</option>
                                <option value="1" {{ old('semester','2') == '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ old('semester','2') == '2' ? 'selected' : '' }}>Semester 2</option>
                                <option value="3" {{ old('semester','2') == '3' ? 'selected' : '' }}>Semester 3</option>
                                <option value="Summer" {{ old('semester','2') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>

                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="section" class="form-label">Section</label>
                            <select class="form-select @error('section') is-invalid @enderror"
                                id="section" name="section">
                                <option value="">Select Section</option>
                                <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A</option>
                                <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B</option>
                                <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>Section C</option>
                            </select>
                            @error('section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="section-divider">

                    <p class="section-label"><i class="fas fa-door-open me-1"></i> Classroom & Time</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="classroom_id" class="form-label">Classroom</label>
                            <select class="form-select @error('classroom_id') is-invalid @enderror"
                                id="classroom_id" name="classroom_id">
                                <option value="">Select Classroom</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}"
                                        {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                        {{ $classroom->room_number }}
                                        @if($classroom->building ?? false) - {{ $classroom->building }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="day_of_week" class="form-label">Day of Week</label>
                            <select class="form-select @error('day_of_week') is-invalid @enderror"
                                id="day_of_week" name="day_of_week">
                                <option value="">Select Day</option>
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                    <option value="{{ $day }}"
                                        {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-2 align-items-end">
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">
                                Start Time <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('start_time') is-invalid @enderror"
                                id="start_time" name="start_time" required>
                                <option value="">Select Start Time</option>
                                @for($h = 7; $h <= 21; $h++)
                                    @php $t = sprintf('%02d:00', $h); @endphp
                                    <option value="{{ $t }}" {{ old('start_time') == $t ? 'selected' : '' }}>
                                        {{ date('h:i A', strtotime($t)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="end_time" class="form-label">
                                End Time <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time"
                                style="background-color:#f8f9fa;color:#adb5bd;">
                                <option value="">Select Start Time First</option>
                            </select>
                            <small class="text-muted" id="end_time_hint">Select a start time first</small>
                            @error('end_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 d-flex align-items-end pb-1">
                            <span id="durationBadge" class="duration-badge d-none">
                                <i class="fas fa-clock"></i>
                                <span id="durationText"></span>
                            </span>
                        </div>
                    </div>

                    <div id="timePreviewBox" class="time-preview-box mb-3">
                        <i class="fas fa-calendar-check me-2"></i>
                        <strong>Schedule:</strong>
                        <span id="timePreviewText"></span>
                    </div>

                    <hr class="section-divider">

                    <div class="alert alert-info d-flex gap-3 align-items-start mb-4">
                        <i class="fas fa-info-circle fa-lg mt-1 flex-shrink-0"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">About Assignments</h6>
                            <p class="mb-0 small">
                                Assigns a subject to a professor for the selected term, section, and schedule.
                                Already-assigned subjects are hidden. Time slots already taken by this professor
                                on the selected day are marked as <strong>Taken</strong> and disabled.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Create Assignment
                        </button>
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary rounded-3">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card preview-card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-success"></i>
                <h6 class="mb-0 fw-semibold">Professor Preview</h6>
            </div>
            <div class="card-body" id="teacherPreview">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-2 opacity-25"></i>
                    <p class="mb-0 small">Select a professor to see details</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card preview-card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-book text-success"></i>
                <h6 class="mb-0 fw-semibold">Subject Preview</h6>
            </div>
            <div class="card-body" id="subjectPreview">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-book fa-2x mb-2 opacity-25"></i>
                    <p class="mb-0 small">Select a subject to see details</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ── TAKEN SLOTS STATE ─────────────────────────────────────────────────────────
var takenSlots = []; // [{ start: 'HH:00', end: 'HH:00' }, ...]

function isHourTaken(h) {
    var hStr = (h < 10 ? '0' : '') + h + ':00';
    return takenSlots.some(function (slot) {
        return hStr >= slot.start && hStr < slot.end;
    });
}

function fetchTakenSlots() {
    var teacherId = document.getElementById('teacher_id').value;
    var day       = document.getElementById('day_of_week').value;

    takenSlots = [];
    renderStartTimeOptions();

    if (!teacherId || !day) return;

    $.ajax({
        url: '{{ route("admin.assignments.takenSlots") }}',
        method: 'GET',
        data: { teacher_id: teacherId, day: day },
        success: function (data) {
            takenSlots = data;
            renderStartTimeOptions();
        }
    });
}

function renderStartTimeOptions() {
    var startSelect = document.getElementById('start_time');
    var currentVal  = startSelect.value;

    startSelect.innerHTML = '<option value="">Select Start Time</option>';

    for (var h = 7; h <= 21; h++) {
        var opt  = document.createElement('option');
        var hStr = (h < 10 ? '0' : '') + h + ':00';
        var d    = new Date(2000, 0, 1, h, 0);
        var lbl  = d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

        opt.value = hStr;

        if (isHourTaken(h)) {
            opt.disabled    = true;
            opt.textContent = lbl + ' (Taken)';
            opt.style.color = '#adb5bd';
        } else {
            opt.textContent = lbl;
        }

        startSelect.appendChild(opt);
    }

    // Restore selection if still valid
    if (currentVal) {
        startSelect.value = currentVal;
        var selected = startSelect.querySelector('option[value="' + currentVal + '"]');
        if (selected && selected.disabled) {
            startSelect.value = '';
        }
    }

    startSelect.dispatchEvent(new Event('change'));
}

// ── START TIME -> END TIME ────────────────────────────────────────────────────
document.getElementById('start_time').addEventListener('change', function () {
    var startVal  = this.value;
    var endSelect = document.getElementById('end_time');
    var hint      = document.getElementById('end_time_hint');

    endSelect.innerHTML = '';

    if (!startVal) {
        endSelect.innerHTML = '<option value="">Select Start Time First</option>';
        endSelect.style.backgroundColor = '#f8f9fa';
        endSelect.style.color = '#adb5bd';
        if (hint) hint.style.display = '';
        document.getElementById('durationBadge').classList.add('d-none');
        document.getElementById('timePreviewBox').classList.remove('show');
        return;
    }

    var startH = parseInt(startVal.split(':')[0], 10);

    var placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Select End Time';
    endSelect.appendChild(placeholder);

    for (var h = startH + 1; h <= 21; h++) {
        var opt  = document.createElement('option');
        var hStr = (h < 10 ? '0' : '') + h + ':00';
        var d    = new Date(2000, 0, 1, h, 0);
        opt.value       = hStr;
        opt.textContent = d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        endSelect.appendChild(opt);
    }

    endSelect.style.backgroundColor = '';
    endSelect.style.color = '';
    if (hint) hint.style.display = 'none';
});

document.getElementById('end_time').addEventListener('change', updateTimePreview);

document.getElementById('day_of_week').addEventListener('change', function () {
    fetchTakenSlots();
    updateTimePreview();
});
document.getElementById('semester').addEventListener('change', filterSubjectsByTeacher);
document.getElementById('academic_year').addEventListener('change', filterSubjectsByTeacher);

function fmtHour(h) {
    return new Date(2000, 0, 1, h, 0).toLocaleTimeString('en-US', {
        hour: 'numeric', minute: '2-digit', hour12: true
    });
}

function updateTimePreview() {
    var startVal = document.getElementById('start_time').value;
    var endVal   = document.getElementById('end_time').value;
    var day      = document.getElementById('day_of_week').value;

    if (!startVal || !endVal) {
        document.getElementById('durationBadge').classList.add('d-none');
        document.getElementById('timePreviewBox').classList.remove('show');
        return;
    }

    var startH = parseInt(startVal.split(':')[0], 10);
    var endH   = parseInt(endVal.split(':')[0], 10);
    var diff   = endH - startH;

    document.getElementById('durationText').textContent = diff + (diff === 1 ? ' hr' : ' hrs');
    document.getElementById('durationBadge').classList.remove('d-none');

    var text = fmtHour(startH) + ' \u2013 ' + fmtHour(endH);
    if (day) text = '<strong>' + day + '</strong>, ' + text;
    text += ' &nbsp;<span class="badge bg-success">' + diff + (diff === 1 ? ' hr' : ' hrs') + '</span>';
    document.getElementById('timePreviewText').innerHTML = ' ' + text;
    document.getElementById('timePreviewBox').classList.add('show');
}

function filterSubjectsByTeacher() {
    var teacherId  = document.getElementById('teacher_id').value;
    var academicYear = document.getElementById('academic_year').value || '{{ date("Y") - 1 }}';
    var semester = document.getElementById('semester').value || '2';
    var $subjectSel  = $('#subject_id');

    if (!teacherId) {
        $subjectSel.prop('disabled', true).html('<option value="">Select a professor first...</option>');
        return;
    }

    $subjectSel.prop('disabled', true).html('<option value="">Loading...</option>');

    $.ajax({
        url: '{{ route("admin.assignments.availableSubjects") }}',
        method: 'GET',
        data: { teacher_id: teacherId, academic_year: academicYear, semester: semester },
        success: function (subjects) {
            $subjectSel.html('<option value="">Choose a subject...</option>');
            if (subjects.length === 0) {
                $subjectSel.html('<option value="">No available subjects for this term</option>');
                return;
            }
            $.each(subjects, function (i, s) {
                $subjectSel.append(
                    '<option value="' + s.id + '">' +
                        s.subject_name + ' (' + s.subject_code + ') - ' + s.credits + ' cr.' +
                    '</option>'
                );
            });
            $subjectSel.prop('disabled', false);
        },
        error: function () {
            $subjectSel.prop('disabled', false);
        }
    });
}
document.getElementById('teacher_id').addEventListener('change', function () {
    fetchTakenSlots();
    filterSubjectsByTeacher();
});
$(document).ready(function () {

$('#teacher_id').on('change', function () {
    var id       = $(this).val();
    var $preview = $('#teacherPreview');

    $('#subject_id').prop('disabled', true).html('<option value="">Select a professor first...</option>');

    fetchTakenSlots();
    filterSubjectsByTeacher();

    if (!id) {
        $preview.html('<div class="text-center text-muted py-4"><i class="fas fa-chalkboard-teacher fa-2x mb-2 opacity-25"></i><p class="mb-0 small">Select a professor to see details</p></div>');
        return;
    }

    $preview.html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-success" role="status"></div><p class="mt-2 small text-muted">Loading...</p></div>');

    $.ajax({
        url: '{{ route("admin.teachers.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function (r) {
            var initials = (r.name || '').charAt(0);
            $preview.html(
                '<div class="text-center mb-3">' +
                    '<div class="avatar-circle-large">' + initials + '</div>' +
                    '<h6 class="mt-2 mb-0 fw-bold">' + (r.name||'') + '</h6>' +
                    '<p class="text-muted small mb-0">' + (r.email||'') + '</p>' +
                '</div>' +
                '<div>' +
                    '<div class="preview-detail-row"><span class="preview-detail-label">Department</span><span class="preview-detail-value">' + (r.department||'N/A') + '</span></div>' +
                    '<div class="preview-detail-row"><span class="preview-detail-label">Status</span><span class="preview-detail-value"><span class="badge bg-success-subtle text-success">' + (r.status||'Active') + '</span></span></div>' +
                    '<div class="preview-detail-row"><span class="preview-detail-label">Employee ID</span><span class="preview-detail-value">' + (r.employee_id||'N/A') + '</span></div>' +
                '</div>'
            );
        },
        error: function () {
            $preview.html('<div class="text-center text-muted py-4"><i class="fas fa-exclamation-circle fa-2x mb-2 text-warning opacity-50"></i><p class="mb-0 small">Could not load professor details</p></div>');
        }
    });
});

    $('#subject_id').on('change', function () {
        var id       = $(this).val();
        var $preview = $('#subjectPreview');

        if (!id) {
            $preview.html('<div class="text-center text-muted py-4"><i class="fas fa-book fa-2x mb-2 opacity-25"></i><p class="mb-0 small">Select a subject to see details</p></div>');
            return;
        }
        $preview.html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-success" role="status"></div><p class="mt-2 small text-muted">Loading...</p></div>');

        $.ajax({
            url: '{{ route("admin.subjects.show", ":id") }}'.replace(':id', id),
            method: 'GET',
            success: function (r) {
                $preview.html(
                    '<div class="mb-3">' +
                        '<h6 class="fw-bold mb-1">' + (r.subject_name||'N/A') + '</h6>' +
                        '<span class="badge bg-success">' + (r.subject_code||'') + '</span>' +
                    '</div>' +
                    '<div>' +
                        '<div class="preview-detail-row"><span class="preview-detail-label">Credits</span><span class="preview-detail-value">' + (r.credits||'N/A') + '</span></div>' +
                        '<div class="preview-detail-row"><span class="preview-detail-label">Department</span><span class="preview-detail-value">' + (r.department||'N/A') + '</span></div>' +
                        '<div class="preview-detail-row"><span class="preview-detail-label">Description</span><span class="preview-detail-value" style="max-width:60%;font-size:0.82rem;font-weight:400;">' + (r.description||'No description') + '</span></div>' +
                    '</div>'
                );
            },
            error: function () {
                $preview.html('<div class="text-center text-muted py-4"><i class="fas fa-exclamation-circle fa-2x mb-2 text-warning opacity-50"></i><p class="mb-0 small">Could not load subject details</p></div>');
            }
        });
    });

    // Restore old() values after validation fail
    @if(old('start_time'))
        document.getElementById('start_time').value = '{{ old("start_time") }}';
        document.getElementById('start_time').dispatchEvent(new Event('change'));
        setTimeout(function () {
            document.getElementById('end_time').value = '{{ old("end_time") }}';
            document.getElementById('end_time').dispatchEvent(new Event('change'));
        }, 50);
    @endif

    @if(old('teacher_id'))
        setTimeout(function () {
            fetchTakenSlots();
            filterSubjectsByTeacher();
        }, 100);
    @endif

});
</script>

@endsection