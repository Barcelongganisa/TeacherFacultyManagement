@extends('teacher.layouts.app')
@section('title', 'My Schedule')

@section('page-header')
<div class="flex-between">
    <div>
        <h1>
            <i class="fas fa-calendar-alt me-2 text-success"></i>
            {{ $teacher->name ?? 'Teacher' }}'s Schedule
        </h1>
        <p class="text-muted">Weekly teaching schedule overview</p>
    </div>
    <div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-chalkboard-teacher me-2 text-success"></i>
            Weekly Schedule
        </h5>

        <div>
            <button onclick="exportPDF()" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>

            <button onclick="exportCSV()" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>
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

<style>
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
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
const teacherName = @json($teacher->name ?? 'Teacher');
const schedules = @json($schedules);
function formatTime(time) {
    if(!time) return '';
    const [h, m] = time.split(':');
    let hour = parseInt(h);
    let ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12;
    return hour + ':' + m + ' ' + ampm;
}
function flatRows() {
    return schedules.map(s => {
        return {
            day: s.day_of_week,
            code: s.subject_code,
            name: s.subject_name,
            courseYearSection: s.course_coode +' ' +parseInt(s.year_level) + ' - ' + s.section, 
            room: s.room_number,
            time: formatTime(s.start_time) + ' - ' + formatTime(s.end_time)
        }
    });
}
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');
    doc.setFontSize(18);
    doc.text('Weekly Teaching Schedule', 14, 20);
    doc.setFontSize(12);
    doc.text('Teacher: ' + teacherName, 14, 30);
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 38);

    doc.autoTable({
        head: [['Day', 'Subject', 'Course Year / Section', 'Room', 'Time']],
        body: flatRows().map(r => [
            r.day,
            r.code + '\n' + r.name,
            r.courseYearSection,
            r.room,
            r.time
        ]),
        startY: 45,
        styles: { fontSize: 8, cellPadding: 2 },
        headStyles: { fillColor: [22, 163, 74], textColor: 255 },
        columnStyles: {
            1: { cellWidth: 50 },
            2: { cellWidth: 30 }
        }
    });

    doc.save('Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '.pdf');
}
function exportCSV() {
    const rows = [['Day','Subject Code','Subject Name','Course Year / Section','Room','Time']];
    flatRows().forEach(r => rows.push([r.day, r.code, r.name, r.courseYearSection, r.room, r.time]));

    const csv = rows.map(r => r.map(v => '"' + String(v).replace(/"/g,'""') + '"').join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '.csv';
    a.click();
}
</script>
@endpush