@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="h2">Dashboard</h1>
            <p class="text-muted">Welcome to Classroom Monitoring System - Admin Panel</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge bg-success p-2">
                <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('l, F j, Y') }}
            </span>
        </div>
    </div>
@endsection

@section('content')
<style>
.stat-card {
    background: linear-gradient(145deg, #ffffff, #f8fff9);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(46, 204, 113, 0.1);
    border: 1px solid rgba(46, 204, 113, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(46, 204, 113, 0.2);
}

.stat-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2ecc71, #27ae60);
}

.stat-icon {
    font-size: 2.5rem;
    color: #2ecc71;
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.stat-label {
    color: #6c757d;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.quick-action-card {
    background: white;
    border-radius: 15px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(46, 204, 113, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: flex;
    align-items: center;
}

.quick-action-card:hover {
    background: #e8f8f5;
    transform: translateX(5px);
    border-color: #2ecc71;
    text-decoration: none;
    color: inherit;
}

.quick-action-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.2rem;
}

.quick-action-content h6 {
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.quick-action-content small {
    color: #6c757d;
    font-size: 0.8rem;
}

.recent-teacher-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid rgba(46, 204, 113, 0.1);
    transition: all 0.3s ease;
}

.recent-teacher-item:last-child {
    border-bottom: none;
}

.recent-teacher-item:hover {
    background: #f8fff9;
}

.recent-teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.recent-teacher-info {
    flex: 1;
}

.recent-teacher-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.recent-teacher-details {
    font-size: 0.85rem;
    color: #6c757d;
}

.recent-teacher-details i {
    margin-right: 0.25rem;
    font-size: 0.8rem;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.system-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(46, 204, 113, 0.1);
}

.system-info-item:last-child {
    border-bottom: none;
}

.system-info-label {
    color: #6c757d;
}

.system-info-label i {
    margin-right: 0.5rem;
    color: #2ecc71;
    width: 20px;
}

.system-info-value {
    font-weight: 500;
}
</style>

<!-- Statistics Cards - Row 1 -->
<div class="row g-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-number">{{ $totalTeachers }}</div>
            <div class="stat-label">Active Professors</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Active Students</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number">{{ $totalSubjects }}</div>
            <div class="stat-label">Active Subjects</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-school"></i>
            </div>
            <div class="stat-number">{{ $totalClassrooms }}</div>
            <div class="stat-label">Active Classrooms</div>
        </div>
    </div>
</div>

<!-- Statistics Cards - Row 2 -->
<div class="row g-4 mt-2">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-number">{{ $totalSchedules }}</div>
            <div class="stat-label">Total Schedules</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $totalTimeSlots }}</div>
            <div class="stat-label">Time Slots</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-number">{{ $pendingReservations }}</div>
            <div class="stat-label">Pending Reservations</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-number">{{ $totalAssignments }}</div>
            <div class="stat-label">Teacher Assignments</div>
        </div>
    </div>
</div>

<!-- Quick Actions and System Status -->
<div class="row mt-4 g-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-success"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.teachers.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Professor</h6>
                                <small>Create new professor account</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="{{ route('admin.students.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Student</h6>
                                <small>Create new student account</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="{{ route('admin.subjects.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Subject</h6>
                                <small>Create new subject</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="{{ route('admin.classrooms.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Classroom</h6>
                                <small>Add new classroom</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="{{ route('admin.time-slots.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Time Slot</h6>
                                <small>Create new time slot</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <a href="{{ route('admin.assignments.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Create Assignment</h6>
                                <small>Assign subject to teacher</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-success"></i>System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-circle text-success"></i> System Status:
                    </span>
                    <span class="badge bg-success">Active & Running</span>
                </div>
                
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-user"></i> Current Admin:
                    </span>
                    <span class="system-info-value">{{ auth()->user()->username }}</span>
                </div>
                
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-clock"></i> Login Time:
                    </span>
                    <span class="system-info-value">{{ session('login_time') ? date('Y-m-d H:i:s', session('login_time')) : now()->format('Y-m-d H:i:s') }}</span>
                </div>
                
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-calendar"></i> Last Updated:
                    </span>
                    <span class="system-info-value">{{ now()->format('Y-m-d H:i:s') }}</span>
                </div>
                
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-database"></i> Database:
                    </span>
                    <span class="system-info-value">
                        <span class="badge bg-success">Connected</span>
                    </span>
                </div>
                
                <div class="system-info-item">
                    <span class="system-info-label">
                        <i class="fas fa-server"></i> PHP Version:
                    </span>
                    <span class="system-info-value">{{ phpversion() }}</span>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-shield-alt fa-2x text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Admin Access Level</h6>
                            <p class="mb-0 small text-muted">You have full administrative privileges to manage all system resources.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Teachers -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Recent Professors
                    </h5>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-outline-success">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentTeachers->count() > 0)
                    @foreach($recentTeachers as $teacher)
                        <div class="recent-teacher-item">
                            <div class="recent-teacher-avatar">
                                {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                            </div>
                            <div class="recent-teacher-info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="recent-teacher-name">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                                        <div class="recent-teacher-details">
                                            <i class="fas fa-envelope"></i> {{ $teacher->email }}
                                            @if($teacher->department)
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-building"></i> {{ $teacher->department }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="status-badge bg-success text-white">Active</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                        <p>No teachers found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-radius: 15px;
}

.card-header {
    background: white;
    border-bottom: 1px solid rgba(46, 204, 113, 0.1);
    padding: 1.25rem 1.5rem;
    border-radius: 15px 15px 0 0 !important;
}

.card-body {
    padding: 1.5rem;
}

@media (max-width: 768px) {
    .stat-number {
        font-size: 2rem;
    }
    
    .quick-action-card {
        padding: 1rem;
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>
@endpush