@extends('admin.layouts.app')

@section('title', 'Professor Details')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-circle me-2 text-success"></i>Professor Details</h1>
            <p class="text-muted">View complete professor information</p>
        </div>
        <div>
            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.schedules.view', $teacher->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-calendar-alt me-2"></i>View Schedule
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-4">
                    <div class="avatar-circle-large bg-success text-white mx-auto mb-3">
                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                    </div>
                    <h3>{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                    <p class="text-muted mb-2">{{ $teacher->email }}</p>
                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : ($teacher->status === 'inactive' ? 'danger' : 'warning') }} mb-3">
                        {{ ucfirst($teacher->status) }}
                    </span>
                    
                    @if($location && $location !== '-')
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <strong>Current Location:</strong><br>
                            {{ $location }}
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Status:</strong> Not in class
                        </div>
                    @endif
                </div>
                
                <hr>
                
                <div class="text-start">
                    <h6 class="mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.schedules.view', $teacher->id) }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-calendar-alt me-2"></i>View Schedule
                        </a>
                        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-success"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="fw-bold">{{ $teacher->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email Address</label>
                        <p class="fw-bold">{{ $teacher->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="fw-bold">{{ $teacher->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Department</label>
                        <p class="fw-bold">{{ $teacher->department ?? 'Not assigned' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Qualification</label>
                        <p class="fw-bold">{{ $teacher->specialization ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Hire Date</label>
                        <p class="fw-bold">{{ $teacher->created_at ? $teacher->created_at->format('F j, Y') : 'Not set' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Registration Date</label>
                        <p class="fw-bold">{{ $teacher->created_at->format('F j, Y g:i A') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Bio</label>
                        <p class="fw-bold">{{ $teacher->bio ?? 'No bio provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-lock me-2 text-success"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Username</label>
                        <p class="fw-bold">{{ $teacher->user->username ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Account Status</label>
                        <p>
                            <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Last Login</label>
                        <p class="fw-bold">{{ $teacher->user->last_login_at ?? 'Never' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Account Created</label>
                        <p class="fw-bold">{{ $teacher->user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <div class="display-6 fw-bold text-success">{{ $teacher->schedules->count() }}</div>
                            <div class="text-muted">Total Schedules</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <div class="display-6 fw-bold text-success">{{ $teacher->subjects->count() }}</div>
                            <div class="text-muted">Assigned Subjects</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-box">
                            <div class="display-6 fw-bold text-success">{{ $teacher->reservations->count() }}</div>
                            <div class="text-muted">Room Reservations</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 3rem;
    margin: 0 auto;
}

.stat-box {
    padding: 1rem;
    border-radius: 10px;
    background: #f8f9fa;
    transition: var(--transition);
}

.stat-box:hover {
    background: var(--soft-green);
    transform: translateY(-2px);
}

@media print {
    .btn, .sidebar, .navbar {
        display: none !important;
    }
    
    .main-content {
        margin: 0 !important;
        padding: 20px !important;
    }
}
</style>
@endsection