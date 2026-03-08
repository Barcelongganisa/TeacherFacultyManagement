@extends('teacher.layouts.app')
@section('title', 'My Schedule')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap');

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

/* ── Export Buttons — exactly like student ── */
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
.btn-export-pdf  { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
.btn-export-pdf:hover  { background: #fee2e2; }
.btn-export-csv  { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
.btn-export-csv:hover  { background: #dbeafe; }
.btn-print       { background: var(--soft-green); color: var(--primary-green-dark); border-color: #bbf7d0; }
.btn-print:hover { background: #dcfce7; }

/* ── Tab Switcher — exactly like student ── */
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
.view-tab.active,
.view-tab:hover {
    background: #ffffff;
    color: var(--primary-green-dark);
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}

/* ── Week Overview ── */
.week-view-table { table-layout: auto; width: 100%; }
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
}
.week-day-header.has-no-class { opacity: 0.55; }
.week-day-cell { vertical-align: top; padding: 0.6rem 0.5rem; border-color: #f3f4f6 !important; }
.event-chip {
    background: #f0fdf4;
    border-left: 3px solid var(--primary-green);
    border-radius: 8px;
    padding: 0.55rem 0.7rem;
    margin-bottom: 0.5rem;
    transition: transform .15s, box-shadow .15s;
    white-space: nowrap;
}
.event-chip:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,.12); }
.event-chip .ec-code { font-size: 0.82rem; font-weight: 700; color: var(--text-dark); }
.event-chip .ec-name { font-size: 0.72rem; color: var(--text-muted); margin: 1px 0; }
.event-chip .ec-time { font-size: 0.72rem; color: var(--primary-green-dark); font-weight: 500; }
.event-chip .ec-room { font-size: 0.7rem; color: var(--text-muted); }
.no-class-label {
    font-size: 0.78rem; color: #d1d5db; font-style: italic;
    display: block; text-align: center; padding: 0.5rem 0;
}
.week-view-table tbody tr { height: auto !important; }
.week-view-table tbody td { height: auto !important; vertical-align: top; }

/* ── Time-Grid scrollable wrapper — exactly like student ── */
.grid-scroll-wrapper {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 0 0 12px 12px;
}
.grid-scroll-wrapper::-webkit-scrollbar       { width: 6px; height: 6px; }
.grid-scroll-wrapper::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
.grid-scroll-wrapper::-webkit-scrollbar-thumb { background: #a7f3d0; border-radius: 4px; }
.grid-scroll-wrapper::-webkit-scrollbar-thumb:hover { background: var(--primary-green); }

/* ── Time-Grid table — exactly like student ── */
.calendar-table {
    font-size: 0.82rem;
    min-width: 900px;
    table-layout: fixed;
}
.calendar-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
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
    justify-items: center;
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
.time-slot.half-hour { color: #c0c9d3; font-size: 0.65rem; font-weight: 400; }
.empty-slot { color: #ececec; font-size: 0.85rem; display: block; }

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
.grid-event-cell .ge-code { font-size: 0.78rem; font-weight: 700; color: var(--primary-green-dark); line-height: 1.2; }
.grid-event-cell .ge-name { font-size: 0.68rem; color: #065f46; margin: 2px 0 3px; line-height: 1.3; }
.grid-event-cell .ge-time { font-size: 0.65rem; color: #047857; font-weight: 500; }
.grid-event-cell .ge-room { font-size: 0.63rem; color: #6b7280; margin-top: 2px; }

/* ── Info Strip ── */
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
.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-state .empty-icon {
    width: 5rem; height: 5rem;
    background: var(--soft-green);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    color: var(--primary-green-light);
    font-size: 2rem;
}
.empty-state h5  { font-weight: 700; color: #374151; margin-bottom: .5rem; }
.empty-state p   { color: var(--text-muted); font-size: 0.9rem; max-width: 360px; margin: 0 auto; }

@media print {
    .export-buttons, .view-tabs, .sidebar, nav { display: none !important; }
    .content-card { box-shadow: none; border: 1px solid #ccc; }
}
@media (max-width: 768px) {
    .page-header { flex-direction: column; align-items: flex-start; }
    .info-strip   { flex-direction: column; gap: 0.25rem; }
}
</style>

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h4><i class="fa-regular fa-calendar-days me-2"></i>Schedule</h4>
        <p class="subtitle">My Teaching Schedule</p>
    </div>
    <span class="date-badge"><i class="far fa-calendar me-1"></i>{{ now()->format('F j, Y') }}</span>
</div>

@php
    $allSchedules = collect();
    foreach ($scheduleData as $day => $slots) {
        foreach ($slots as $slotId => $schedule) {
            $allSchedules->push($schedule);
        }
    }
    $hasSchedules = $allSchedules->count() > 0;
@endphp

<!-- ── CALENDAR VIEWS ── -->
<div class="content-card">
    <div class="card-header-custom">
        <div class="d-flex align-items-center gap-2">
            <div class="header-icon"><i class="fas fa-calendar-week"></i></div>
            <h3>Weekly Schedule</h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- View toggle -->
            <div class="view-tabs" role="tablist">
                <button class="view-tab active" id="tab-week" onclick="switchView('week')" role="tab">
                    <i class="fas fa-th-large"></i> Week
                </button>
                <button class="view-tab" id="tab-grid" onclick="switchView('grid')" role="tab">
                    <i class="fas fa-grip-lines"></i> Time Grid
                </button>
            </div>
            <!-- Export buttons — same style as student -->
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

        @if($hasSchedules)

        {{-- ── Week Overview Panel ── --}}
        <div id="view-week">
            @php
                $weekDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered week-view-table mb-0" style="border-color:#f3f4f6;">
                    <thead>
                        <tr>
                            @foreach($weekDays as $day)
                                @php
                                    $dayKey = strtoupper($day);
                                    $hasClass = !empty($scheduleData[$day]) || !empty($scheduleData[$dayKey]);
                                @endphp
                                <th class="week-day-header {{ !$hasClass ? 'has-no-class' : '' }}">
                                    {{ $day }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($weekDays as $day)
                                @php
                                    // Support both 'Monday' and 'MONDAY' keys
                                    $daySched = collect($scheduleData[$day] ?? $scheduleData[strtoupper($day)] ?? [])
                                        ->sortBy(fn($s) => \Carbon\Carbon::parse($s->start_time ?? $timeSlots->firstWhere('id', array_search($s, (array)($scheduleData[$day] ?? [])))?->start_time ?? '00:00')->timestamp);
                                @endphp
                                <td class="week-day-cell">
                                    @if($daySched->count() > 0)
                                        @foreach($daySched as $slotId => $schedule)
                                            @php
                                                $ts = $timeSlots->firstWhere('id', $slotId);
                                                $sTime = $ts ? \Carbon\Carbon::parse($ts->start_time) : null;
                                                $eTime = $ts ? \Carbon\Carbon::parse($ts->end_time) : null;
                                            @endphp
                                            <div class="event-chip">
                                                <div class="ec-code">{{ $schedule->subject_code ?? 'N/A' }}</div>
                                                <div class="ec-name">{{ $schedule->subject_name ?? '' }}</div>
                                                @if($sTime && $eTime)
                                                <div class="ec-time">
                                                    <i class="far fa-clock"></i>
                                                    {{ $sTime->format('g:i A') }} – {{ $eTime->format('g:i A') }}
                                                </div>
                                                @endif
                                                <div class="ec-room">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $schedule->room_number ?? '' }}
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

        {{-- ── Time Grid Panel — same logic as student ── --}}
        <div id="view-grid" style="display:none;">
            @php
                $gridDays = ['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'];

                // Build 30-min slots 7:00 AM – 9:00 PM
                $gridSlots = [];
                $cursor = \Carbon\Carbon::createFromTime(7, 0, 0);
                $gridEnd = \Carbon\Carbon::createFromTime(21, 0, 0);
                while ($cursor <= $gridEnd) {
                    $gridSlots[] = $cursor->copy();
                    $cursor->addMinutes(30);
                }

                // Re-index scheduleData by uppercase day key → array of schedule objects with times from $timeSlots
                $byDay = [];
                foreach ($gridDays as $dk) {
                    // accept both 'Monday' and 'MONDAY' as source keys
                    $srcKey = $scheduleData[$dk] ?? $scheduleData[ucfirst(strtolower($dk))] ?? [];
                    foreach ($srcKey as $slotId => $schedule) {
                        $ts = $timeSlots->firstWhere('id', $slotId);
                        if (!$ts) continue;
                        $byDay[$dk][] = (object)[
                            'subject_code' => $schedule->subject_code ?? 'N/A',
                            'subject_name' => $schedule->subject_name ?? '',
                            'room_number'  => $schedule->room_number ?? '',
                            'start_time'   => $ts->start_time,
                            'end_time'     => $ts->end_time,
                        ];
                    }
                }

                // Pre-index slot by minutes-from-midnight
                $slotMinIdx = [];
                foreach ($gridSlots as $i => $slot) {
                    $slotMinIdx[$slot->hour * 60 + $slot->minute] = $i;
                }

                // Build grid data — same algorithm as student
                $gridData = [];
                foreach ($gridDays as $dk) {
                    $gridData[$dk] = array_fill(0, count($gridSlots), null);
                    if (empty($byDay[$dk])) continue;
                    foreach ($byDay[$dk] as $ev) {
                        $sStart   = \Carbon\Carbon::parse($ev->start_time);
                        $sEnd     = \Carbon\Carbon::parse($ev->end_time);
                        $startMin = $sStart->hour * 60 + $sStart->minute;
                        if (!isset($slotMinIdx[$startMin])) continue;
                        $startIdx = $slotMinIdx[$startMin];
                        $rowspan  = max(1, (int) round($sStart->diffInMinutes($sEnd) / 30 + 1));
                        $gridData[$dk][$startIdx] = ['schedule' => $ev, 'rowspan' => $rowspan];
                        for ($j = $startIdx + 1; $j < $startIdx + $rowspan && $j < count($gridSlots); $j++) {
                            $gridData[$dk][$j] = 'skip';
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
                        @foreach($gridSlots as $slotIdx => $slotTime)
                            @php
                                $isHalfHour  = $slotTime->minute === 30;
                                $isOnTheHour = $slotTime->minute === 0;
                            @endphp
                            <tr>
                                <td class="time-slot {{ $isHalfHour ? 'half-hour' : '' }}">
                                    {{ $isOnTheHour ? $slotTime->format('g:i A') : $slotTime->format('g:i') }}
                                </td>
                                @foreach($gridDays as $dk)
                                    @php $cell = $gridData[$dk][$slotIdx]; @endphp
                                    @if($cell === 'skip')
                                        {{-- covered by rowspan above --}}
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

        <div class="info-strip mt-3" style="border-radius:10px; border:1px solid #dcfce7;">
            <small><i class="fas fa-info-circle"></i> Showing {{ $allSchedules->count() }} scheduled classes</small>
            <small><i class="far fa-clock"></i> Times displayed in 12-hour format</small>
        </div>

        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                <h5>No Schedule Available</h5>
                <p>No schedules have been assigned to you yet. Please contact your administrator.</p>
            </div>
        @endif

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
/* ── View switcher ── */
function switchView(view) {
    document.getElementById('view-week').style.display = view === 'week' ? '' : 'none';
    document.getElementById('view-grid').style.display = view === 'grid' ? '' : 'none';
    document.getElementById('tab-week').classList.toggle('active', view === 'week');
    document.getElementById('tab-grid').classList.toggle('active', view === 'grid');
}

/* Raw data for exports */
const scheduleData = @json($scheduleData);
const timeSlots    = @json($timeSlots);
const teacherName  = '{{ auth()->user()->name }}';
const days         = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

/* ── Flatten to rows for export ── */
function flatRows() {
    const rows = [];
    days.forEach(day => {
        const dayData = scheduleData[day] || scheduleData[day.toUpperCase()] || {};
        Object.entries(dayData).forEach(([slotId, s]) => {
            const ts    = timeSlots.find(t => t.id == slotId);
            const start = ts ? new Date('1970-01-01T' + ts.start_time).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}) : '';
            const end   = ts ? new Date('1970-01-01T' + ts.end_time  ).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}) : '';
            rows.push({ day, code: s.subject_code || 'N/A', name: s.subject_name || '', room: s.room_number || '', time: start + ' – ' + end });
        });
    });
    return rows;
}

/* ── Export PDF ── */
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');
    doc.setFontSize(18);
    doc.text('Weekly Teaching Schedule', 14, 20);
    doc.setFontSize(12);
    doc.text('Teacher: ' + teacherName, 14, 30);
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 38);
    doc.autoTable({
        head: [['Day', 'Subject Code', 'Subject Name', 'Room', 'Time']],
        body: flatRows().map(r => [r.day, r.code, r.name, r.room, r.time]),
        startY: 45,
        styles: { fontSize: 9, cellPadding: 3 },
        headStyles: { fillColor: [22, 163, 74], textColor: 255 },
        alternateRowStyles: { fillColor: [245, 245, 245] }
    });
    doc.save('Teacher_Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '_' + new Date().toISOString().split('T')[0] + '.pdf');
}

/* ── Export CSV ── */
function exportCSV() {
    const rows = [['Day', 'Subject Code', 'Subject Name', 'Room', 'Time']];
    flatRows().forEach(r => rows.push([r.day, r.code, r.name, r.room, r.time]));
    const csv  = rows.map(r => r.map(v => '"' + String(v).replace(/"/g, '""') + '"').join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = 'Teacher_Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    URL.revokeObjectURL(a.href);
}
</script>

@endsection