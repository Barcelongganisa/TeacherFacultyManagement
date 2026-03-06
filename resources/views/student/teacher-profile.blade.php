@extends('layouts.student')

@section('title', 'Teacher Profile')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('student.teachers') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Teacher Directory
            </a>
        </div>
    </div>

    <!-- Teacher Profile Card -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <!-- Profile Image -->
                    <div class="mb-3">
                        @if(!empty($teacher->profile_image) && file_exists(public_path('assets/uploads/' . $teacher->profile_image)))
                            <img src="{{ asset('assets/uploads/' . $teacher->profile_image) }}" 
                                 class="rounded-circle border" width="120" height="120" alt="Profile" style="object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; font-size: 36px; font-weight: bold;">
                                {{ strtoupper(substr($teacher->first_name, 0, 1) . substr($teacher->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Name and Title -->
                    <h4 class="card-title mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h4>
                    <p class="text-muted mb-2">Teacher</p>
                    <span class="badge {{ $teacher->status == 'active' ? 'bg-success' : ($teacher->status == 'inactive' ? 'bg-secondary' : 'bg-warning') }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Contact Information -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-address-card"></i> Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Email:</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope"></i> {{ $teacher->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Phone:</label>
                                <p class="mb-0">
                                    <i class="fas fa-phone"></i> {{ $teacher->phone ?? 'Not provided' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Department:</label>
                                <p class="mb-0">
                                    <i class="fas fa-building"></i> {{ $teacher->department ?? 'Not specified' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Current Location:</label>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    @if($currentLocation !== '-')
                                        <span class="badge bg-success">{{ $currentLocation }}</span>
                                    @else
                                        <span class="text-muted">Not currently in class</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Taught -->
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-book"></i> Subjects Taught</h5>
                </div>
                <div class="card-body">
                    @if($subjects->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($subjects as $subject)
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No subjects assigned</p>
                    @endif
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-day"></i> Today's Schedule ({{ now()->format('l, M d') }})</h5>
                </div>
                <div class="card-body">
                    @if($todaySchedule->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaySchedule as $schedule)
                                        <tr>
                                            <td>
                                                <small class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>{{ $schedule->subject_name }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ $schedule->room_name }} ({{ $schedule->room_number }})
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No classes scheduled for today</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection