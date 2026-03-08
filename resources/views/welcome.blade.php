<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Monitoring System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #1b5e20;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .hero-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(46,125,50,0.06);
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .hero-icon {
            font-size: 80px;
            color: #2e7d32;
            margin-bottom: 20px;
        }

        h1 {
            color: #1b5e20;
            font-weight: 700;
            font-size: 42px;
            margin-bottom: 15px;
        }

        .subtitle {
            color: #2e7d32;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 40px 0;
            text-align: left;
        }

        .feature {
            background: #f8fff9;
            border-radius: 10px;
            padding: 15px;
            border-left: 4px solid #81c784;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-icon {
            font-size: 24px;
            color: #4caf50;
        }

        .feature-text {
            font-size: 14px;
            color: #2e7d32;
        }

        .cta-buttons {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-login {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 12px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.18s ease-in-out;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            background: #256b2a;
            box-shadow: 0 10px 20px rgba(46,125,50,0.12);
        }

        .btn-docs {
            background: #fff;
            border: 2px solid #2e7d32;
            padding: 10px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            color: #2e7d32;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.18s ease-in-out;
        }

        .btn-docs:hover {
            background: #e8f5e9;
            transform: translateY(-3px);
        }

        @media (max-width: 576px) {
            .hero-container { padding: 40px 20px; }
            h1 { font-size: 36px; }
            .features { grid-template-columns: 1fr; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="hero-container">
        <div class="hero-icon"><i class="fas fa-graduation-cap"></i></div>
        <h1>CMS</h1>
        <p class="subtitle">Classroom Monitoring System</p>
        <p style="color: #555; margin-bottom: 25px;">
            A comprehensive solution for managing teachers, subjects, classrooms, and teaching schedules in educational institutions.
        </p>

        <div class="features">
            <div class="feature"><span class="feature-icon"><i class="fas fa-users-cog"></i></span><span class="feature-text">Professor Management</span></div>
            <div class="feature"><span class="feature-icon"><i class="fas fa-book"></i></span><span class="feature-text">Subject Management</span></div>
            <div class="feature"><span class="feature-icon"><i class="fas fa-door-open"></i></span><span class="feature-text">Classroom Management</span></div>
            <div class="feature"><span class="feature-icon"><i class="fas fa-calendar-alt"></i></span><span class="feature-text">Schedule Management</span></div>
            <div class="feature"><span class="feature-icon"><i class="fas fa-tasks"></i></span><span class="feature-text">Assignment Tracking</span></div>
            <div class="feature"><span class="feature-icon"><i class="fas fa-lock"></i></span><span class="feature-text">Secure Access</span></div>
        </div>

       <div class="cta-buttons">
            @auth
                @php
                    $dashboardRoute = match(auth()->user()->role) {
                        'student' => 'student.dashboard',
                        'teacher' => 'teacher.dashboard',
                        'admin' => 'admin.dashboard',
                        'superadmin' => 'superadmin.dashboard',
                        default => 'dashboard'
                    };
                @endphp

                <a href="{{ route($dashboardRoute) }}" class="btn-login">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @else
                <button onclick="openLoginModal()" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            @endauth
        </div>
    </div>

    {{-- Login Modal --}}
    <x-login-modal />

    {{-- Forgot Password Modal --}}
    <x-forgot-password-modal />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.openLoginModal = function() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
    </script>
</body>
</html>