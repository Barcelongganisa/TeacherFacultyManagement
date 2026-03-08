@extends('superadmin.layouts.app')

@section('title', 'Dashboard - Super Admin')

<style>
    #mainContent > div > div:nth-child(1) > div.col-12 > div > div.card-body.p-0{
        margin-top: 10px !important; 
    }
</style>

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-tachometer-alt me-2"></i>Super Admin Dashboard</h1>
            <p class="text-muted">Global system overview and management</p>
        </div>
        <div class="header-actions">
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
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-purple h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                Total Campuses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCampuses }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('superadmin.campuses.index') }}" class="small text-purple">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('superadmin.users.index') }}" class="small text-primary">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingReservations }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('superadmin.reservations.index', ['status' => 'pending']) }}" class="small text-warning">Review Now <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todaysReservations->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('superadmin.reservations.index', ['date' => date('Y-m-d')]) }}" class="small text-success">View Today <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics by Role -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Users by Role</h5>
                </div>
                <div class="card-body">
                    <!-- Add this wrapper div with fixed height -->
                    <div style="height: 200px; position: relative;">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-3">
                                <span class="badge bg-primary">Admin</span>
                                <h6>{{ $totalAdmins }}</h6>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-info">Teacher</span>
                                <h6>{{ $totalTeachers }}</h6>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-success">Student</span>
                                <h6>{{ $totalStudents }}</h6>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-warning">Super</span>
                                <h6>1</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card">
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
                                    <th class="text-center">Admins</th>
                                    <th class="text-center">Teachers</th>
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
                                    <td class="text-center">{{ $campus->admins_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->teachers_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->students_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->classrooms_count ?? 0 }}</td>
                                    <td class="text-center">{{ $campus->reservations_count ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No campuses found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-history me-2"></i>Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-{{ $user->role === 'super_admin' ? 'warning' : ($user->role === 'admin' ? 'primary' : ($user->role === 'teacher' ? 'info' : 'success')) }}">{{ ucfirst($user->role) }}</span></td>
                                    <td><span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">{{ $user->status }}</span></td>
                                    <td><small>{{ $user->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Today's Reservations</h5>
                </div>
                <div class="card-body">
                    @if($todaysReservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                                    <td><small>{{ date('h:i A', strtotime($reservation->start_time)) }} - {{ date('h:i A', strtotime($reservation->end_time)) }}</small></td>
                                    <td>{{ $reservation->classroom->room_number ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($reservation->purpose, 30) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $reservation->status === 'approved' ? 'success' : ($reservation->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>No reservations for today</p>
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
    // User Role Chart
    const ctx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Teachers', 'Students'],
            datasets: [{
                data: [{{ $totalAdmins }}, {{ $totalTeachers }}, {{ $totalStudents }}],
                backgroundColor: ['#4e73df', '#36b9cc', '#1cc88a'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#17a673'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        },
    });
</script>
@endpush