@extends('teacher.layouts.app')

@section('title', 'Set Availability')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>Set Your Availability</h1>
        <p>Define when you're available for classes, meetings, and consultations</p>
    </div>

    @if(session('message'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Set Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-clock me-2"></i>Set Availability Slot
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.availability.set') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Day of Week</label>
                    <select name="day_of_week" class="form-select" required>
                        @foreach($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Time Slot</label>
                    <select name="time_slot_id" class="form-select" required>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->id }}">
                                {{ $slot->slot_name }} - 
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} to 
                                {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="tentative">Tentative</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="set_availability" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-bolt me-2"></i>Quick Actions
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-success w-100 mb-2" onclick="setAllAvailable()">
                        <i class="fas fa-check-circle"></i> Set All Available
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning w-100 mb-2" onclick="setAllUnavailable()">
                        <i class="fas fa-times-circle"></i> Set All Unavailable
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info w-100 mb-2" onclick="resetWeekdays()">
                        <i class="fas fa-briefcase"></i> Set Weekdays Only
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary w-100 mb-2" onclick="clearAll()">
                        <i class="fas fa-eraser"></i> Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Grid -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>Weekly Availability Schedule
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered availability-grid">
                    <thead>
                        <tr>
                            <th class="time-column">Time Slot</th>
                            @foreach($days as $day)
                                <th class="text-center">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeSlots as $slot)
                        <tr>
                            <td class="time-slot">
                                <strong>{{ $slot->slot_name }}</strong>
                                <br>
                                <small>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - 
                                       {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</small>
                            </td>
                            @foreach($days as $day)
                                @php
                                    $status = $availability[$day][$slot->id] ?? 'available';
                                    $statusClass = match($status) {
                                        'available' => 'table-success',
                                        'unavailable' => 'table-secondary',
                                        'tentative' => 'table-warning',
                                        default => 'table-success'
                                    };
                                    $icon = match($status) {
                                        'available' => 'fa-check-circle text-success',
                                        'unavailable' => 'fa-times-circle text-secondary',
                                        'tentative' => 'fa-question-circle text-warning',
                                        default => 'fa-check-circle text-success'
                                    };
                                @endphp
                                <td class="availability-cell {{ $statusClass }}" 
                                    data-day="{{ $day }}" 
                                    data-slot="{{ $slot->id }}"
                                    onclick="toggleAvailability(this)">
                                    <i class="fas {{ $icon }}"></i>
                                    <span class="status-text">{{ ucfirst($status) }}</span>
                                    <br>
                                    <small class="click-hint">Click to toggle</small>
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Status Legend</h6>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <div class="legend-dot bg-success me-2"></div>
                            <span>Available - You're free to teach/meet</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-dot bg-warning me-2"></div>
                            <span>Tentative - Possibly available, check first</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="legend-dot bg-secondary me-2"></div>
                            <span>Unavailable - Not available</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle"></i>
                        Click on any cell to toggle availability status
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.availability-grid {
    font-size: 0.9rem;
}

.availability-grid th {
    background-color: #e8f5e8 !important;
    text-align: center;
    font-weight: bold;
    border: 1px solid #28a745;
    padding: 12px 8px;
}

.time-column {
    width: 150px;
}

.time-slot {
    background-color: #e8f5e8;
    border: 1px solid #28a745;
    padding: 10px;
    vertical-align: middle;
    font-weight: 500;
}

.availability-cell {
    width: 120px;
    height: 80px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #28a745;
    position: relative;
}

.availability-cell:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    z-index: 10;
}

.availability-cell i {
    font-size: 1.2rem;
    margin-bottom: 5px;
}

.availability-cell .status-text {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
}

.availability-cell .click-hint {
    opacity: 0;
    transition: opacity 0.2s ease;
    font-size: 0.7rem;
}

.availability-cell:hover .click-hint {
    opacity: 1;
}

.legend-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
}

/* Status colors */
.table-success {
    background-color: #d4edda !important;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-secondary {
    background-color: #e2e3e5 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Toggle availability status
function toggleAvailability(cell) {
    const day = cell.dataset.day;
    const slot = cell.dataset.slot;
    const currentIcon = cell.querySelector('i');
    const currentText = cell.querySelector('.status-text');
    
    // Cycle through statuses: available -> unavailable -> tentative -> available
    if (currentText.textContent === 'Available') {
        currentText.textContent = 'Unavailable';
        currentIcon.className = 'fas fa-times-circle text-secondary';
        cell.className = 'availability-cell table-secondary';
    } else if (currentText.textContent === 'Unavailable') {
        currentText.textContent = 'Tentative';
        currentIcon.className = 'fas fa-question-circle text-warning';
        cell.className = 'availability-cell table-warning';
    } else {
        currentText.textContent = 'Available';
        currentIcon.className = 'fas fa-check-circle text-success';
        cell.className = 'availability-cell table-success';
    }
    
    // Here you would make an AJAX call to save the new status
    // updateAvailability(day, slot, currentText.textContent.toLowerCase());
}

// Quick Actions
function setAllAvailable() {
    if (confirm('Set all time slots as available?')) {
        document.querySelectorAll('.availability-cell').forEach(cell => {
            cell.querySelector('.status-text').textContent = 'Available';
            cell.querySelector('i').className = 'fas fa-check-circle text-success';
            cell.className = 'availability-cell table-success';
        });
        // Here you would make an AJAX call to save all
    }
}

function setAllUnavailable() {
    if (confirm('Set all time slots as unavailable?')) {
        document.querySelectorAll('.availability-cell').forEach(cell => {
            cell.querySelector('.status-text').textContent = 'Unavailable';
            cell.querySelector('i').className = 'fas fa-times-circle text-secondary';
            cell.className = 'availability-cell table-secondary';
        });
    }
}

function resetWeekdays() {
    if (confirm('Set weekdays (Mon-Fri) as available and weekends as unavailable?')) {
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        document.querySelectorAll('.availability-cell').forEach(cell => {
            const day = cell.dataset.day;
            const dayIndex = days.indexOf(day);
            
            if (dayIndex >= 0 && dayIndex <= 4) { // Monday to Friday
                cell.querySelector('.status-text').textContent = 'Available';
                cell.querySelector('i').className = 'fas fa-check-circle text-success';
                cell.className = 'availability-cell table-success';
            } else { // Saturday and Sunday
                cell.querySelector('.status-text').textContent = 'Unavailable';
                cell.querySelector('i').className = 'fas fa-times-circle text-secondary';
                cell.className = 'availability-cell table-secondary';
            }
        });
    }
}

function clearAll() {
    if (confirm('Clear all availability settings?')) {
        document.querySelectorAll('.availability-cell').forEach(cell => {
            cell.querySelector('.status-text').textContent = 'Available';
            cell.querySelector('i').className = 'fas fa-check-circle text-success';
            cell.className = 'availability-cell table-success';
        });
    }
}

// AJAX function to update availability (to be implemented)
function updateAvailability(day, timeSlotId, status) {
    // Here you would make an AJAX call to your backend
    console.log('Updating:', day, timeSlotId, status);
}
</script>
@endpush