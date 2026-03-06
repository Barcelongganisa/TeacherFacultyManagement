<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - CMS Admin Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2ecc71;
            --primary-green-dark: #27ae60;
            --light-green: #d4edda;
            --soft-green: #e8f8f5;
            --white: #ffffff;
            --text-dark: #2c3e50;
            --text-soft: #6c757d;
            --shadow-sm: 0 2px 4px rgba(46, 204, 113, 0.1);
            --shadow-md: 0 4px 6px rgba(46, 204, 113, 0.15);
            --transition: all 0.3s ease;
            --sidebar-width: 280px;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            box-shadow: var(--shadow-md);
            height: 70px;
        }

        .navbar-brand {
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .navbar-brand i {
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(46, 204, 113, 0.1);
            padding: 1.25rem 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h5 {
            margin: 0;
            color: var(--text-dark);
            font-weight: 600;
        }

        .card-header h5 i {
            color: var(--primary-green);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green));
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            background: transparent;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--primary-green);
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: var(--primary-green-dark);
        }

        .btn-danger {
            background: #e74c3c;
            border: none;
        }

        .btn-warning {
            background: #f39c12;
            border: none;
            color: white;
        }

        .btn-info {
            background: #3498db;
            border: none;
            color: white;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--primary-green);
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(46, 204, 113, 0.1);
        }

        .table tbody tr:hover {
            background: var(--soft-green);
        }

        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge.bg-success {
            background: var(--primary-green) !important;
        }

        .badge.bg-danger {
            background: #e74c3c !important;
        }

        .badge.bg-warning {
            background: #f39c12 !important;
        }

        .badge.bg-info {
            background: #3498db !important;
        }

        /* Forms */
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: var(--light-green);
            color: var(--primary-green-dark);
            border-left: 4px solid var(--primary-green);
        }

        .alert-danger {
            background: #fdeaea;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }

        .alert-warning {
            background: #fef5e7;
            color: #f39c12;
            border-left: 4px solid #f39c12;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--text-soft);
            margin-bottom: 0;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            color: var(--primary-green);
            border: 1px solid rgba(46, 204, 113, 0.2);
            padding: 0.5rem 1rem;
        }

        .page-link:hover {
            background: var(--soft-green);
            color: var(--primary-green-dark);
            border-color: var(--primary-green);
        }

        .page-item.active .page-link {
            background: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
                padding: 20px !important;
            }

            .main-content.expanded {
                margin-left: 0 !important;
            }

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .table thead th {
                font-size: 0.8rem;
                padding: 0.75rem;
            }

            .table tbody td {
                padding: 0.75rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.5s ease;
        }

        /* Utility Classes */
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .text-success-custom {
            color: var(--primary-green);
        }

        .bg-soft-green {
            background: var(--soft-green);
        }

        .border-green {
            border-color: var(--primary-green) !important;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .spinner-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--light-green);
            border-top-color: var(--primary-green);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('admin.partials.sidebar')
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-3 d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-graduation-cap"></i> CMS Admin Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                @yield('page-header')
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="loadingSpinner">
        <div class="spinner"></div>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        // Sidebar toggle
        $('#sidebarToggle').click(function() {
            $('#adminSidebar').toggleClass('active');
        });

        // Show loading spinner on AJAX requests
        $(document).ajaxStart(function() {
            $('#loadingSpinner').addClass('active');
        }).ajaxStop(function() {
            $('#loadingSpinner').removeClass('active');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>