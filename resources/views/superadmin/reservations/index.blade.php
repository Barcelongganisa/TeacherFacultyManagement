@extends('superadmin.layouts.app')

@section('title', 'Room Reservations - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-calendar-check me-2"></i>Room Reservations</h1>
            <p class="text-muted">Monitor and manage all room reservations across all campuses</p>
        </div>
        <div>
            <button class="btn btn-success" onclick="exportReservations()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReservations ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter me-2"></i>Filter Reservations</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.reservations.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Purpose, room, or user..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="campus_id" class="form-label">Campus</label>
                    <select class="form-select" id="campus_id" name="campus_id">
                        <option value="">All Campuses</option>
                        @foreach($campuses ?? [] as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->campus_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('superadmin.reservations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Reservations List</h5>
                <span class="text-muted">Total: {{ $reservations->total() ?? 0 }} reservations</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Room</th>
                            <th>Room Campus</th>
                            <th>Purpose</th>
                            <th>Reserved By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations ?? [] as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->reservation_date ? $reservation->reservation_date->format('M d, Y') : 'N/A' }}</strong>
                                @if($reservation->timeSlot)
                                    <br><small class="text-muted">
                                        {{ \Carbon\Carbon::parse($reservation->timeSlot->start_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($reservation->timeSlot->end_time)->format('h:i A') }}
                                    </small>
                                @else
                                    <br><small class="text-muted text-danger">No time slot assigned</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $reservation->classroom->room_number ?? 'N/A' }}</strong>
                                @if($reservation->classroom->room_name)
                                    <br><small class="text-muted">{{ $reservation->classroom->room_name }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-purple">{{ $reservation->classroom->campus->campus_code ?? 'N/A' }}</span>
                                <br><small class="text-muted">{{ $reservation->classroom->campus->campus_name ?? '' }}</small>
                            </td>
                            <td>
                                <div data-bs-toggle="tooltip" title="{{ $reservation->purpose }}">
                                    {{ Str::limit($reservation->purpose, 30) }}
                                </div>
                                @if($reservation->notes)
                                    <br><small class="text-muted" data-bs-toggle="tooltip" title="Notes: {{ $reservation->notes }}">
                                        <i class="fas fa-sticky-note"></i>
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>{{ $reservation->teacher->user->name ?? 'N/A' }}</strong>
                                    <small class="text-muted">{{ $reservation->teacher->user->email ?? '' }}</small>
                                    <small class="text-muted">
                                        {{ $reservation->teacher->campus ?? '' }}
                                    </small>
                                    @if($reservation->teacher && $reservation->teacher->campus) 
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $reservation->status === 'approved' ? 'success' : ($reservation->status === 'pending' ? 'warning' : ($reservation->status === 'rejected' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                                @if($reservation->status === 'rejected' && $reservation->rejection_reason)
                                    <br><small class="text-muted" data-bs-toggle="tooltip" 
                                               title="{{ $reservation->rejection_reason }}">
                                        <i class="fas fa-info-circle"></i> Reason
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-info" title="View Details"
                                            onclick="viewReservation({{ $reservation->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('superadmin.reservations.approve', $reservation) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" 
                                                    title="Approve" onclick="return confirm('Approve this reservation?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Reject" onclick="showRejectModal({{ $reservation->id }}, '{{ $reservation->classroom->room_number ?? '' }} - {{ $reservation->reservation_date ? $reservation->reservation_date->format('M d') : 'No date' }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <p>No reservations found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $reservations->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Reservation Modal -->
<div class="modal fade" id="viewReservationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reservationDetails">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Reservation Modal -->
<div class="modal fade" id="rejectReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" id="rejectReservationForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Reject reservation for: <strong id="rejectReservationDetails"></strong></p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // View Reservation Modal
    function viewReservation(id) {
        $('#viewReservationModal').modal('show');
        $('#reservationDetails').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        fetch(`/super-admin/reservations/${id}`)
            .then(response => response.json())
            .then(reservation => {
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Date:</th>
                                    <td>${new Date(reservation.reservation_date).toLocaleDateString()}</td>
                                </tr>
                                <tr>
                                    <th>Time:</th>
                                    <td>${reservation.time_slot ? reservation.time_slot.full : 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Room:</th>
                                    <td>${reservation.classroom.room_number} ${reservation.classroom.room_name ? '- ' + reservation.classroom.room_name : ''}</td>
                                </tr>
                                <tr>
                                    <th>Room Campus:</th>
                                    <td>${reservation.classroom.campus ? reservation.classroom.campus.name : 'N/A'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Reserved By:</th>
                                    <td>${reservation.user.name}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>${reservation.user.email}</td>
                                </tr>
                                <tr>
                                    <th>User Campus:</th>
                                    <td>${reservation.user.campus ? reservation.user.campus.name : 'N/A'}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td><span class="badge bg-${reservation.status === 'approved' ? 'success' : (reservation.status === 'pending' ? 'warning' : (reservation.status === 'rejected' ? 'danger' : 'secondary'))}">${reservation.status}</span></td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>${new Date(reservation.created_at).toLocaleString()}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Purpose:</h6>
                            <p class="border p-3 rounded bg-light">${reservation.purpose}</p>
                        </div>
                    </div>`;
                
                if (reservation.notes) {
                    detailsHtml += `
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6>Notes:</h6>
                            <p class="border p-3 rounded bg-light">${reservation.notes}</p>
                        </div>
                    </div>`;
                }
                
                if (reservation.rejection_reason) {
                    detailsHtml += `
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6 class="text-danger">Rejection Reason:</h6>
                            <p class="border border-danger p-3 rounded bg-light">${reservation.rejection_reason}</p>
                        </div>
                    </div>`;
                }
                
                if (reservation.approved_by) {
                    detailsHtml += `
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6 class="text-success">Approved By:</h6>
                            <p class="border border-success p-3 rounded bg-light">
                                ${reservation.approved_by.name} on ${new Date(reservation.approved_at).toLocaleString()}
                            </p>
                        </div>
                    </div>`;
                }
                
                $('#reservationDetails').html(detailsHtml);
            })
            .catch(error => {
                $('#reservationDetails').html('<div class="alert alert-danger">Error loading reservation details.</div>');
                console.error('Error:', error);
            });
    }
    
    // Reject Reservation Modal
    function showRejectModal(id, details) {
        $('#rejectReservationDetails').text(details);
        $('#rejectReservationForm').attr('action', `/super-admin/reservations/${id}/reject`);
        $('#rejectReservationModal').modal('show');
    }
    
    // Export function
    function exportReservations() {
        // Prepare CSV headers
        const headers = ['Date', 'Time', 'Room', 'Room Campus', 'Purpose', 'Reserved By', 'Email', 'User Campus', 'Status', 'Rejection Reason'];
        let csvContent = headers.join(',') + '\n';
        
        // Loop through table rows
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            // Skip empty rows
            if (row.querySelectorAll('td').length === 1) return;
            
            const cols = row.querySelectorAll('td');
            
            // Extract date and time
            const dateTimeElement = cols[0];
            const date = dateTimeElement.querySelector('strong')?.innerText || '';
            const time = dateTimeElement.querySelector('small')?.innerText || '';
            
            // Room
            const room = cols[1].innerText.replace(/\n/g, ' ').trim();
            
            // Room Campus
            const roomCampus = cols[2].innerText.replace(/\n/g, ' ').trim();
            
            // Purpose
            const purpose = cols[3].innerText.replace(/\n/g, ' ').trim();
            
            // User details
            const userDiv = cols[4];
            const userName = userDiv.querySelector('strong')?.innerText || '';
            const userEmail = userDiv.querySelector('small')?.innerText || '';
            const userCampus = userDiv.querySelector('small:last-child')?.innerText.replace('📍', '').trim() || '';
            
            // Status
            const statusDiv = cols[5];
            const status = statusDiv.querySelector('.badge')?.innerText || '';
            const rejectionReason = statusDiv.querySelector('small')?.getAttribute('title') || '';
            
            const rowData = [
                `"${date}"`,
                `"${time}"`,
                `"${room}"`,
                `"${roomCampus}"`,
                `"${purpose}"`,
                `"${userName}"`,
                `"${userEmail}"`,
                `"${userCampus}"`,
                `"${status}"`,
                `"${rejectionReason}"`
            ];
            csvContent += rowData.join(',') + '\n';
        });

        // Create CSV and trigger download
        const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        const fileName = 'Room_Reservations_' + new Date().toISOString().split('T')[0] + '.csv';
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush