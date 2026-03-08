@extends('superadmin.layouts.app')

@section('title', 'User Management - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-users-cog me-2"></i>User Management</h1>
            <p class="text-muted">Manage all users across all campuses</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Create New User
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter me-2"></i>Filter Users</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Campus Admin</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Users List</h5>
                <span class="text-muted">Total: {{ $users->total() }} users</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Campus</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/img/default-avatar.png') }}" 
                                         class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">@ {{ $user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'superadmin' ? 'danger' : ($user->role === 'admin' ? 'primary' : ($user->role === 'teacher' ? 'info' : 'success')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>
                                @if($user->campus)
                                    <span class="badge bg-purple">{{ $user->campus }}</span>
                                    <small class="d-block text-muted">{{ $user->campus }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td><small>{{ $user->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-info" title="View Details"
                                            data-bs-toggle="modal" data-bs-target="#viewUserModal"
                                            data-user="{{ json_encode($user) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning" title="Reset Password"
                                            data-bs-toggle="modal" data-bs-target="#resetPasswordModal"
                                            data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <form action="{{ route('superadmin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }}" 
                                                title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}"
                                                onclick="return confirm('Are you sure you want to {{ $user->status === 'active' ? 'deactivate' : 'activate' }} this user?')">
                                            <i class="fas fa-{{ $user->status === 'active' ? 'user-slash' : 'user-check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-users-slash fa-3x mb-3"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user me-2"></i>User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" id="resetPasswordForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-key me-2"></i>Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Reset password for: <strong id="resetUserName"></strong></p>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // View User Modal
    $('#viewUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var user = button.data('user');
        var modal = $(this);
        
        var detailsHtml = `
            <div class="text-center mb-3">
                <img src="${user.profile_image ? '/storage/' + user.profile_image : '/assets/img/default-avatar.png'}" 
                     class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                <h5 class="mt-2">${user.name}</h5>
                <span class="badge bg-${user.role === 'super_admin' ? 'warning' : (user.role === 'admin' ? 'primary' : (user.role === 'teacher' ? 'info' : 'success'))}">${user.role.replace('_', ' ')}</span>
            </div>
            <table class="table table-sm">
                <tr>
                    <th>Email:</th>
                    <td>${user.email}</td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td>${user.username || 'N/A'}</td>
                </tr>
                <tr>
                    <th>Campus:</th>
                    <td>${user.campus ? user.campus.campus_name : 'Global'}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><span class="badge bg-${user.status === 'active' ? 'success' : (user.status === 'pending' ? 'warning' : 'secondary')}">${user.status}</span></td>
                </tr>
                <tr>
                    <th>Email Verified:</th>
                    <td>${user.email_verified_at ? 'Yes' : 'No'}</td>
                </tr>
                <tr>
                    <th>Joined:</th>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                </tr>
            </table>
        `;
        
        modal.find('#userDetails').html(detailsHtml);
    });
    
    // Reset Password Modal
    $('#resetPasswordModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var userName = button.data('user-name');
        var modal = $(this);
        
        modal.find('#resetUserName').text(userName);
        modal.find('#resetPasswordForm').attr('action', '/super-admin/users/' + userId + '/reset-password');
    });
</script>
@endpush