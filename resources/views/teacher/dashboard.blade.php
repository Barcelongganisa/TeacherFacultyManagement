@extends('teacher.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Professor Dashboard</h1>
    <p>Welcome to your teaching portal</p>
</div>

<!-- Current Assignment Alert -->
@if($currentAssignment)
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">Current Class Assignment</h4>
    <p class="mb-0">
        <strong>Subject:</strong> {{ $currentAssignment->subject_name }}<br>
        <strong>Room:</strong> {{ $currentAssignment->room_number }} - {{ $currentAssignment->room_name }}<br>
        <strong>Time:</strong> {{ substr($currentAssignment->start_time, 0, 5) }} - {{ substr($currentAssignment->end_time, 0, 5) }}
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number">{{ $scheduleCount }}</div>
            <div class="stat-label">Total Classes This Week</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-number">{{ $subjectCount }}</div>
            <div class="stat-label">Assigned Subjects</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon">🏛️</div>
            <div class="stat-number">{{ $roomCount }}</div>
            <div class="stat-label">Assigned Rooms</div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quick Links</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.schedule') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-calendar-alt"></i> View Full Schedule
                </a>
                <a href="{{ route('teacher.current-assignment') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-info-circle"></i> Current Assignment Details
                </a>
                <a href="{{ route('teacher.profile.edit') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-user-edit"></i> Edit My Profile
                </a>
                <a href="{{ route('teacher.availability') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-clock"></i> Set Availability
                </a>
                <a href="{{ route('teacher.reservations') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-door-open"></i> Room Reservations
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Today's Classes</h5>
            </div>
            <div class="card-body">
                <div id="todayClasses">
                    @if(count($todayClasses ?? []) > 0)
                        <ul class="list-group">
                            @foreach($todayClasses as $tc)
                                <li class="list-group-item">
                                    <strong>{{ $tc->slot_name }}</strong>
                                    &nbsp;{{ substr($tc->start_time, 0, 5) }} - {{ substr($tc->end_time, 0, 5) }}
                                    <br>
                                    {{ $tc->subject_name }} — <small>{{ $tc->room_number }} {{ $tc->room_name }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">No classes scheduled for today.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Classes -->
<div class="card">
    <div class="card-header">
        <h5>Upcoming Classes</h5>
    </div>
    <div class="card-body">
        <div id="upcomingClasses">
            @if(count($upcomingClasses ?? []) > 0)
                <ul class="list-group">
                    @foreach($upcomingClasses as $uc)
                        <li class="list-group-item">
                            <strong>{{ $uc->day_of_week }}</strong>
                            &nbsp;{{ $uc->slot_name }} ({{ substr($uc->start_time, 0, 5) }})
                            <br>
                            {{ $uc->subject_name }} — <small>{{ $uc->room_number }} {{ $uc->room_name }}</small>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted">No upcoming classes scheduled.</div>
            @endif
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
</style>
@endpush