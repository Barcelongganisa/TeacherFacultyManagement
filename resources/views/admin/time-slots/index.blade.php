@extends('admin.layouts.app')

@section('title', 'Time Slots Management')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-clock me-2 text-success"></i>Time Slots Management</h1>
            <p class="text-muted">Configure class time slots</p>
        </div>
        <a href="{{ route('admin.time-slots.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Time Slot
        </a>
    </div>
@endsection

@section('content')
<!-- Time Slots Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Time Slots List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Slot Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timeSlots as $slot)
                        @php
                            $start = \Carbon\Carbon::parse($slot->start_time);
                            $end = \Carbon\Carbon::parse($slot->end_time);
                            $duration = $start->diff($end)->format('%H:%I');
                        @endphp
                        <tr>
                            <td><strong>{{ $slot->slot_name }}</strong></td>
                            <td>{{ $start->format('g:i A') }}</td>
                            <td>{{ $end->format('g:i A') }}</td>
                            <td>{{ $duration }} hours</td>
                            <td>
                                <span class="badge bg-{{ $slot->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($slot->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.time-slots.edit', $slot->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $slot->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $slot->id }}" 
                                      action="{{ route('admin.time-slots.destroy', $slot->id) }}" 
                                      method="POST" 
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No time slots found</p>
                                <a href="{{ route('admin.time-slots.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Your First Time Slot
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this time slot? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deleteId = null;

function confirmDelete(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

$('#confirmDeleteBtn').click(function() {
    if (deleteId) {
        $('#delete-form-' + deleteId).submit();
    }
});

// Initialize tooltips
$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush