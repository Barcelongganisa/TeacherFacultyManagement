@extends('admin.layouts.app')

@section('title', 'Professor Schedules')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-calendar-alt me-2 text-success"></i>Professor Schedules</h1>
            <p class="text-muted">View teaching schedules for all professors</p>
        </div>
    </div>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2 text-success"></i>Search Professors
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.schedules.index') }}" id="searchForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Professor</label>
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
                    <label for="department" class="form-label">Department</label>
                    <select class="form-select" id="department" name="department">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            @if($dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Teachers Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chalkboard-teacher me-2 text-success"></i>Professors List
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Professor Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success text-white me-2">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $teacher->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->department ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.schedules.view', $teacher->id) }}" 
                                       class="btn btn-sm btn-success">
                                        <i class="fas fa-calendar-alt me-2"></i>View Schedule
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No professors found</p>
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