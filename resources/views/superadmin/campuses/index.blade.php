@extends('superadmin.layouts.app')

@section('title', 'Campus Management - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-university me-2"></i>Campus Management</h1>
            <p class="text-muted">Manage all UCC campuses</p>
        </div>
        <a href="{{ route('superadmin.campuses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Campus
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-purple h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                Total Campuses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $campuses->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Classrooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClassrooms ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
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
                                Active Campuses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCampuses ?? 0 }}</div>
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
                                Campus Admins</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmins ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campuses Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Campuses List</h5>
                <form method="GET" action="{{ route('superadmin.campuses.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" 
                           placeholder="Search campuses..." value="{{ request('search') }}" style="width: 250px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Campus</th>
                            <th>Code</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Admins</th>
                            <th>Classrooms</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campuses as $campus)
                        <tr>
                            <td>
                                <strong>{{ $campus->campus_name }}</strong>
                            </td>
                            <td><code>{{ $campus->campus_code }}</code></td>
                            <td>
                                <small>{{ Str::limit($campus->address, 30) }}</small>
                            </td>
                            <td>
                                @if($campus->contact_email || $campus->contact_phone)
                                    <small>
                                        @if($campus->contact_email)
                                            <i class="fas fa-envelope"></i> {{ $campus->contact_email }}<br>
                                        @endif
                                        @if($campus->contact_phone)
                                            <i class="fas fa-phone"></i> {{ $campus->contact_phone }}
                                        @endif
                                    </small>
                                @else
                                    <span class="badge bg-info">{{ $campus->classrooms_count ?? 0 }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $campus->admins_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $campus->classrooms_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $campus->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($campus->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.campuses.edit', $campus) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('superadmin.campuses.show', $campus) }}" class="btn btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- <a href="{{ route('superadmin.assignments.index', ['campus' => $campus->id]) }}" 
                                       class="btn btn-outline-warning" title="Manage Admins">
                                        <i class="fas fa-user-tie"></i>
                                    </a> --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-university fa-3x mb-3"></i>
                                <p>No campuses found</p>
                                <a href="{{ route('superadmin.campuses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Add Your First Campus
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $campuses->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection