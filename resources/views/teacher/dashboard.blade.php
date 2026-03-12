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
        <strong>Subject:</strong> {{ $currentAssignment->subject->subject_name ?? 'N/A' }}<br>
        <strong>Room:</strong> {{ $currentAssignment->classroom->room_number ?? 'N/A' }} - {{ $currentAssignment->classroom->room_name ?? '' }}<br>
        <strong>Time:</strong> {{ substr($currentAssignment->timeSlot->start_time ?? '', 0, 5) }} - {{ substr($currentAssignment->timeSlot->end_time ?? '', 0, 5) }}
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Quick Links + Today's Classes -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quick Links</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.schedule', $teacher->id) }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-calendar-alt"></i> View Full Schedule
                </a>
                <a href="{{ route('teacher.current-assignment') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-info-circle"></i> Current Assignment Details
                </a>
                <a href="{{ route('teacher.profile.edit') }}" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-user-edit"></i> Edit My Profile
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
                                    <strong>{{ $tc->interval_time }}</strong>
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

<!-- Weekly Schedule Calendar -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-chalkboard-teacher me-2 text-success"></i>
            Weekly Schedule
        </h5>
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
                    @php
                    $startHour = 7;
                    $endHour = 21;
                    $grid = [];
                    $covered = [];

                    foreach ($days as $day) {
                        for ($h = $startHour; $h < $endHour; $h++) {
                            $grid[$day][$h] = null;
                            $covered[$day][$h] = false;
                        }
                    }

                    foreach ($schedules as $sch) {
                        $day = trim($sch->day_of_week);
                        $startHourSched = (int) \Carbon\Carbon::parse($sch->start_time)->format('H');
                        $endHourSched = (int) \Carbon\Carbon::parse($sch->end_time)->format('H');

                        if (!isset($grid[$day])) continue;

                        $grid[$day][$startHourSched] = $sch;

                        for ($h = $startHourSched + 1; $h < $endHourSched; $h++) {
                            if (isset($covered[$day][$h])) {
                                $covered[$day][$h] = true;
                            }
                        }
                    }
                    @endphp

                    @for($hour = $startHour; $hour < $endHour; $hour++)
                    <tr>
                        <td class="time-slot bg-soft-green">
                            <div class="text-center interval-time">
                                <div class="interval-start">{{ \Carbon\Carbon::createFromTime($hour,0)->format('g:i A') }}</div>
                                <div class="interval-main">{{ \Carbon\Carbon::createFromTime($hour,30)->format('g:i A') }}</div>
                                <div class="interval-end">{{ \Carbon\Carbon::createFromTime($hour+1,0)->format('g:i A') }}</div>
                            </div>
                        </td>

                        @foreach($days as $day)
                            @if($covered[$day][$hour]) @continue @endif

                            @php
                            $sched = $grid[$day][$hour];
                            $rowspan = 1;

                            if ($sched) {
                                $start = (int)\Carbon\Carbon::parse($sched->start_time)->format('H');
                                $end = (int)\Carbon\Carbon::parse($sched->end_time)->format('H');
                                $rowspan = $end - $start;
                            }
                            @endphp

                            <td class="schedule-cell" rowspan="{{ $rowspan }}">
                                @if($sched)
                                <div class="class-block">
                                    <div class="subject-code"><strong>{{ $sched->subject_code }}</strong></div>
                                    <div class="subject-name">{{ $sched->subject_name }}</div>
                                    <div class="section-info">
                                        <span class="badge bg-light text-success border border-success">
                                           {{ $sched->course_coode }} {{ intval(preg_replace('/\D/', '', $sched->year_level)) }} - {{ $sched->section }}
                                        </span>
                                    </div>
                                    <div class="room-info">
                                        <small><i class="fas fa-map-marker-alt"></i> {{ $sched->room_number }}</small>
                                    </div>
                                </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endfor
                </tbody>
            </table>
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
                            &nbsp;{{ $uc->interval_time }} ({{ substr($uc->start_time, 0, 5) }})
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
.stat-icon { font-size: 2.5rem; margin-bottom: 10px; }
.stat-number { font-size: 2rem; font-weight: bold; }
.stat-label { font-size: 0.9rem; opacity: 0.9; }

.schedule-grid th { background-color: #2ecc71 !important; color: white; text-align: center; font-weight: bold; border: 1px solid #27ae60; padding: 12px 8px; }
.time-column { width: 150px; min-width: 150px; }
.time-slot { background-color: #e8f8f5; border: 1px solid #27ae60; vertical-align: middle; }
.interval-main { font-size: 1.1rem; font-weight: 700; color: #155724; }
.interval-start, .interval-end { font-size: 0.72rem; color: #555; }
.schedule-cell { width: 140px; height: 100px; vertical-align: middle; border: 1px solid #27ae60; padding: 4px; position: relative; }
.class-block { background-color: #d4edda; border: 1px solid #27ae60; border-radius: 4px; padding: 6px; position: absolute; inset: 4px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 2px; }
.subject-code { font-size: 0.85rem; color: #155724; line-height: 1; }
.subject-name { font-size: 0.7rem; color: #155724; line-height: 1.1; margin-bottom: 2px; }
.section-info { margin: 2px 0; }
.section-info .badge { font-size: 0.65rem; padding: 2px 4px; }
.room-info { font-size: 0.65rem; color: #6c757d; }
.bg-soft-green { background-color: #e8f8f5; }
@media (max-width: 768px) {
    .schedule-grid { font-size: 0.75rem; }
    .schedule-cell { width: 100px; height: 80px; }
}
</style>
@endpush