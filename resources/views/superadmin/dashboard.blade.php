@extends('superadmin.layouts.app')

@section('title', 'Dashboard - Super Admin')

@push('styles')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(26, 122, 60, 0.2);
    }
    .stat-card .card-body {
        padding: 1.5rem;
    }
    .stat-card .icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-card .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: #2c3e50;
    }
    .stat-card .card-footer {
        padding: 0.75rem 1.5rem;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    .stat-card .card-footer a {
        font-size: 0.82rem;
        font-weight: 500;
        text-decoration: none;
        transition: gap 0.2s;
    }
    .stat-card .card-footer a:hover {
        text-decoration: underline;
    }

    /* Card accent colors */
    .stat-green  { background: linear-gradient(135deg, #f0faf4, #fff); border-left: 4px solid #1a7a3c; }
    .stat-blue   { background: linear-gradient(135deg, #eff6ff, #fff); border-left: 4px solid #3b82f6; }
    .stat-orange { background: linear-gradient(135deg, #fff7ed, #fff); border-left: 4px solid #f97316; }
    .stat-teal   { background: linear-gradient(135deg, #f0fdfa, #fff); border-left: 4px solid #14b8a6; }

    .icon-green  { background: rgba(26, 122, 60, 0.1);  color: #1a7a3c; }
    .icon-blue   { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .icon-orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
    .icon-teal   { background: rgba(20, 184, 166, 0.1); color: #14b8a6; }

    .role-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>
@endpush

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-tachometer-alt me-2"></i>Super Admin Dashboard</h1>
            <p class="text-muted">Global system overview and management</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Create User
            </a>
            <a href="{{ route('superadmin.campuses.create') }}" class="btn btn-success">
                <i class="fas fa-university me-2"></i>Add Campus
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Statistics Cards --}}
    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-green h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrap icon-green">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <div class="stat-label text-success">Total Campuses</div>
                        <div class="stat-value">{{ $totalCampuses }}</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('superadmin.campuses.index') }}" class="text-success">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-blue h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrap icon-blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-label text-primary">Total Users</div>
                        <div class="stat-value">{{ $totalUsers }}</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('superadmin.users.index') }}" class="text-primary">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-orange h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrap icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-label text-warning">Pending Reservations</div>
                        <div class="stat-value">{{ $pendingReservations }}</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('superadmin.reservations.index', ['status' => 'pending']) }}" class="text-warning">
                        Review Now <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-teal h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrap icon-teal">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <div class="stat-label" style="color:#14b8a6;">Today's Reservations</div>
                        <div class="stat-value">{{ $todaysReservations->count() }}</div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('superadmin.reservations.index', ['date' => date('Y-m-d')]) }}" style="color:#14b8a6;">
                        View Today <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts & Campus Overview --}}
    <div class="row mb-4">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Users by Role</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div style="height: 200px; position: relative;">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-3">
                            <span class="badge bg-info d-block mb-1">Professor</span>
                            <h6 class="mb-0">{{ $totalTeachers }}</h6>
                        </div>
                        <div class="col-3">
                            <span class="badge bg-success d-block mb-1">Student</span>
                            <h6 class="mb-0">{{ $totalStudents }}</h6>
                        </div>
                        <div class="col-3">
                            <span class="badge bg-warning d-block mb-1">Super</span>
                            <h6 class="mb-0">1</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Campus Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Campus</th>
                                    <th>Code</th>
                                    <th class="text-center">Professors</th>
                                    <th class="text-center">Students</th>
                                    <th class="text-center">Rooms</th>
                                    <th class="text-center">Reservations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campuses as $campus)
                                <tr>
                                    <td><strong>{{ $campus->campus_name }}</strong></td>
                                    <td><code>{{ $campus->campus_code }}</code></td>
                                    <td class="text-center">{{ $campus->teachers_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->students_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->classrooms_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->reservations_count ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="fas fa-university me-2"></i>No campuses found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Recent Activity --}}
    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-history me-2"></i>Recent Users</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentUsers = App\Models\User::latest()->take(5)->get();
                                @endphp
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-muted small">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $user->role === 'super_admin' ? 'warning' :
                                            ($user->role === 'admin'      ? 'primary' :
                                            ($user->role === 'teacher'    ? 'info' : 'success'))
                                        }}">
                                            {{ $user->role === 'teacher' ? 'Professor' : ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Today's Reservations</h5>
                </div>
                <div class="card-body p-0">
                    @if($todaysReservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Room</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todaysReservations as $reservation)
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            {{ date('h:i A', strtotime($reservation->start_time)) }}
                                            – {{ date('h:i A', strtotime($reservation->end_time)) }}
                                        </small>
                                    </td>
                                    <td>{{ $reservation->classroom->room_number ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($reservation->purpose, 30) }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $reservation->status === 'approved' ? 'success' :
                                            ($reservation->status === 'pending' ? 'warning' : 'danger')
                                        }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                        <p class="mb-0">No reservations for today</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Professors', 'Students'],
            datasets: [{
                data: [{{ $totalAdmins }}, {{ $totalTeachers }}, {{ $totalStudents }}],
                backgroundColor: ['#4e73df', '#36b9cc', '#1a7a3c'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#145e2e'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        },
    });
</script>
@endpush