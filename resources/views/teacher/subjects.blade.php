@extends('teacher.layouts.app')

@section('title', 'My Subjects')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>My Assigned Subjects</h1>
        <p>View all subjects you're currently teaching</p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-number">{{ $subjects->count() }}</div>
                <div class="stat-label">Total Subjects</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">⏰</div>
                <div class="stat-number">
                    @php
                        $totalCredits = $subjects->sum('credits');
                    @endphp
                    {{ $totalCredits }}
                </div>
                <div class="stat-label">Total Credits</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number">
                    @php
                        $totalStudents = DB::table('enrollments')
                            ->join('teacher_subjects', 'enrollments.subject_id', '=', 'teacher_subjects.subject_id')
                            ->where('teacher_subjects.teacher_id', auth()->user()->teacher->id ?? 0)
                            ->where('enrollments.status', 'enrolled')
                            ->count();
                    @endphp
                    {{ $totalStudents }}
                </div>
                <div class="stat-label">Total Students</div>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    <div class="row">
        @forelse($subjects as $subject)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card subject-card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $subject->subject_code }}</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="subject-title">{{ $subject->subject_name }}</h6>
                        
                        @if($subject->description)
                            <p class="subject-description">{{ Str::limit($subject->description, 100) }}</p>
                        @endif
                        
                        <div class="subject-details mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Credits</small>
                                    <div class="fw-bold">{{ $subject->credits ?? 'N/A' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Schedule</small>
                                    <div class="fw-bold">
                                        @php
                                            $scheduleCount = DB::table('schedules')
                                                ->where('subject_id', $subject->id)
                                                ->where('teacher_id', auth()->user()->teacher->id ?? 0)
                                                ->count();
                                        @endphp
                                        {{ $scheduleCount }} sessions/week
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $studentCount = DB::table('enrollments')
                                ->where('subject_id', $subject->id)
                                ->where('status', 'enrolled')
                                ->count();
                        @endphp
                        
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Enrolled Students</span>
                                <span class="badge bg-primary">{{ $studentCount }}</span>
                            </div>
                            <div class="progress mt-1" style="height: 5px;">
                                @php
                                    $capacity = 50; // Default capacity, adjust based on your data
                                    $percentage = min(($studentCount / $capacity) * 100, 100);
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="#" class="btn btn-sm btn-outline-primary" onclick="viewSchedule({{ $subject->id }})">
                            <i class="fas fa-calendar-alt"></i> View Schedule
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-info" onclick="viewStudents({{ $subject->id }})">
                            <i class="fas fa-users"></i> View Students
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                        <h5>No Subjects Assigned</h5>
                        <p class="text-muted">You haven't been assigned any subjects yet.</p>
                        <p class="text-muted small">Contact the administrator if you believe this is an error.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Schedule by Subject Modal -->
    <div class="modal fade" id="subjectScheduleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subject Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="subjectScheduleContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    <!-- Students List Modal -->
    <div class="modal fade" id="studentsListModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enrolled Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="studentsListContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.subject-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.subject-card .card-header {
    background-color: #e8f5e8;
    border-bottom: 2px solid #28a745;
}

.subject-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 10px;
}

.subject-description {
    color: #6c757d;
    font-size: 0.9rem;
}

.subject-details {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.card-footer {
    border-top: 1px solid #dee2e6;
    padding: 10px 15px;
}

.card-footer .btn {
    margin-right: 5px;
}
</style>
@endpush

@push('scripts')
<script>
function viewSchedule(subjectId) {
    document.getElementById('subjectScheduleContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Simulate loading schedule data
    setTimeout(() => {
        document.getElementById('subjectScheduleContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monday</td>
                            <td>10:00 AM - 12:00 PM</td>
                            <td>Room 101</td>
                        </tr>
                        <tr>
                            <td>Wednesday</td>
                            <td>10:00 AM - 12:00 PM</td>
                            <td>Room 101</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }, 500);
    
    var modal = new bootstrap.Modal(document.getElementById('subjectScheduleModal'));
    modal.show();
}

function viewStudents(subjectId) {
    document.getElementById('studentsListContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    // Simulate loading students data
    setTimeout(() => {
        document.getElementById('studentsListContent').innerHTML = `
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Enrolled Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-001</td>
                            <td>John Doe</td>
                            <td>john.doe@student.com</td>
                            <td>2024-01-15</td>
                        </tr>
                        <tr>
                            <td>2024-002</td>
                            <td>Jane Smith</td>
                            <td>jane.smith@student.com</td>
                            <td>2024-01-15</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Showing 2 of 25 students
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }, 500);
    
    var modal = new bootstrap.Modal(document.getElementById('studentsListModal'));
    modal.show();
}
</script>
@endpush