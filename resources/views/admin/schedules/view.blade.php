@extends('admin.layouts.app')

@section('title', $teacher->name .' - Schedule')

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
        <h5 class="mb-0">
            <i class="fas fa-chalkboard-teacher me-2 text-success"></i>
            {{ $teacher->name }} - Weekly Schedule
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

            @if(count($schedules) === 0)
            <div class="text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>No schedules assigned to this professor</p>
            </div>
            @endif
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

    .time-column { width: 150px; min-width: 150px; }

    .time-slot {
        background-color: #e8f8f5;
        border: 1px solid #27ae60;
        vertical-align: middle;
    }

    .interval-main {
        font-size: 1.1rem;
        font-weight: 700;
        color: #155724;
    }

    .interval-start, .interval-end {
        font-size: 0.72rem;
        color: #555;
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
        position: absolute;
        inset: 4px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .subject-code { font-size: 0.85rem; color: #155724; }
    .subject-name { font-size: 0.75rem; color: #155724; }
    .room-info { font-size: 0.7rem; color: #6c757d; }
    .bg-soft-green { background-color: #e8f8f5; }

    @media (max-width: 768px) {
        .schedule-grid { font-size: 0.75rem; }
        .schedule-cell { width: 100px; height: 80px; }
    }
</style>
@endsection