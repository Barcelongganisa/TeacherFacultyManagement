@extends('admin.layouts.app')

@section('title', 'Room Reservations')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-door-open me-2 text-success"></i>Room Reservations</h1>
            <p class="text-muted">Manage and approve room reservation requests</p>
        </div>
    </div>
@endsection

@section('content')
<!-- Pending Reservations -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2 text-warning"></i>Pending Requests
            <span class="badge bg-warning text-dark ms-2">{{ $pending->count() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if($pending->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Professor</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-warning text-dark me-2">
                                            {{ substr($reservation->teacher->first_name ?? '', 0, 1) }}{{ substr($reservation->teacher->last_name ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $reservation->teacher->first_name ?? '' }} {{ $reservation->teacher->last_name ?? '' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $reservation->teacher->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $reservation->classroom->room_number ?? '' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $reservation->classroom->building ?? '' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $reservation->timeSlot->slot_name ?? '' }}
                                    </span>
                                    <br>
                                    <small>{{ \Carbon\Carbon::parse($reservation->timeSlot->start_time ?? '')->format('g:i A') }} - 
                                           {{ \Carbon\Carbon::parse($reservation->timeSlot->end_time ?? '')->format('g:i A') }}</small>
                                </td>
                                <td>
                                    @if($reservation->notes)
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                              data-bs-toggle="tooltip" title="{{ $reservation->notes }}">
                                            {{ $reservation->notes }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('admin.reservations.approve', $reservation->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Approve this reservation?')"
                                                    data-bs-toggle="tooltip" 
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="openRejectModal({{ $reservation->id }})"
                                                data-bs-toggle="tooltip" 
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <p>No pending reservations</p>
            </div>
        @endif
    </div>
</div>

<!-- All Reservations -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>All Reservations
        </h5>
    </div>
    <div class="card-body p-0">
        @if($all->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Professor</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-{{ $reservation->status === 'approved' ? 'success' : ($reservation->status === 'rejected' ? 'danger' : 'warning') }} text-white me-2">
                                            {{ substr($reservation->teacher->first_name ?? '', 0, 1) }}{{ substr($reservation->teacher->last_name ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $reservation->teacher->first_name ?? '' }} {{ $reservation->teacher->last_name ?? '' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $reservation->classroom->room_number ?? '' }}<br>
                                    <small class="text-muted">{{ $reservation->classroom->building ?? '' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $reservation->timeSlot->slot_name ?? '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $reservation->status === 'approved' ? 'success' : ($reservation->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>{{ $reservation->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-door-open fa-3x mb-3"></i>
                <p>No reservations found</p>
            </div>
        @endif
    </div>
    
    @if($all instanceof \Illuminate\Pagination\LengthAwarePaginator && $all->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $all->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason (Optional)</label>
                        <textarea class="form-control" 
                                  id="reason" 
                                  name="reason" 
                                  rows="3" 
                                  placeholder="Provide a reason for rejection..."></textarea>
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

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    flex-shrink: 0;
}
</style>
@endsection

@push('scripts')
<script>
function openRejectModal(reservationId) {
    $('#rejectForm').attr('action', '{{ url("admin/reservations/reject") }}/' + reservationId);
    $('#rejectModal').modal('show');
}

// Initialize tooltips
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush