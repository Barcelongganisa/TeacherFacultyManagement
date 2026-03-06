<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
    
    <style>
        :root {
            --primary-green: #2ecc71;
            --primary-green-dark: #27ae60;
            --primary-green-light: #a3e4d7;
            --soft-green: #e8f8f5;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --text-dark: #2c3e50;
            --text-soft: #6c757d;
            --shadow-sm: 0 2px 4px rgba(46, 204, 113, 0.1);
            --shadow-md: 0 4px 6px rgba(46, 204, 113, 0.15);
            --shadow-lg: 0 10px 15px rgba(46, 204, 113, 0.2);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9f5e9 100%);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Modern Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            padding: 0.8rem 2rem;
            box-shadow: var(--shadow-lg);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--white) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 12px;
            transition: var(--transition);
        }

        .navbar-brand:hover i {
            transform: rotate(360deg);
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .welcome-text {
            color: var(--white);
            font-weight: 500;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-text i {
            margin-right: 8px;
            color: #f1c40f;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white) !important;
            border-radius: 50px;
            padding: 8px 20px !important;
            font-weight: 500;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .logout-btn i {
            margin-right: 8px;
            transition: var(--transition);
        }

        .logout-btn:hover i {
            transform: translateX(3px);
        }

        /* Main Content Area */
        .main-content {
            margin-left: 300px;
            margin-top: 80px;
            padding: 30px;
            min-height: calc(100vh - 80px);
            transition: var(--transition);
            position: relative;
        }

        /* Content Cards Styling */
        .content-card {
            background: var(--white);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(46, 204, 113, 0.1);
            transition: var(--transition);
            height: 100%;
        }

        .content-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-5px);
            border-color: var(--primary-green);
        }

        .card-header-custom {
            border-bottom: 2px solid var(--soft-green);
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-custom h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .card-header-custom i {
            color: var(--primary-green);
            font-size: 1.5rem;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--soft-green);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .page-header .breadcrumb-item {
            color: var(--text-soft);
            font-size: 0.95rem;
        }

        .page-header .breadcrumb-item.active {
            color: var(--primary-green);
            font-weight: 500;
        }

        .page-header .breadcrumb-item + .breadcrumb-item::before {
            color: var(--primary-green-light);
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--white), var(--soft-green));
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(46, 204, 113, 0.1);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.8rem;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.2;
        }

        .stat-info p {
            color: var(--text-soft);
            margin: 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-modern {
                padding: 0.5rem 1rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-brand i {
                font-size: 1.5rem;
            }

            .welcome-text {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid var(--soft-green);
            border-radius: 50%;
            border-top-color: var(--primary-green);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alert Messages */
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            animation: slideInDown 0.5s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid var(--primary-green);
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--soft-green);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-green);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-green-dark);
        }
    </style>
</head>
<body>
    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('student.dashboard') }}">
                <i class="fas fa-graduation-cap"></i>
                <span>CRS Student Portal</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="nav-user-info">
                            <span class="welcome-text">
                                <i class="fas fa-user-circle"></i>
                                Welcome back, {{ Auth::user()->name }}
                            </span>
                            <a class="nav-link logout-btn" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Include Sidebar -->
    @include('student.partials.sidebar')

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header (can be overridden in child views) -->
        @hasSection('page-header')
            <div class="page-header">
                <h1>@yield('page-header')</h1>
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif
            </div>
        @endif

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-custom alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-custom alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content Yield -->
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Add loading state for AJAX calls -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Add active state to current nav link
            const currentLocation = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if(link.getAttribute('href') === currentLocation) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>