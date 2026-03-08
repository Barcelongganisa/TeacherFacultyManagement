@extends('admin.layouts.app')

@section('title', 'Subjects Management')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book me-2 text-success"></i>Subjects Management</h1>
            <p class="text-muted">Manage courses and subjects</p>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Subject
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
        <form method="GET" action="{{ route('admin.subjects.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Subjects</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Search by code, name, or department..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="code_asc" {{ request('sort') == 'code_asc' ? 'selected' : '' }}>Code A-Z</option>
                        <option value="code_desc" {{ request('sort') == 'code_desc' ? 'selected' : '' }}>Code Z-A</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="credits_asc" {{ request('sort') == 'credits_asc' ? 'selected' : '' }}>Credits (Low-High)</option>
                        <option value="credits_desc" {{ request('sort') == 'credits_desc' ? 'selected' : '' }}>Credits (High-Low)</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <span class="text-muted ms-3">
                        Showing {{ $subjects->firstItem() ?? 0 }} - {{ $subjects->lastItem() ?? 0 }} of {{ $subjects->total() }} subjects
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Subjects Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Subjects List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Year Level</th>
                        <th>Credits</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td><strong>{{ $subject->subject_code }}</strong></td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->year_level ?? '-' }}</td>
                            <td>{{ $subject->credits }}</td>
                            <td>
                                <span class="badge bg-{{ $subject->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($subject->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $subject->id }})"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <form id="delete-form-{{ $subject->id }}" 
                                      action="{{ route('admin.subjects.destroy', $subject->id) }}" 
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
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No subjects found</p>
                                <a href="{{ route('admin.subjects.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Your First Subject
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($subjects->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $subjects->withQueryString()->links() }}
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
                <p class="mb-0">Are you sure you want to delete this subject? This action cannot be undone.</p>
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