@extends('teacher.layouts.app')

@section('title', 'Current Assignment')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>Current Assignment</h1>
        <p>View your current class in session</p>
    </div>

    @if($currentAssignment)
        <!-- Current Class Card -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card current-class-card">
                    <div class="card-body text-center">
                        <div class="live-indicator">
                            <span class="live-dot"></span>
                            <span class="live-text">LIVE NOW</span>
                        </div>
                        
                        <i class="fas fa-chalkboard-teacher class-icon"></i>
                        
                        <h2 class="subject-name mt-3">{{ $currentAssignment->subject_name }}</h2>
                        
                        <div class="class-details mt-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="detail-box">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <h5>Location</h5>
                                        <p>{{ $currentAssignment->room_number }} - {{ $currentAssignment->room_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-box">
                                        <i class="fas fa-clock"></i>
                                        <h5>Time</h5>
                                        <p>{{ \Carbon\Carbon::parse($currentAssignment->start_time)->format('g:i A') }} - 
                                           {{ \Carbon\Carbon::parse($currentAssignment->end_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-box">
                                        <i class="fas fa-calendar-day"></i>
                                        <h5>Day</h5>
                                        <p>{{ now()->format('l, F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="class-progress mt-4">
                            @php
                                $now = now();
                                $start = \Carbon\Carbon::parse($currentAssignment->start_time);
                                $end = \Carbon\Carbon::parse($currentAssignment->end_time);
                                $totalMinutes = $start->diffInMinutes($end);
                                $elapsedMinutes = $start->diffInMinutes($now);
                                $progress = ($elapsedMinutes / $totalMinutes) * 100;
                                $timeRemaining = $now->diffInMinutes($end);
                            @endphp
                            
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <p class="mt-2 text-muted">
                                <i class="fas fa-hourglass-half"></i>
                                {{ $timeRemaining }} minutes remaining
                            </p>
                        </div>

                        <div class="action-buttons mt-4">
                            <button class="btn btn-primary" onclick="startAttendance()">
                                <i class="fas fa-clipboard-list"></i> Take Attendance
                            </button>
                            <button class="btn btn-info" onclick="viewClassMaterials()">
                                <i class="fas fa-folder-open"></i> Class Materials
                            </button>
                            <button class="btn btn-secondary" onclick="sendNotification()">
                                <i class="fas fa-bell"></i> Send Notification
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-details">
                        <h3>32</h3>
                        <p>Students Present</p>
                        <small class="text-muted">Out of 35 enrolled</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-details">
                        <h3>91%</h3>
                        <p>Attendance Rate</p>
                        <small class="text-muted">Last 5 sessions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-details">
                        <h3>4.5</h3>
                        <p>Class Rating</p>
                        <small class="text-muted">From student feedback</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Class Preview -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Next Class Preview</h5>
            </div>
            <div class="card-body">
                @php
                    $nextClass = DB::table('schedules as s')
                        ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
                        ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                        ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                        ->where('s.teacher_id', auth()->user()->teacher->id ?? 0)
                        ->where('s.status', 'active')
                        ->where('s.day_of_week', now()->addDay()->format('l'))
                        ->orderBy('ts.start_time')
                        ->first();
                @endphp

                @if($nextClass)
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Subject:</strong>
                            <p>{{ $nextClass->subject_name }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Time:</strong>
                            <p>{{ \Carbon\Carbon::parse($nextClass->start_time)->format('g:i A') }} - 
                               {{ \Carbon\Carbon::parse($nextClass->end_time)->format('g:i A') }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Room:</strong>
                            <p>{{ $nextClass->room_number }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Day:</strong>
                            <p>{{ now()->addDay()->format('l') }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-muted">No class scheduled for tomorrow</p>
                @endif
            </div>
        </div>
    @else
        <!-- No Current Class -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card no-class-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clock fa-4x text-muted mb-3"></i>
                        <h3>No Current Class</h3>
                        <p class="text-muted">You don't have any class in session right now.</p>
                        
                        <div class="next-class-info mt-4">
                            @php
                                $nextUpcoming = DB::table('schedules as s')
                                    ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
                                    ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                                    ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                                    ->where('s.teacher_id', auth()->user()->teacher->id ?? 0)
                                    ->where('s.status', 'active')
                                    ->orderByRaw("FIELD(s.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                                    ->orderBy('ts.start_time')
                                    ->first();
                            @endphp

                            @if($nextUpcoming)
                                <h5>Next Upcoming Class</h5>
                                <p class="mb-1">
                                    <strong>{{ $nextUpcoming->subject_name }}</strong>
                                </p>
                                <p class="text-muted">
                                    {{ $nextUpcoming->day_of_week }}, 
                                    {{ \Carbon\Carbon::parse($nextUpcoming->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($nextUpcoming->end_time)->format('g:i A') }}
                                    <br>
                                    Room: {{ $nextUpcoming->room_number }}
                                </p>
                            @endif
                        </div>

                        <a href="{{ route('teacher.schedule') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-calendar-alt"></i> View Full Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.current-class-card {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.current-class-card .card-body {
    padding: 40px;
}

.live-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.live-dot {
    width: 12px;
    height: 12px;
    background-color: #dc3545;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

.live-text {
    color: #dc3545;
    font-weight: bold;
    font-size: 1.1rem;
}

.class-icon {
    font-size: 4rem;
    color: #28a745;
}

.subject-name {
    color: #2c3e50;
    font-weight: 600;
}

.detail-box {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
}

.detail-box i {
    font-size: 1.5rem;
    color: #28a745;
    margin-bottom: 10px;
}

.detail-box h5 {
    font-size: 1rem;
    margin-bottom: 5px;
    color: #6c757d;
}

.detail-box p {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0;
    color: #2c3e50;
}

.class-progress {
    max-width: 500px;
    margin: 0 auto;
}

.action-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 150px;
}

.stats-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon i {
    font-size: 1.8rem;
    color: white;
}

.stats-details h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50;
}

.stats-details p {
    margin: 5px 0 0;
    color: #6c757d;
}

.no-class-card {
    border: 2px dashed #dee2e6;
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
function startAttendance() {
    alert('Attendance feature coming soon!');
}

function viewClassMaterials() {
    alert('Class materials feature coming soon!');
}

function sendNotification() {
    alert('Notification feature coming soon!');
}

// Auto-refresh the page every minute to update progress
setTimeout(function() {
    location.reload();
}, 60000); // Refresh every minute
</script>
@endpush