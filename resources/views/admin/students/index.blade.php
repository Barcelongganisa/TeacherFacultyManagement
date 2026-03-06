@extends('admin.layouts.app')

@section('title', 'Students Management')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-graduate me-2 text-success"></i>Students Management</h1>
            <p class="text-muted">Manage student accounts and information</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Student
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
        <form method="GET" action="{{ route('admin.students.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Students</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Search by username or email..." 
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
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Username A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Username Z-A</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <span class="text-muted ms-3">
                        Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Students Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Students List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>#{{ $student->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success text-white me-2">
                                        {{ substr($student->username, 0, 2) }}
                                    </div>
                                    <strong>{{ $student->username }}</strong>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.students.edit', $student->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $student->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $student->id }}" 
                                      action="{{ route('admin.students.destroy', $student->id) }}" 
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
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No students found</p>
                                <a href="{{ route('admin.students.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Your First Student
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($students->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $students->withQueryString()->links() }}
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
                <p class="mb-0">Are you sure you want to delete this student? This action cannot be undone.</p>
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