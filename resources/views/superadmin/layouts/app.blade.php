<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - CMS Super Admin Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #6f42c1;
            --primary-purple-dark: #5a359a;
            --primary-orange: #fd7e14;
            --light-purple: #e2d9f3;
            --soft-purple: #f3e8ff;
            --white: #ffffff;
            --text-dark: #2c3e50;
            --text-soft: #6c757d;
            --shadow-sm: 0 2px 4px rgba(111, 66, 193, 0.1);
            --shadow-md: 0 4px 6px rgba(111, 66, 193, 0.15);
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
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-dark));
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

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
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
            border-bottom: 1px solid rgba(111, 66, 193, 0.1);
            padding: 1.25rem 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h5 {
            margin: 0;
            color: var(--text-dark);
            font-weight: 600;
        }

        .card-header h5 i {
            color: var(--primary-purple);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-purple), var(--primary-purple-dark));
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-purple-dark), var(--primary-purple));
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            border: 1px solid var(--primary-purple);
            color: var(--primary-purple);
            background: transparent;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-light {
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: white;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .btn-success {
            background: #28a745;
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
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
            border-bottom: 2px solid var(--primary-purple);
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(111, 66, 193, 0.1);
        }

        .table tbody tr:hover {
            background: var(--soft-purple);
        }

        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge.bg-success {
            background: #28a745 !important;
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

        .badge.bg-purple {
            background: var(--primary-purple) !important;
            color: white;
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
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
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

        .page-header h1 i {
            color: var(--primary-purple);
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
            color: var(--primary-purple);
            border: 1px solid rgba(111, 66, 193, 0.2);
            padding: 0.5rem 1rem;
        }

        .page-link:hover {
            background: var(--soft-purple);
            color: var(--primary-purple-dark);
            border-color: var(--primary-purple);
        }

        .page-item.active .page-link {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
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

        .text-purple {
            color: var(--primary-purple);
        }

        .bg-soft-purple {
            background: var(--soft-purple);
        }

        .border-purple {
            border-color: var(--primary-purple) !important;
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
            border: 5px solid var(--light-purple);
            border-top-color: var(--primary-purple);
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
    @include('superadmin.partials.sidebar')
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-3 d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <i class="fas fa-crown"></i> CMS Super Admin Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('superadmin.profile.edit') }}">
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
            $('#superadminSidebar').toggleClass('active');
            $('#mainContent').toggleClass('expanded');
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

        // Handle active navigation links based on current URL
        $(document).ready(function() {
            var currentUrl = window.location.pathname;
            $('.sidebar-nav .nav-link').each(function() {
                if ($(this).attr('href') === currentUrl) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>