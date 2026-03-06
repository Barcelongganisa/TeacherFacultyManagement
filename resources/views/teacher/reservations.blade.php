@extends('teacher.layouts.app')

@section('title', 'Room Reservations')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>Room Reservations</h1>
        <p>Request and manage classroom reservations</p>
    </div>

    @if(session('message'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Reservation Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Request New Reservation
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.reservations.store') }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label for="reservation_date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" id="reservation_date" name="reservation_date" 
                           class="form-control @error('reservation_date') is-invalid @enderror" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('reservation_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="time_slot_id" class="form-label">Time Slot <span class="text-danger">*</span></label>
                    <select id="time_slot_id" name="time_slot_id" class="form-select @error('time_slot_id') is-invalid @enderror" required>
                        <option value="">Select time slot</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->id }}">
                                {{ $slot->slot_name }} - 
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} to 
                                {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                            </option>
                        @endforeach
                    </select>
                    @error('time_slot_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="classroom_id" class="form-label">Classroom <span class="text-danger">*</span></label>
                    <select id="classroom_id" name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                        <option value="">Select classroom</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-capacity="{{ $room->capacity }}">
                                {{ $room->room_number }} - {{ $room->room_name }} 
                                ({{ $room->room_type }}, Capacity: {{ $room->capacity }})
                            </option>
                        @endforeach
                    </select>
                    @error('classroom_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                        <i class="fas fa-search"></i> Check Availability
                    </button>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" 
                              rows="2" placeholder="Any special requirements or notes..."></textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" name="reserve" class="btn btn-primary">
                        <i class="fas fa-calendar-check"></i> Request Reservation
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Clear Form
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- My Reservations -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>My Reservations
            </h5>
            <div>
                <select class="form-select form-select-sm" id="reservationFilter" style="width: auto; display: inline-block;">
                    <option value="all">All Reservations</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="reservationsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Room</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myReservations as $reservation)
                            <tr data-status="{{ $reservation->status }}">
                                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                                <td>{{ $reservation->slot_name }}</td>
                                <td>{{ $reservation->room_number }} - {{ $reservation->room_name }}</td>
                                <td>
                                    @if($reservation->notes)
                                        <span class="cursor-help" title="{{ $reservation->notes }}">
                                            {{ Str::limit($reservation->notes, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($reservation->status) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservation->created_at)->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($reservation->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="cancelReservation({{ $reservation->id }})"
                                                title="Cancel Request">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="viewReservation({{ $reservation->id }})"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                    <p>No reservations found</p>
                                    <small>Use the form above to request a room reservation</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Date</label>
                    <input type="date" id="checkDate" class="form-control" min="{{ date('Y-m-d') }}">
                </div>
                <div id="availabilityResult">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                        <p>Select a date to check room availability</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Reservation Modal -->
<div class="modal fade" id="viewReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reservationDetails">
                Loading...
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filter reservations by status
document.getElementById('reservationFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('#reservationsTable tbody tr');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Check availability function
document.getElementById('checkDate').addEventListener('change', function() {
    const date = this.value;
    if (!date) return;
    
    // Here you would make an AJAX call to check availability
    // For now, show a loading message
    document.getElementById('availabilityResult').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Checking availability...</p>
        </div>
    `;
    
    // Simulate AJAX call
    setTimeout(() => {
        // This would be replaced with actual data from your backend
        document.getElementById('availabilityResult').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Select a room and time slot in the main form to check specific availability.
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Available Rooms</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Morning (8:00 - 10:00)</td>
                            <td><span class="badge bg-success">3 rooms available</span></td>
                        </tr>
                        <tr>
                            <td>Mid-Morning (10:00 - 12:00)</td>
                            <td><span class="badge bg-warning">1 room available</span></td>
                        </tr>
                        <tr>
                            <td>Afternoon (1:00 - 3:00)</td>
                            <td><span class="badge bg-success">4 rooms available</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }, 1000);
});

// Cancel reservation function
function cancelReservation(id) {
    if (confirm('Are you sure you want to cancel this reservation?')) {
        // Here you would submit a form to cancel the reservation
        alert('Cancel functionality - ID: ' + id);
    }
}

// View reservation details
function viewReservation(id) {
    // Here you would make an AJAX call to get reservation details
    document.getElementById('reservationDetails').innerHTML = 'Loading...';
    
    // Simulate loading
    setTimeout(() => {
        document.getElementById('reservationDetails').innerHTML = `
            <p><strong>Reservation ID:</strong> #${id}</p>
            <p><strong>Room:</strong> Room 101 - Lecture Hall</p>
            <p><strong>Date:</strong> March 15, 2024</p>
            <p><strong>Time:</strong> 10:00 AM - 12:00 PM</p>
            <p><strong>Status:</strong> <span class="badge bg-warning">Pending</span></p>
            <p><strong>Notes:</strong> Need projector and whiteboard markers</p>
            <hr>
            <p><small class="text-muted">Created: March 10, 2024 2:30 PM</small></p>
        `;
        
        var modal = new bootstrap.Modal(document.getElementById('viewReservationModal'));
        modal.show();
    }, 500);
}

// Date validation - prevent past dates
document.getElementById('reservation_date').min = new Date().toISOString().split('T')[0];
</script>
@endpush