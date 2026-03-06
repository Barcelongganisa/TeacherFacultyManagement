@extends('admin.layouts.app')

@section('title', 'Classrooms Management')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-school me-2 text-success"></i>Classrooms Management</h1>
            <p class="text-muted">Manage classroom and room assignments</p>
        </div>
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Classroom
        </a>
    </div>
@endsection

@section('content')
<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2 text-success"></i>Search & Filter
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.classrooms.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Classrooms</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Search by room number, name, or building..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="building" class="form-label">Building</label>
                    <select class="form-select" id="building" name="building">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $bldg)
                            @if($bldg)
                                <option value="{{ $bldg }}" {{ request('building') == $bldg ? 'selected' : '' }}>
                                    {{ $bldg }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Room Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <option value="classroom" {{ request('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                        <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="lecture room" {{ request('type') == 'lecture room' ? 'selected' : '' }}>Lecture Room</option>
                        <option value="social hall" {{ request('type') == 'social hall' ? 'selected' : '' }}>Social Hall</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="room_asc" {{ request('sort') == 'room_asc' ? 'selected' : '' }}>Room A-Z</option>
                        <option value="room_desc" {{ request('sort') == 'room_desc' ? 'selected' : '' }}>Room Z-A</option>
                        <option value="building_asc" {{ request('sort') == 'building_asc' ? 'selected' : '' }}>Building A-Z</option>
                        <option value="capacity_asc" {{ request('sort') == 'capacity_asc' ? 'selected' : '' }}>Capacity (Low-High)</option>
                        <option value="capacity_desc" {{ request('sort') == 'capacity_desc' ? 'selected' : '' }}>Capacity (High-Low)</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <span class="text-muted ms-3">
                        Showing {{ $classrooms->firstItem() ?? 0 }} - {{ $classrooms->lastItem() ?? 0 }} of {{ $classrooms->total() }} classrooms
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Classrooms Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Classrooms List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Room Number</th>
                        <th>Room Name</th>
                        <th>Building</th>
                        <th>Floor</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $classroom)
                        <tr>
                            <td><strong>{{ $classroom->room_number }}</strong></td>
                            <td>{{ $classroom->room_name }}</td>
                            <td>{{ $classroom->building ?? '-' }}</td>
                            <td>{{ $classroom->floor ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-white">
                                    {{ ucfirst(str_replace('_', ' ', $classroom->room_type)) }}
                                </span>
                            </td>
                            <td>{{ $classroom->capacity }}</td>
                            <td>
                                <span class="badge bg-{{ $classroom->status === 'active' ? 'success' : ($classroom->status === 'maintenance' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($classroom->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $classroom->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $classroom->id }}" 
                                      action="{{ route('admin.classrooms.destroy', $classroom->id) }}" 
                                      method="POST" 
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No classrooms found</p>
                                <a href="{{ route('admin.classrooms.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Your First Classroom
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($classrooms->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $classrooms->withQueryString()->links() }}
            </div>
        </div>
    @endif
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
                <p class="mb-0">Are you sure you want to delete this classroom? This action cannot be undone.</p>
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