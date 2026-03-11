@extends('admin.layouts.app')

@section('title', $teacher->first_name . ' ' . $teacher->last_name . ' - Schedule')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1>
                <i class="fas fa-calendar-alt me-2 text-success"></i>
                {{ $teacher->name }}'s Schedule
            </h1>
            <p class="text-muted">Weekly teaching schedule overview</p>
        </div>
        <div>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Professors
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2 text-success"></i>
                {{ $teacher->first_name }} {{ $teacher->last_name }} - Weekly Schedule
            </h5>
            <button type="button" class="btn btn-success btn-sm" onclick="openAddScheduleModal()">
                <i class="fas fa-plus me-2"></i>Add Schedule
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered schedule-grid">
                <thead class="bg-success text-white">
                    <tr>
                        <th class="time-column">Time</th>
                        @foreach($days as $day)
                            <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $timeSlot)
                        <tr>
                            <td class="time-slot bg-soft-green">
                                <div class="text-center interval-time">
                                    <div class="interval-start">
                                        {{ \Carbon\Carbon::parse($timeSlot->start_time)->format('g:i A') }}
                                    </div>
                                    <div class="interval-main">
                                        {{ $timeSlot->interval_time }}
                                    </div>
                                    <div class="interval-end">
                                        {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('g:i A') }}
                                    </div>
                                </div>
                            </td>
                            @foreach($days as $day)
                                <td class="schedule-cell">
                                    @if(isset($scheduleData[$day][$timeSlot->id]))
                                        @php $schedule = $scheduleData[$day][$timeSlot->id]; @endphp
                                        <div class="class-block editable-block" 
                                             data-schedule-id="{{ $schedule->id }}"
                                             title="Click to edit this schedule"
                                             style="cursor: pointer;">
                                            <div class="subject-code">
                                                <strong>{{ $schedule->subject->subject_code ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="subject-name">
                                                {{ $schedule->subject->subject_name ?? 'N/A' }}
                                            </div>
                                            <div class="room-info">
                                                <small>
                                                    <i class="fas fa-map-marker-alt"></i> 
                                                    {{ $schedule->classroom->room_number ?? 'N/A' }}
                                                </small>
                                            </div>
                                            <div class="edit-indicator">
                                                <i class="fas fa-edit"></i>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(empty($scheduleData))
                <div class="text-center text-muted py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>No schedules assigned to this professor</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="teacher_id" value="{{ $teacher->user_id }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_classroom_id" class="form-label">Classroom <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_classroom_id" name="classroom_id" required>
                                <option value="">Select Classroom</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">
                                        {{ $classroom->room_number }} ({{ $classroom->building }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_time_slot_id" class="form-label">Time Slot <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_time_slot_id" name="time_slot_id" required>
                                <option value="">Select Time Slot</option>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot->id }}">
                                        {{ $slot->interval_time }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_status" class="form-label">Status</label>
                        <select class="form-select" id="add_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This will check for scheduling conflicts automatically.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_schedule" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editScheduleForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_schedule_id" name="schedule_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_classroom_id" class="form-label">Classroom <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_classroom_id" name="classroom_id" required>
                                <option value="">Select Classroom</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">
                                        {{ $classroom->room_number }} ({{ $classroom->building }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                @foreach($days as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_time_slot_id" class="form-label">Time Slot <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_time_slot_id" name="time_slot_id" required>
                                <option value="">Select Time Slot</option>
                                @foreach($timeSlots as $slot)
                                    <option value="{{ $slot->id }}">
                                        {{ $slot->interval_time }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteSchedule()">
                        <i class="fas fa-trash me-2"></i>Delete Schedule
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Schedule
                        </button>
                    </div>
                </div>
            </form>

            <form id="deleteScheduleForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<style>
.schedule-grid th {
    background-color: #2ecc71 !important;
    color: white;
    text-align: center;
    font-weight: bold;
    border: 1px solid #27ae60;
    padding: 12px 8px;
}

.time-column {
    width: 150px;
    min-width: 150px;
}

.time-slot {
    background-color: #e8f8f5;
    border: 1px solid #27ae60;
    font-size: 0.85rem;
    padding: 12px 8px;
    vertical-align: middle;
}

/* Interval Time Styles */
.interval-time {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.interval-start {
    font-size: 0.72rem;
    color: #555;
    font-weight: 400;
}

.interval-main {
    font-size: 1.1rem;
    font-weight: 700;
    color: #155724;
    line-height: 1.2;
}

.interval-end {
    font-size: 0.72rem;
    color: #555;
    font-weight: 400;
}

.schedule-cell {
    width: 140px;
    height: 100px;
    vertical-align: middle;
    border: 1px solid #27ae60;
    padding: 4px;
    position: relative;
}

.class-block {
    background-color: #d4edda;
    border: 1px solid #27ae60;
    border-radius: 4px;
    padding: 8px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.editable-block:hover {
    background-color: #c3e6cb;
    border-color: #1e7e34;
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.edit-indicator {
    position: absolute;
    top: 2px;
    right: 2px;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: #155724;
    font-size: 0.7rem;
}

.editable-block:hover .edit-indicator {
    opacity: 1;
}

.subject-code {
    font-size: 0.85rem;
    font-weight: bold;
    line-height: 1.2;
    margin-bottom: 2px;
    color: #155724;
}

.subject-name {
    font-size: 0.75rem;
    line-height: 1.1;
    margin-bottom: 2px;
    color: #155724;
    word-wrap: break-word;
}

.room-info {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: auto;
}

.bg-soft-green {
    background-color: #e8f8f5;
}

@media (max-width: 768px) {
    .schedule-grid {
        font-size: 0.75rem;
    }

    .schedule-cell {
        width: 100px;
        height: 80px;
    }

    .subject-code {
        font-size: 0.7rem;
    }

    .subject-name {
        font-size: 0.65rem;
    }

    .interval-main {
        font-size: 0.9rem;
    }

    .interval-start,
    .interval-end {
        font-size: 0.65rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
let currentScheduleId = null;

$(document).ready(function() {
    $('.editable-block').click(function() {
        const scheduleId = $(this).data('schedule-id');
        if (scheduleId) {
            openEditModal(scheduleId);
        }
    });
});

function openAddScheduleModal() {
    $('#addScheduleModal').modal('show');
}

function openEditModal(scheduleId) {
    currentScheduleId = scheduleId;

    $.ajax({
        url: '{{ url("admin/schedules/get-data") }}/' + scheduleId,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#edit_schedule_id').val(scheduleId);
                $('#edit_subject_id').val(response.subject_id);
                $('#edit_classroom_id').val(response.classroom_id);
                $('#edit_day_of_week').val(response.day_of_week);
                $('#edit_time_slot_id').val(response.time_slot_id);
                $('#edit_status').val(response.status);

                $('#editScheduleForm').attr('action', '{{ url("admin/schedules") }}/' + scheduleId);
                $('#deleteScheduleForm').attr('action', '{{ url("admin/schedules") }}/' + scheduleId);

                $('#editScheduleModal').modal('show');
            } else {
                alert('Error loading schedule data');
            }
        },
        error: function() {
            alert('Error loading schedule data');
        }
    });
}

function confirmDeleteSchedule() {
    if (confirm('Are you sure you want to delete this schedule? This action cannot be undone.')) {
        $('#deleteScheduleForm').submit();
    }
}
</script>
@endpush