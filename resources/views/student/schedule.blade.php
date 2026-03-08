@extends('layouts.student')

@section('title', 'Classroom Schedule')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap');

    body > div.sidebar > div:nth-child(1){
    display: flex !important;
    justify-content: center !important;
    flex-wrap: wrap !important;
    flex-direction: column !important;
    align-items: center !important;
    }


    :root {
        --primary-green: #16a34a;
        --primary-green-dark: #15803d;
        --primary-green-light: #86efac;
        --soft-green: #f0fdf4;
        --text-dark: #111827;
        --text-muted: #6b7280;
        --border: #e5e7eb;
        --card-bg: #ffffff;
        --row-hover: #f9fefb;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: #f6faf7;
        color: var(--text-dark);
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }
    .page-header h4 {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0;
        color: var(--text-dark);
        letter-spacing: -0.02em;
    }
    .page-header .date-badge {
        background: var(--soft-green);
        color: var(--primary-green-dark);
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 0.35rem 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .page-header .subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0.2rem 0 0;
    }

    /* ── Content Card ── */
    .content-card {
        background: var(--card-bg);
        border-radius: 18px;
        border: 1px solid var(--border);
        box-shadow: 0 2px 16px rgba(0,0,0,.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .card-header-custom h3 {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }
    .card-header-custom .header-icon {
        width: 2.25rem;
        height: 2.25rem;
        background: var(--soft-green);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* ── Export Buttons ── */
    .export-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .export-buttons a,
    .export-buttons button {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: all .18s ease;
        border: 1.5px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .btn-export-pdf {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .btn-export-pdf:hover { background: #fee2e2; }
    .btn-export-csv {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }
    .btn-export-csv:hover { background: #dbeafe; }
    .btn-print {
        background: var(--soft-green);
        color: var(--primary-green-dark);
        border-color: #bbf7d0;
    }
    .btn-print:hover { background: #dcfce7; }

    /* ── List Table ── */
    .schedule-table thead th {
        background: var(--soft-green);
        color: var(--primary-green-dark);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.85rem 1rem;
        border: none;
        white-space: nowrap;
    }
    .schedule-table thead th:first-child { padding-left: 1.5rem; border-radius: 0; }
    .schedule-table thead th:last-child  { padding-right: 1.5rem; }
    .schedule-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background .15s;
    }
    .schedule-table tbody tr:last-child { border-bottom: none; }
    .schedule-table tbody tr:hover { background: var(--row-hover); }
    .schedule-table tbody td {
        padding: 0.9rem 1rem;
        font-size: 0.9rem;
        vertical-align: middle;
        border: none;
    }
    .schedule-table tbody td:first-child { padding-left: 1.5rem; }
    .schedule-table tbody td:last-child  { padding-right: 1.5rem; }

    .day-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
        color: #fff;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        min-width: 110px;
        justify-content: center;
        letter-spacing: 0.01em;
    }
    .icon-circle {
        width: 2rem;
        height: 2rem;
        background: #f0fdf4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-green);
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .subject-title { font-weight: 600; color: var(--text-dark); font-size: 0.9rem; }
    .subject-sub   { font-size: 0.78rem; color: var(--text-muted); margin-top: 1px; }
    .time-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 999px;
        padding: 0.32rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-dark);
        white-space: nowrap;
    }

    /* ── Footer Info Strip ── */
    .info-strip {
        background: var(--soft-green);
        border-top: 1px solid #dcfce7;
        padding: 0.75rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .info-strip small {
        color: var(--text-muted);
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .info-strip small i { color: var(--primary-green); }

    /* ── Empty State ── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state .empty-icon {
        width: 5rem;
        height: 5rem;
        background: var(--soft-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: var(--primary-green-light);
        font-size: 2rem;
    }
    .empty-state h5 { font-weight: 700; color: #374151; margin-bottom: .5rem; }
    .empty-state p  { color: var(--text-muted); font-size: 0.9rem; max-width: 360px; margin: 0 auto; }

    /* ── Weekly Calendar Card ── */
    .week-view-table {
        table-layout: auto;
        width: 100%;
    }
    .week-day-header {
        font-family: 'Syne', sans-serif;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.75rem 0.85rem;
        color: var(--primary-green-dark);
        background: var(--soft-green);
        border: none;
        text-align: center;
        /* No fixed width — shrinks to content */
    }
    /* Days with no class get a subtle muted header */
    .week-day-header.has-no-class {
        opacity: 0.55;
    }
    .week-day-cell {
        vertical-align: top;
        padding: 0.6rem 0.5rem;
        border-color: #f3f4f6 !important;
    }
    .event-chip {
        background: #f0fdf4;
        border-left: 3px solid var(--primary-green);
        border-radius: 8px;
        padding: 0.55rem 0.7rem;
        margin-bottom: 0.5rem;
        transition: transform .15s, box-shadow .15s;
        white-space: nowrap;
    }
    .event-chip:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(22,163,74,.12);
    }
    .event-chip .ec-code   { font-size: 0.82rem; font-weight: 700; color: var(--text-dark); }
    .event-chip .ec-name   { font-size: 0.72rem; color: var(--text-muted); margin: 1px 0; }
    .event-chip .ec-time   { font-size: 0.72rem; color: var(--primary-green-dark); font-weight: 500; }
    .event-chip .ec-room   { font-size: 0.7rem; color: var(--text-muted); }
    .no-class-label { font-size: 0.78rem; color: #d1d5db; font-style: italic; display: block; text-align: center; padding: 0.5rem 0; }

    /* ── Week view: cells should only be as tall as content ── */
    .week-view-table tbody tr { height: auto !important; }
    .week-view-table tbody td { height: auto !important; vertical-align: top; }

    /* ── Time-Grid scrollable wrapper ── */
    .grid-scroll-wrapper {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 0 0 12px 12px;
    }
    .grid-scroll-wrapper::-webkit-scrollbar { width: 6px; height: 6px; }
    .grid-scroll-wrapper::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .grid-scroll-wrapper::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 4px; }
    .grid-scroll-wrapper::-webkit-scrollbar-thumb:hover { background: var(--primary-green); }

    /* ── Time-Grid Calendar ── */
    .calendar-table {
        font-size: 0.82rem;
        min-width: 900px;
        table-layout: fixed;
    }
    /* Sticky header inside scroll wrapper */
    .calendar-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .calendar-table thead th {
        background: var(--soft-green);
        color: var(--primary-green-dark);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 0.7rem 0.6rem;
        text-align: center;
        border-color: #e5e7eb;
        white-space: nowrap;
    }
    .calendar-table thead th:first-child {
        width: 80px;
        text-align: left;
        padding-left: 1rem;
    }
    .calendar-table tbody tr { transition: background .1s; }
    .calendar-table tbody tr:hover > td:not(.grid-event-cell) { background: #f9fefb; }
    .calendar-table td {
        padding: 0;
        border-color: #f0f0f0 !important;
        text-align: center;
        vertical-align: middle;
        height: 28px;
        align-content: center;
        justify-items: center
    }
    .time-slot {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        white-space: nowrap;
        text-align: left;
        padding: 0 0.5rem 0 1rem !important;
        letter-spacing: 0.02em;
        width: 80px;
        background: #fafafa;
        border-right: 2px solid #e5e7eb !important;
    }
    /* Half-hour rows that are NOT on the hour get a lighter time label */
    .time-slot.half-hour {
        color: #c0c9d3;
        font-size: 0.65rem;
        font-weight: 400;
    }
    .empty-slot { color: #ececec; font-size: 0.85rem; display: block; }

    /* The green event block that spans multiple rows */
    .grid-event-cell {
        background: linear-gradient(160deg, #dcfce7 0%, #bbf7d0 100%);
        border-left: 3px solid var(--primary-green) !important;
        border-top: 1px solid #86efac !important;
        border-bottom: 1px solid #86efac !important;
        padding: 0.5rem 0.6rem !important;
        vertical-align: top !important;
        text-align: left !important;
        position: relative;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.6);
    }
    .grid-event-cell:hover {
        background: linear-gradient(160deg, #d1fae5 0%, #a7f3d0 100%);
        cursor: default;
    }
    .grid-event-cell .ge-code {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--primary-green-dark);
        line-height: 1.2;
    }
    .grid-event-cell .ge-name {
        font-size: 0.68rem;
        color: #065f46;
        margin: 2px 0 3px;
        line-height: 1.3;
    }
    .grid-event-cell .ge-time {
        font-size: 0.65rem;
        color: #047857;
        font-weight: 500;
    }
    .grid-event-cell .ge-room {
        font-size: 0.63rem;
        color: #6b7280;
        margin-top: 2px;
    }

    /* ── Tab Switcher ── */
    .view-tabs {
        display: flex;
        gap: 0.4rem;
        background: #f3f4f6;
        border-radius: 10px;
        padding: 0.3rem;
    }
    .view-tab {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        transition: all .18s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .view-tab.active, .view-tab:hover {
        background: #ffffff;
        color: var(--primary-green-dark);
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }

    @media print {
        .export-buttons, .view-tabs, .sidebar, nav { display: none !important; }
        .content-card { box-shadow: none; border: 1px solid #ccc; }
    }
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .info-strip  { flex-direction: column; gap: 0.25rem; }
    }
</style>

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h4><i class="fa-regular fa-calendar-days me-2"></i>Schedule</h4>
        <p class="subtitle">Current Schedule</p>
    </div>
    <span class="date-badge"><i class="far fa-calendar me-1"></i>{{ now()->format('F j, Y') }}</span>
</div>

<!-- ── LIST VIEW ── -->
<div class="content-card">
    <div class="card-header-custom">
        <div class="d-flex align-items-center gap-2">
            <div class="header-icon"><i class="fas fa-table"></i></div>
            <h3>Weekly Class Schedule</h3>
        </div>
    </div>

    <div class="card-body p-0">
        @if($schedules->count() > 0)
            <div class="table-responsive">
                <table class="table schedule-table mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-day me-1"></i>Day</th>
                            <th><i class="fas fa-book me-1"></i>Subject</th>
                            <th><i class="fas fa-user-tie me-1"></i>Professor</th>
                            <th><i class="fas fa-map-marker-alt me-1"></i>Room</th>
                            <th><i class="fas fa-stopwatch me-1"></i>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>
                                <span class="day-badge">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $schedule->day }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-circle"><i class="fas fa-book-open"></i></div>
                                    <div>
                                        <div class="subject-title">{{ $schedule->subject_code }}</div>
                                        <div class="subject-sub">{{ $schedule->subject_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-circle"><i class="fas fa-user-tie"></i></div>
                                    <span style="font-size:.88rem;">Prof. {{ $schedule->teacher_name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-map-marker-alt" style="color:var(--primary-green);font-size:.85rem;"></i>
                                    <div>
                                        <strong style="font-size:.88rem;">{{ $schedule->room_number }}</strong>
                                        @if(!empty($schedule->room_name))
                                            <div class="subject-sub">{{ $schedule->room_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="time-pill">
                                    <i class="far fa-clock" style="color:var(--primary-green);"></i>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} –
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="info-strip">
                <small><i class="fas fa-info-circle"></i> Showing {{ $schedules->count() }} scheduled classes</small>
                <small><i class="far fa-clock"></i> Times displayed in 12-hour format</small>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                <h5>No Schedule Available</h5>
                <p>No schedules have been set for your course yet. Please contact your administrator.</p>
            </div>
        @endif
    </div>
</div>

<!-- ── CALENDAR VIEWS ── -->
@if($schedules->count() > 0)
<div class="content-card week-card">
    <div class="card-header-custom">
        <div class="d-flex align-items-center gap-2">
            <div class="header-icon"><i class="fas fa-calendar-week"></i></div>
            <h3>Weekly Schedule</h3>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- View toggle -->
            <div class="view-tabs" role="tablist">
                <button class="view-tab active" id="tab-week"
                    onclick="switchView('week')" role="tab" aria-selected="true">
                    <i class="fas fa-th-large"></i> Week
                </button>
                <button class="view-tab" id="tab-grid"
                    onclick="switchView('grid')" role="tab" aria-selected="false">
                    <i class="fas fa-grip-lines"></i> Time Grid
                </button>
            </div>

            <!-- Export buttons -->
            <div class="export-buttons">
                <a href="#" class="btn-export-pdf" onclick="exportPDF(); return false;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="#" class="btn-export-csv" onclick="exportCSV(); return false;">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="card-body p-3">

        <!-- ── Week Overview Panel ── -->
        <div id="view-week">
            @php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered week-view-table mb-0" style="border-color:#f3f4f6;">
                    <thead>
                        <tr>
                            @foreach($days as $day)
                                @php
                                    $hasClass = $schedules->filter(fn($s) => $s->day === $day)->count() > 0;
                                @endphp
                                <th class="week-day-header {{ !$hasClass ? 'has-no-class' : '' }}">
                                    {{ $day }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($days as $day)
                                @php
                                    $daySchedules = $schedules->filter(fn($s) => $s->day === $day)
                                        ->sortBy(fn($s) => \Carbon\Carbon::parse($s->start_time)->timestamp);
                                @endphp
                                <td class="week-day-cell">
                                    @if($daySchedules->count() > 0)
                                        @foreach($daySchedules as $schedule)
                                        <div class="event-chip">
                                            <div class="ec-code">{{ $schedule->subject_code }}</div>
                                            <div class="ec-name">{{ $schedule->subject_name }}</div>
                                            <div class="ec-time">
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} –
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                            </div>
                                            <div class="ec-room">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $schedule->room_number }}
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <span class="no-class-label">No Class</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── Time Grid Panel ── -->
        <div id="view-grid" style="display:none;">
            @php
                $gridDays = ['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'];

                // Build 30-min time slots from 7:00 AM to 9:00 PM
                $timeSlots = [];
                $slotStart = \Carbon\Carbon::createFromTime(7, 0, 0);
                $slotEnd   = \Carbon\Carbon::createFromTime(21, 0, 0);
                while ($slotStart <= $slotEnd) {
                    $timeSlots[] = $slotStart->copy();
                    $slotStart->addMinutes(30);
                }

                // Index schedules by day (uppercased)
                $scheduleByDay = [];
                foreach ($schedules as $schedule) {
                    $scheduleByDay[strtoupper($schedule->day)][] = $schedule;
                }

                // Pre-index slots by minutes-from-midnight for reliable matching
                $slotMinuteIndex = [];
                foreach ($timeSlots as $i => $slot) {
                    $mins = $slot->hour * 60 + $slot->minute;
                    $slotMinuteIndex[$mins] = $i;
                }

                $gridData = [];
                foreach ($gridDays as $dayKey) {
                    $gridData[$dayKey] = array_fill(0, count($timeSlots), null);

                    if (!isset($scheduleByDay[$dayKey])) continue;

                    foreach ($scheduleByDay[$dayKey] as $schedule) {
                        $sStart = \Carbon\Carbon::parse($schedule->start_time);
                        $sEnd   = \Carbon\Carbon::parse($schedule->end_time);

                        // Match start slot by minutes-from-midnight (avoids AM/PM string mismatch)
                        $startMins = $sStart->hour * 60 + $sStart->minute;
                        if (!isset($slotMinuteIndex[$startMins])) continue;
                        $startIdx = $slotMinuteIndex[$startMins];

                        // Rowspan = exact number of 30-min slots the event occupies
                        $durationMins = $sStart->diffInMinutes($sEnd);
                        $rowspan = $durationMins / 30 + 1; // +1 to include the starting slot

                        // Store the event at the start slot
                        $gridData[$dayKey][$startIdx] = ['schedule' => $schedule, 'rowspan' => $rowspan];

                        // Mark subsequent covered slots as 'skip'
                        for ($j = $startIdx + 1; $j < $startIdx + $rowspan && $j < count($timeSlots); $j++) {
                            $gridData[$dayKey][$j] = 'skip';
                        }
                    }
                }
            @endphp

            <div class="grid-scroll-wrapper">
                <table class="table table-bordered calendar-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:80px;">TIME</th>
                            @foreach($gridDays as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $slotIdx => $slotTime)
                        @php
                            $isHalfHour = $slotTime->minute === 30;
                            $isOnTheHour = $slotTime->minute === 0;
                        @endphp
                        <tr>
                            <td class="time-slot {{ $isHalfHour ? 'half-hour' : '' }}">
                                {{ $isOnTheHour ? $slotTime->format('g:i A') : $slotTime->format('g:i') }}
                            </td>

                            @foreach($gridDays as $dayKey)
                                @php $cell = $gridData[$dayKey][$slotIdx]; @endphp

                                @if($cell === 'skip')
                                    {{-- This cell is covered by a rowspan above — emit nothing --}}
                                @elseif(is_array($cell))
                                    @php
                                        $ev = $cell['schedule'];
                                        $rs = $cell['rowspan'];
                                    @endphp
                                    <td class="grid-event-cell" rowspan="{{ $rs }}">
                                        <div class="ge-code">{{ $ev->subject_code }}</div>
                                        @if(!empty($ev->subject_name))
                                            <div class="ge-name">{{ $ev->subject_name }}</div>
                                        @endif
                                        <div class="ge-time">
                                            <i class="far fa-clock" style="font-size:0.6rem;"></i>
                                            {{ \Carbon\Carbon::parse($ev->start_time)->format('g:i A') }} –
                                            {{ \Carbon\Carbon::parse($ev->end_time)->format('g:i A') }}
                                        </div>
                                        @if(!empty($ev->room_number))
                                            <div class="ge-room">
                                                <i class="fas fa-map-marker-alt" style="font-size:0.6rem;"></i>
                                                {{ $ev->room_number }}
                                            </div>
                                        @endif
                                    </td>
                                @else
                                    <td><span class="empty-slot">·</span></td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endif

<script>
    /* ── View switcher ── */
    function switchView(view) {
        document.getElementById('view-week').style.display = view === 'week' ? '' : 'none';
        document.getElementById('view-grid').style.display = view === 'grid' ? '' : 'none';
        document.getElementById('tab-week').classList.toggle('active', view === 'week');
        document.getElementById('tab-grid').classList.toggle('active', view === 'grid');
    }

    /* ── Export PDF ── */
    const schedules = @json($schedules);
    const studentName = '{{ auth()->user()->name }}';

    function exportPDF() {

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        doc.setFontSize(18);
        doc.text('Student Weekly Schedule', 14, 20);

        doc.setFontSize(12);
        doc.text('Student: ' + studentName, 14, 30);
        doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 38);

        const tableData = [];

        schedules.forEach(s => {

            const start = new Date('1970-01-01T' + s.start_time)
                .toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});

            const end = new Date('1970-01-01T' + s.end_time)
                .toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});

            tableData.push([
                s.day,
                s.subject_code,
                s.subject_name,
                s.teacher_name,
                s.room_number,
                start + ' - ' + end
            ]);

        });

        doc.autoTable({
            head: [['Day','Subject Code','Subject Name','Professor','Room','Time']],
            body: tableData,
            startY: 45,
            styles: {
                fontSize: 9,
                cellPadding: 3
            },
            headStyles: {
                fillColor: [22,163,74],
                textColor: 255
            },
            alternateRowStyles: {
                fillColor: [245,245,245]
            }
        });

        doc.save(
            'Student_Schedule_' +
            studentName.replace(/[^a-z0-9]/gi,'_') +
            '_' +
            new Date().toISOString().split('T')[0] +
            '.pdf'
        );
    }

    /* ── Export CSV ── */
    function exportCSV() {
        const rows = [['Day','Subject Code','Subject Name','Professor','Room','Room Name','Start Time','End Time']];

        @foreach($schedules as $schedule)
        rows.push([
            '{{ addslashes($schedule->day) }}',
            '{{ addslashes($schedule->subject_code) }}',
            '{{ addslashes($schedule->subject_name) }}',
            '{{ addslashes($schedule->teacher_name) }}',
            '{{ addslashes($schedule->room_number) }}',
            '{{ addslashes($schedule->room_name ?? '') }}',
            '{{ \Carbon\Carbon::parse($schedule->start_time)->format("g:i A") }}',
            '{{ \Carbon\Carbon::parse($schedule->end_time)->format("g:i A") }}'
        ]);
        @endforeach

        const csv = rows.map(r => r.map(v => '"' + String(v).replace(/"/g, '""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'schedule.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    }
</script>
@endsection