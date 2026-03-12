@extends('layouts.student')

@section('title', 'My Schedule')

@section('content')
<div class="container-fluid">

    <div class="page-header mb-4">
        <h1><i class="fas fa-calendar-alt me-2 text-success"></i> My Class Schedule</h1>
        <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <div class="card">
        <div class="card-header">
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
                                @php $isToday = (now()->format('l') === $day); @endphp
                                <th class="text-center {{ $isToday ? 'today-col' : '' }}">
                                    {{ $day }}
                                    @if($isToday)
                                        <br><span class="today-badge">Today</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startHour = 7;
                            $endHour   = 21;
                            $grid      = [];
                            $covered   = [];

                            foreach ($days as $day) {
                                for ($h = $startHour; $h < $endHour; $h++) {
                                    $grid[$day][$h]    = null;
                                    $covered[$day][$h] = false;
                                }
                            }

                            foreach ($schedules as $sch) {
                                $day            = ucfirst(strtolower(trim($sch->day_of_week)));
                                $startHourSched = (int) \Carbon\Carbon::parse($sch->start_time)->format('H');
                                $endHourSched   = (int) \Carbon\Carbon::parse($sch->end_time)->format('H');
                                if (!isset($grid[$day])) continue;
                                $grid[$day][$startHourSched] = $sch;
                                for ($h = $startHourSched + 1; $h < $endHourSched; $h++) {
                                    if (isset($covered[$day][$h])) $covered[$day][$h] = true;
                                }
                            }
                        @endphp

                        @for($hour = $startHour; $hour < $endHour; $hour++)
                        <tr>
                            <td class="time-slot bg-soft-green">
                                <div class="text-center interval-time">
                                    <div class="interval-start">{{ \Carbon\Carbon::createFromTime($hour, 0)->format('g:i A') }}</div>
                                    <div class="interval-main">{{ \Carbon\Carbon::createFromTime($hour, 30)->format('g:i A') }}</div>
                                    <div class="interval-end">{{ \Carbon\Carbon::createFromTime($hour + 1, 0)->format('g:i A') }}</div>
                                </div>
                            </td>

                            @foreach($days as $day)
                                @if($covered[$day][$hour]) @continue @endif

                                @php
                                    $sched   = $grid[$day][$hour];
                                    $rowspan = 1;
                                    if ($sched) {
                                        $start   = (int)\Carbon\Carbon::parse($sched->start_time)->format('H');
                                        $end     = (int)\Carbon\Carbon::parse($sched->end_time)->format('H');
                                        $rowspan = max(1, $end - $start);
                                    }
                                    $isToday = (now()->format('l') === $day);
                                @endphp

                                <td class="schedule-cell {{ $isToday ? 'today-cell' : '' }}" rowspan="{{ $rowspan }}">
                                    @if($sched)
                                    <div class="class-block">
                                        <div class="subject-code"><strong>{{ $sched->subject_code }}</strong></div>
                                        <div class="subject-name">{{ $sched->subject_name }}</div>
                                        <div class="room-info">
                                            <small><i class="fas fa-map-marker-alt"></i> {{ $sched->room_number ?? 'TBA' }}</small>
                                        </div>
                                        <div class="room-info">
                                            <small><i class="fas fa-user"></i> {{ $sched->teacher_name ?? 'TBA' }}</small>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endfor
                    </tbody>
                </table>

                @if($schedules->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>No schedules found. You may not be enrolled in any subjects yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Subject Cards --}}
    @if(!$schedules->isEmpty())
    <h5 class="mt-4 mb-3 text-success fw-bold">Enrolled Subjects</h5>
    <div class="row">
        @foreach($schedules->unique('subject_code') as $sub)
        <div class="col-md-4 col-lg-3 mb-3">
            <div class="card subject-card h-100">
                <div class="card-header subject-card-header">
                    <strong>{{ $sub->subject_code }}</strong>
                </div>
                <div class="card-body py-2 px-3">
                    <div class="subject-name-card">{{ $sub->subject_name }}</div>
                    <div class="subject-meta mt-2">
                        <div><i class="fas fa-user fa-xs me-1 text-success"></i> {{ $sub->teacher_name ?? 'TBA' }}</div>
                        <div><i class="fas fa-map-marker-alt fa-xs me-1 text-success"></i> {{ $sub->room_number ?? 'TBA' }}{{ $sub->room_name ? ' · '.$sub->room_name : '' }}</div>
                        @if($sub->credits)
                        <div><i class="fas fa-star fa-xs me-1 text-success"></i> {{ $sub->credits }} credits</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    /* Calendar */
    .schedule-grid th {
        background-color: #2ecc71 !important;
        color: white;
        text-align: center;
        font-weight: bold;
        border: 1px solid #27ae60;
        padding: 12px 8px;
    }
    .time-column { width: 150px; min-width: 150px; }
    .today-col { background-color: #1e8449 !important; }
    .today-badge {
        display: inline-block;
        background: #155724;
        color: #fff;
        font-size: 0.6rem;
        padding: 1px 7px;
        border-radius: 99px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .time-slot {
        background-color: #e8f8f5;
        border: 1px solid #27ae60;
        vertical-align: middle;
    }
    .interval-main { font-size: 1.1rem; font-weight: 700; color: #155724; }
    .interval-start, .interval-end { font-size: 0.72rem; color: #555; }
    .schedule-cell {
        width: 140px;
        height: 100px;
        vertical-align: middle;
        border: 1px solid #27ae60;
        padding: 4px;
        position: relative;
    }
    .schedule-cell.today-cell { background-color: #f0fdf4; }
    .class-block {
        background-color: #d4edda;
        border: 1px solid #27ae60;
        border-radius: 4px;
        padding: 6px;
        position: absolute;
        inset: 4px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        gap: 2px;
    }
    .subject-code { font-size: 0.85rem; color: #155724; }
    .subject-name { font-size: 0.75rem; color: #155724; }
    .room-info { font-size: 0.68rem; color: #6c757d; }
    .bg-soft-green { background-color: #e8f8f5; }
    
    .subject-card {
        border: 1px solid #c3e6cb;
        border-radius: 10px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .subject-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(39,174,96,0.15);
    }
    .subject-card-header {
        background-color: #d4edda;
        color: #155724;
        border-bottom: 1px solid #c3e6cb;
        font-size: 0.85rem;
    }
    .subject-name-card {
        font-size: 0.88rem;
        font-weight: 600;
        color: #155724;
        line-height: 1.3;
    }
    .subject-meta { font-size: 0.78rem; color: #495057; display: flex; flex-direction: column; gap: 3px; }

    @media (max-width: 768px) {
        .schedule-grid { font-size: 0.75rem; }
        .schedule-cell { width: 100px; height: 80px; }
    }
</style>
@endpush