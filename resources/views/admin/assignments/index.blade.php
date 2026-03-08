@extends('admin.layouts.app')

@section('title', 'Professor Assignments')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-tasks me-2 text-success"></i>Professor Assignments</h1>
            <p class="text-muted">Manage subject assignments for professors</p>
        </div>
        <a href="{{ route('admin.assignments.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Create Assignment
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
        <form method="GET" action="{{ route('admin.assignments.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Assignments</label>
                    <div class="position-relative">
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Search by professor or subject..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label">Academic Year</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            @if($year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select" id="semester" name="semester">
                        <option value="">All</option>
                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                        <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Semester 3</option>
                        <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-select" id="department" name="department">
                        <option value="">All</option>
                        @foreach($departments as $dept)
                            @if($dept)
                               <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                    <span class="text-muted ms-3">
                        Showing {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} assignments
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Assignments Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-success"></i>Assignments List
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Professor</th>
                        <th>Subject</th>
                        <th>Academic Year</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($assignments as $assignment)
                <tr>

                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-success text-white me-2">
                                {{ substr($assignment->name ?? '',0,1) }}
                            </div>

                            <div>
                                <strong>
                                    {{ $assignment->name ?? '' }}
                                </strong>
                                <br>
                                <small class="text-muted">
                                    {{ $assignment->department ?? '' }}
                                </small>
                            </div>
                        </div>
                    </td>

                    {{-- SUBJECT --}}
                    <td>
                        <strong>{{ $assignment->subject_name ?? '' }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $assignment->subject_code ?? '' }}
                        </small>
                    </td>

                    <td>{{ $assignment->academic_year }}</td>
                    <td>{{ $assignment->semester }}</td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge bg-{{ $assignment->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($assignment->status) }}
                        </span>
                    </td>

                    {{-- ACTION --}}
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $assignment->id }})"
                                title="Delete"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <form
                            id="delete-form-{{ $assignment->id }}"
                            action="{{ route('admin.assignments.destroy',$assignment->id) }}"
                            method="POST"
                            class="d-none"
                        >
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No assignments found</p>

                        <a href="{{ route('admin.assignments.create') }}"
                           class="btn btn-success btn-sm mt-3">
                            <i class="fas fa-plus me-2"></i>
                            Create Your First Assignment
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if($assignments->hasPages())
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-center">
            {{ $assignments->withQueryString()->links() }}
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
                <p class="mb-0">Are you sure you want to delete this assignment? This action cannot be undone.</p>
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
    width: 45px;
    height: 45px;
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