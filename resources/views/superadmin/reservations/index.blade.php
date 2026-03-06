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
                            <th>Campus</th>
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
                                <strong>{{ $reservation->reserved_date->format('M d, Y') }}</strong>
                                <br><small class="text-muted">
                                    {{ date('h:i A', strtotime($reservation->start_time)) }} - 
                                    {{ date('h:i A', strtotime($reservation->end_time)) }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $reservation->classroom->room_number ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $reservation->classroom->room_name ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-purple">{{ $reservation->classroom->campus->campus_code ?? 'N/A' }}</span>
                                <br><small class="text-muted">{{ $reservation->classroom->campus->campus_name ?? '' }}</small>
                            </td>
                            <td>
                                <div data-bs-toggle="tooltip" title="{{ $reservation->purpose }}">
                                    {{ Str::limit($reservation->purpose, 30) }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ $reservation->user->name ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $reservation->user->email ?? '' }}</small>
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
                                            data-bs-toggle="modal" data-bs-target="#viewReservationModal"
                                            data-reservation="{{ json_encode($reservation) }}">
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
                                                title="Reject" data-bs-toggle="modal" 
                                                data-bs-target="#rejectReservationModal"
                                                data-reservation-id="{{ $reservation->id }}"
                                                data-reservation-details="{{ $reservation->classroom->room_number ?? '' }} - {{ $reservation->reserved_date->format('M d') }}">
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
                <!-- Dynamic content -->
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
    $('#viewReservationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var reservation = button.data('reservation');
        var modal = $(this);
        
        var detailsHtml = `
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th>Date:</th>
                            <td>${new Date(reservation.reserved_date).toLocaleDateString()}</td>
                        </tr>
                        <tr>
                            <th>Time:</th>
                            <td>${reservation.start_time} - ${reservation.end_time}</td>
                        </tr>
                        <tr>
                            <th>Room:</th>
                            <td>${reservation.classroom.room_number} - ${reservation.classroom.room_name}</td>
                        </tr>
                        <tr>
                            <th>Campus:</th>
                            <td>${reservation.classroom.campus.campus_name}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th>Reserved By:</th>
                            <td>${reservation.user.name}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>${reservation.user.email}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="badge bg-${reservation.status === 'approved' ? 'success' : (reservation.status === 'pending' ? 'warning' : 'danger')}">${reservation.status}</span></td>
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
                    <p class="border p-3 rounded">${reservation.purpose}</p>
                </div>
            </div>
            ${reservation.rejection_reason ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-danger">Rejection Reason:</h6>
                    <p class="border border-danger p-3 rounded">${reservation.rejection_reason}</p>
                </div>
            </div>
            ` : ''}
        `;
        
        modal.find('#reservationDetails').html(detailsHtml);
    });
    
    // Reject Reservation Modal
    $('#rejectReservationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var reservationId = button.data('reservation-id');
        var reservationDetails = button.data('reservation-details');
        var modal = $(this);
        
        modal.find('#rejectReservationDetails').text(reservationDetails);
        modal.find('#rejectReservationForm').attr('action', `/super-admin/reservations/${reservationId}/reject`);
    });
    
    // Export function
    function exportReservations() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '{{ route("superadmin.reservations.index") }}/export?' + params.toString();
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush