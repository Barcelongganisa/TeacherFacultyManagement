@extends('teacher.layouts.app')

@section('title', 'My Schedule')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>My Teaching Schedule</h1>
        <p>View all your assigned classes and schedules</p>
    </div>

    <!-- Schedule Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Weekly Schedule</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="exportToCSV()">
                    <i class="fas fa-file-csv"></i> Export CSV
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered schedule-grid">
                    <thead class="table-light">
                        <tr>
                            <th class="time-column">Time</th>
                            @foreach($days as $day)
                                <th class="text-center">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timeSlots as $timeSlot)
                        <tr>
                            <td class="time-slot">
                                <div class="text-center">
                                    <strong>{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('g:i A') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($timeSlot->end_time)->format('g:i A') }}</small>
                                </div>
                            </td>
                            @foreach($days as $day)
                            <td class="schedule-cell">
                                @if(isset($scheduleData[$day][$timeSlot->id]))
                                    @php $class = $scheduleData[$day][$timeSlot->id]; @endphp
                                    <div class="class-block">
                                        <div class="subject-code">
                                                <strong>{{ $class->subject_name ?? 'N/A' }}</strong>
                                            </div>
                                        <div class="room-info">
                                            <small>
                                                <i class="fas fa-map-marker-alt"></i> 
                                                {{ $class->room_number }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No time slots configured
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(empty($scheduleData))
                    <div class="text-center text-muted mt-3">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>No schedules assigned yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Quick Legend</h6>
                    <div class="d-flex align-items-center mb-2">
                        <div style="width: 20px; height: 20px; background-color: #d4edda; border: 1px solid #28a745; margin-right: 10px;"></div>
                        <span>Your scheduled classes</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background-color: #fff3cd; border: 1px solid #ffc107; margin-right: 10px;"></div>
                        <span>Empty slots (available for scheduling)</span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle"></i> 
                        Total classes this week: <strong>{{ collect($scheduleData)->flatten()->count() }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Current Assignment Section -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Current Assignment Details</h5>
            </div>
            <div class="card-body">
                @if($teacherId)
                    @php
                        $current = DB::table('schedules as s')
                            ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
                            ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                            ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                            ->where('s.teacher_id', $teacherId)
                            ->where('s.status', 'active')
                            ->where('s.day_of_week', now()->format('l'))
                            ->whereTime('ts.start_time', '<=', now()->format('H:i:s'))
                            ->whereTime('ts.end_time', '>=', now()->format('H:i:s'))
                            ->select('sub.subject_name', 'c.room_number', 'c.room_name', 'ts.start_time', 'ts.end_time')
                            ->first();
                    @endphp
                    @if($current)
                        <div class="alert alert-success">
                            <h6 class="alert-heading">You're currently teaching!</h6>
                            <p class="mb-0">
                                <strong>Subject:</strong> {{ $current->subject_name }}<br>
                                <strong>Room:</strong> {{ $current->room_number }} - {{ $current->room_name }}<br>
                                <strong>Time:</strong> {{ \Carbon\Carbon::parse($current->start_time)->format('g:i A') }} - 
                                                     {{ \Carbon\Carbon::parse($current->end_time)->format('g:i A') }}
                            </p>
                        </div>
                    @else
                        <p class="text-muted">No current class in session</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Assigned Subjects</h5>
            </div>
            <div class="card-body">
                <div style="max-height: 250px; overflow-y: auto;">
                    @if($teacherId)
                        @php
                            $subjects = DB::table('teacher_subjects as ts')
                                ->join('subjects as s', 'ts.subject_id', '=', 's.id')
                                ->where('ts.teacher_id', $teacherId)
                                ->where('ts.status', 'active')
                                ->select('s.subject_name', 's.subject_code', 's.credits')
                                ->get();
                        @endphp
                        @forelse($subjects as $subject)
                            <div class="subject-item p-2 mb-2" style="background-color: #f8f9fa; border-radius: 5px;">
                                <strong>{{ $subject->subject_code }}</strong><br>
                                {{ $subject->subject_name }}
                                @if($subject->credits)
                                    <span class="badge bg-info float-end">{{ $subject->credits }} credits</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">No subjects assigned</p>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.schedule-grid {
    font-size: 0.9rem;
}

.schedule-grid th {
    background-color: #e8f5e8 !important;
    text-align: center;
    font-weight: bold;
    border: 1px solid #28a745;
    padding: 12px 8px;
}

.time-column {
    width: 120px;
    min-width: 120px;
}

.time-slot {
    background-color: #e8f5e8;
    border: 1px solid #28a745;
    font-size: 0.85rem;
    padding: 20px 8px;
    vertical-align: middle;
    font-weight: 500;
}

.schedule-cell {
    width: 140px;
    height: 80px;
    vertical-align: middle;
    border: 1px solid #28a745;
    padding: 4px;
    position: relative;
    background-color: #fff3cd;
    transition: all 0.2s ease;
}

.schedule-cell:has(.class-block) {
    background-color: transparent;
}

.class-block {
    background-color: #d4edda;
    border: 1px solid #28a745;
    border-radius: 4px;
    padding: 6px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    position: relative;
    transition: all 0.2s ease;
}

.class-block:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    z-index: 10;
}

.subject-code {
    font-size: 0.85rem;
    font-weight: bold;
    line-height: 1.2;
    margin-bottom: 2px;
    color: #155724;
}

.subject-name {
    font-size: 0.75rem;
    line-height: 1.1;
    margin-bottom: 2px;
    color: #155724;
    word-wrap: break-word;
}

.room-info {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: auto;
}

/* Print styles */
@media print {
    .sidebar, .navbar, .btn-group, .card-header .btn {
        display: none !important;
    }
    
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .schedule-grid {
        border: 2px solid #000;
    }
    
    .schedule-grid th {
        background-color: #ccc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .class-block {
        border: 1px solid #000;
        background-color: #eee !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
// Prepare data for exports
const scheduleData = @json($scheduleData);
const timeSlots = @json($timeSlots);
const teacherName = '{{ auth()->user()->name }}';
const days = @json($days);

// Export to PDF function
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape
    
    // Add header
    doc.setFontSize(18);
    doc.text('Weekly Teaching Schedule', 14, 22);
    
    doc.setFontSize(12);
    doc.text('Teacher: ' + teacherName, 14, 35);
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 45);
    
    // Prepare data for the table
    const tableData = [];
    
    timeSlots.forEach(timeSlot => {
        const startTime = new Date('1970-01-01T' + timeSlot.start_time).toLocaleTimeString([], 
            {hour: '2-digit', minute:'2-digit'});
        const endTime = new Date('1970-01-01T' + timeSlot.end_time).toLocaleTimeString([], 
            {hour: '2-digit', minute:'2-digit'});
        const row = [startTime + ' - ' + endTime];
        
        days.forEach(day => {
            if (scheduleData[day] && scheduleData[day][timeSlot.id]) {
                const classInfo = scheduleData[day][timeSlot.id];
                const cellContent = (classInfo.subject_code || 'N/A') + '\n' + 
                                  classInfo.subject_name + '\nRoom: ' + classInfo.room_number;
                row.push(cellContent);
            } else {
                row.push('');
            }
        });
        
        tableData.push(row);
    });
    
    // Create table
    doc.autoTable({
        head: [['Time', ...days]],
        body: tableData,
        startY: 55,
        styles: {
            fontSize: 8,
            cellPadding: 2,
            overflow: 'linebreak',
            cellWidth: 'auto'
        },
        headStyles: {
            fillColor: [40, 167, 69],
            textColor: 255
        },
        alternateRowStyles: {
            fillColor: [248, 249, 250]
        },
        columnStyles: {
            0: { cellWidth: 30 }
        }
    });
    
    // Save the PDF
    const fileName = 'Weekly_Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '_' + 
                    new Date().toISOString().split('T')[0] + '.pdf';
    doc.save(fileName);
}

// Export to CSV function
function exportToCSV() {
    let csvContent = 'Time,' + days.join(',') + '\n';
    
    timeSlots.forEach(timeSlot => {
        const startTime = new Date('1970-01-01T' + timeSlot.start_time).toLocaleTimeString([], 
            {hour: '2-digit', minute:'2-digit'});
        const endTime = new Date('1970-01-01T' + timeSlot.end_time).toLocaleTimeString([], 
            {hour: '2-digit', minute:'2-digit'});
        let row = '"' + startTime + ' - ' + endTime + '"';
        
        days.forEach(day => {
            if (scheduleData[day] && scheduleData[day][timeSlot.id]) {
                const classInfo = scheduleData[day][timeSlot.id];
                const cellContent = (classInfo.subject_code || 'N/A') + ' - ' + 
                                  classInfo.subject_name + ' (Room: ' + classInfo.room_number + ')';
                row += ',"' + cellContent.replace(/"/g, '""') + '"';
            } else {
                row += ',""';
            }
        });
        
        csvContent += row + '\n';
    });
    
    // Create and download CSV file
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    const fileName = 'Weekly_Schedule_' + teacherName.replace(/[^a-z0-9]/gi, '_') + '_' + 
                    new Date().toISOString().split('T')[0] + '.csv';
    link.setAttribute('download', fileName);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
</script>
@endpush