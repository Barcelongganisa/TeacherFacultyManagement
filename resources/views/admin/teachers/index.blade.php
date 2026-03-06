@extends('admin.layouts.app')

@section('title', 'Professors Management')

@section('page-header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="h2">Professors Management</h1>
            <p class="text-muted">Manage professor records and information</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success mt-2 mt-sm-0">
            <i class="fas fa-plus me-2"></i>Add New Professor
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
        <form method="GET" action="{{ route('admin.teachers.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Professors</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Search by name, email, or department..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <span class="text-muted ms-3">
                        Showing {{ $teachers->firstItem() ?? 0 }} - {{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() }} professors
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Teachers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Professors List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Qualification</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>#{{ $teacher->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success text-white me-2">
                                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $teacher->first_name }} {{ $teacher->last_name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ $teacher->department ?? '-' }}</td>
                            <td>{{ $teacher->qualification ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : ($teacher->status === 'inactive' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.schedules.view', $teacher->id) }}" 
                                       class="btn btn-sm btn-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="View Schedule">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $teacher->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $teacher->id }}" 
                                      action="{{ route('admin.teachers.destroy', $teacher->id) }}" 
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
                                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No professors found</p>
                                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Your First Professor
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($teachers->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $teachers->withQueryString()->links() }}
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
                <p class="mb-0">Are you sure you want to delete this professor? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
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
}

.table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
}

.pagination {
    margin-bottom: 0;
}
</style>
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