@extends('superadmin.layouts.app')

@section('title', 'Reports & Analytics - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-chart-bar me-2"></i>Reports & Analytics</h1>
            <p class="text-muted">System-wide statistics and insights</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="exportReport('pdf')">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </button>
            <button class="btn btn-success" onclick="exportReport('excel')">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-calendar-alt me-2"></i>Date Range</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.reports') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i>Update Reports
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Users</h6>
                            <h3 class="text-white mb-0">{{ $totalUsers ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-up me-1"></i>{{ $newUsersThisMonth ?? 0 }} new this month
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Reservations</h6>
                            <h3 class="text-white mb-0">{{ $totalReservations ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                    <small class="text-white-50">
                        <i class="fas fa-arrow-up me-1"></i>{{ $reservationsThisMonth ?? 0 }} this month
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Classrooms</h6>
                            <h3 class="text-white mb-0">{{ $totalClassrooms ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-door-open fa-3x opacity-50"></i>
                    </div>
                    <small class="text-white-50">
                        {{ $activeClassrooms ?? 0 }} active
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Campuses</h6>
                            <h3 class="text-white mb-0">{{ $totalCampuses ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-university fa-3x opacity-50"></i>
                    </div>
                    <small class="text-white-50">
                        {{ $activeCampuses ?? 0 }} active
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>User Distribution by Role</h5>
                </div>
                <div class="card-body">
                    <canvas id="userRoleChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line me-2"></i>Reservations Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="reservationsTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-university me-2"></i>Campus Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Campus</th>
                                    <th class="text-center">Users</th>
                                    <th class="text-center">Teachers</th>
                                    <th class="text-center">Students</th>
                                    <th class="text-center">Rooms</th>
                                    <th class="text-center">Reservations</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campusStats ?? [] as $stat)
                                <tr>
                                    <td>{{ $stat->campus_name }}</td>
                                    <td class="text-center">{{ $stat->total_users ?? 0 }}</td>
                                    <td class="text-center">{{ $stat->teachers_count ?? 0 }}</td>
                                    <td class="text-center">{{ $stat->students_count ?? 0 }}</td>
                                    <td class="text-center">{{ $stat->classrooms_count ?? 0 }}</td>
                                    <td class="text-center">{{ $stat->reservations_count ?? 0 }}</td>
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
                    <h5><i class="fas fa-calendar-alt me-2"></i>Reservation Status Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-center">Count</th>
                                    <th class="text-center">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = ($reservationStats['pending'] ?? 0) + ($reservationStats['approved'] ?? 0) + ($reservationStats['rejected'] ?? 0) + ($reservationStats['cancelled'] ?? 0);
                                @endphp
                                <tr>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td class="text-center">{{ $reservationStats['pending'] ?? 0 }}</td>
                                    <td class="text-center">{{ $total > 0 ? round(($reservationStats['pending'] ?? 0) / $total * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Approved</span></td>
                                    <td class="text-center">{{ $reservationStats['approved'] ?? 0 }}</td>
                                    <td class="text-center">{{ $total > 0 ? round(($reservationStats['approved'] ?? 0) / $total * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td class="text-center">{{ $reservationStats['rejected'] ?? 0 }}</td>
                                    <td class="text-center">{{ $total > 0 ? round(($reservationStats['rejected'] ?? 0) / $total * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">Cancelled</span></td>
                                    <td class="text-center">{{ $reservationStats['cancelled'] ?? 0 }}</td>
                                    <td class="text-center">{{ $total > 0 ? round(($reservationStats['cancelled'] ?? 0) / $total * 100, 1) : 0 }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Users -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-trophy me-2"></i>Most Active Users</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Campus</th>
                            <th class="text-center">Reservations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeUsers ?? [] as $index => $user)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/img/default-avatar.png' ) }}" 
                                         class="rounded-circle me-2" width="30" height="30">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small>{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-{{ $user->role === 'teacher' ? 'info' : 'success' }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->campus->campus_name ?? 'N/A' }}</td>
                            <td class="text-center"><strong>{{ $user->reservations_count }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Role Chart
    const roleCtx = document.getElementById('userRoleChart').getContext('2d');
    new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Teachers', 'Students'],
            datasets: [{
                data: [{{ $adminCount ?? 0 }}, {{ $teacherCount ?? 0 }}, {{ $studentCount ?? 0 }}],
                backgroundColor: ['#4e73df', '#36b9cc', '#1cc88a'],
                hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#17a673'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Reservations Trend Chart
    const trendCtx = document.getElementById('reservationsTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels ?? []) !!},
            datasets: [{
                label: 'Reservations',
                data: {!! json_encode($trendData ?? []) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Export function
    function exportReport(type) {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '{{ route("superadmin.reports") }}/export?type=' + type + '&' + params.toString();
    }
</script>
@endpush