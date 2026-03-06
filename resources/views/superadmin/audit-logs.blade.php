@extends('superadmin.layouts.app')

@section('title', 'Audit Logs - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-history me-2"></i>Audit Logs</h1>
            <p class="text-muted">Track all system activities and changes</p>
        </div>
        <button class="btn btn-primary" onclick="exportLogs()">
            <i class="fas fa-download me-2"></i>Export Logs
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter me-2"></i>Filter Logs</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.audit-logs') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" class="form-control" id="user" name="user" 
                           placeholder="Username or email" value="{{ request('user') }}">
                </div>
                <div class="col-md-3">
                    <label for="action" class="form-label">Action</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">All Actions</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="model" class="form-label">Model</label>
                    <select class="form-select" id="model" name="model">
                        <option value="">All Models</option>
                        <option value="user" {{ request('model') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="campus" {{ request('model') == 'campus' ? 'selected' : '' }}>Campus</option>
                        <option value="classroom" {{ request('model') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                        <option value="reservation" {{ request('model') == 'reservation' ? 'selected' : '' }}>Reservation</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex-between">
                <h5><i class="fas fa-list me-2"></i>Activity Logs</h5>
                <span class="text-muted">Total: {{ $logs->total() }} entries</span>
            </div>
        </div>
        <div class="card-body">
            <div class="timeline">
                @forelse($logs as $log)
                <div class="timeline-item">
                    <div class="timeline-icon bg-{{ $log->action === 'delete' ? 'danger' : ($log->action === 'create' ? 'success' : ($log->action === 'update' ? 'info' : 'secondary')) }}">
                        <i class="fas fa-{{ $log->action === 'delete' ? 'trash' : ($log->action === 'create' ? 'plus' : ($log->action === 'update' ? 'edit' : ($log->action === 'login' ? 'sign-in-alt' : 'sign-out-alt'))) }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $log->user->name ?? 'System' }}</strong>
                                <span class="text-muted">({{ $log->user->email ?? 'N/A' }})</span>
                                <span class="badge bg-{{ $log->action === 'delete' ? 'danger' : ($log->action === 'create' ? 'success' : ($log->action === 'update' ? 'info' : 'secondary')) }} ms-2">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                        
                        <p class="mb-1">
                            <strong>{{ ucfirst($log->model_type) }}:</strong> 
                            {{ $log->model_identifier }}
                        </p>
                        
                        @if($log->description)
                            <p class="mb-1">{{ $log->description }}</p>
                        @endif
                        
                        @if($log->changes)
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-info" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#changes-{{ $log->id }}">
                                    <i class="fas fa-eye"></i> View Changes
                                </button>
                                <div class="collapse mt-2" id="changes-{{ $log->id }}">
                                    <div class="card card-body bg-light">
                                        <pre class="mb-0"><code>{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-globe"></i> IP: {{ $log->ip_address }} | 
                                <i class="fas fa-browser"></i> {{ $log->user_agent }}
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-history fa-3x mb-3"></i>
                    <p>No audit logs found</p>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: 30px;
        height: 100%;
        width: 2px;
        background: #e0e0e0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 70px;
    }
    
    .timeline-icon {
        position: absolute;
        left: 15px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        z-index: 1;
    }
    
    .timeline-content {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .timeline-content:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        font-size: 12px;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush

@push('scripts')
<script>
    // Export logs
    function exportLogs() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '{{ route("superadmin.audit-logs.export") }}?' + params.toString();
    }
</script>
@endpush