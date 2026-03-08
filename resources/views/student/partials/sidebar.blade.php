@php
$user = auth()->user();
$profileImageUrl = $user->profile_image
    ? asset('storage/' . $user->profile_image)
    : asset('images/default_profile.png');
@endphp

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
}

.sidebar {
    position: fixed;
    left: 0;
    top: 70px;
    bottom: 0;
    width: 280px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fff9 100%);
    padding: 25px 20px;
    border-right: 1px solid rgba(46, 204, 113, 0.15);
    box-shadow: 4px 0 10px rgba(46, 204, 113, 0.05);
    overflow-y: auto;
    transition: var(--transition);
}

.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: var(--soft-green);
}

.sidebar::-webkit-scrollbar-thumb {
    background: var(--primary-green);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: var(--primary-green-dark);
}

/* Profile Card */
.profile-card {
    background: linear-gradient(145deg, var(--white), var(--soft-green));
    border-radius: 20px;
    padding: 25px 20px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-sm);
    border: 1px solid rgba(46, 204, 113, 0.1);
    transition: var(--transition);
}

.profile-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.profile-image-wrapper {
    position: relative;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--white);
    box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
    transition: var(--transition);
}

.profile-image:hover {
    transform: scale(1.05);
    border-color: var(--primary-green);
}

.online-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 15px;
    height: 15px;
    background-color: var(--primary-green);
    border: 2px solid var(--white);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(46, 204, 113, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
    }
}

.profile-name {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.profile-email {
    color: var(--text-soft);
    font-size: 0.9rem;
    margin-bottom: 15px;
    word-break: break-word;
}

.role-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
    color: var(--white);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
    text-transform: uppercase;
}

/* Navigation Menu */
.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 8px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: 12px;
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.nav-link i {
    width: 24px;
    font-size: 1.2rem;
    margin-right: 12px;
    color: var(--primary-green);
    transition: var(--transition);
}

.nav-link:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, rgba(46, 204, 113, 0.1), transparent);
    transition: var(--transition);
    z-index: 0;
}

.nav-link:hover {
    background: var(--soft-green);
    transform: translateX(5px);
}

.nav-link:hover:before {
    width: 100%;
}

.nav-link:hover i {
    transform: scale(1.1);
    color: var(--primary-green-dark);
}

.nav-link span {
    position: relative;
    z-index: 1;
}

.nav-link.active {
    background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
    color: var(--white);
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
}

.nav-link.active i {
    color: var(--white);
}

.nav-link.active:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.5);
}

/* Divider */
.nav-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--primary-green), transparent);
    margin: 20px 0;
    opacity: 0.3;
}

/* Footer Menu (optional) */
.nav-footer {
    margin-top: auto;
    padding-top: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 240px;
        transform: translateX(-100%);
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
}

/* Animation for active state */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-link.active {
    animation: slideIn 0.3s ease;
}
</style>

<div class="sidebar">
    <!-- Profile Section -->
    <div class="profile-card">
        <div class="profile-image-wrapper">
            <img src="{{ $profileImageUrl }}" class="profile-image" alt="{{ $user->name }}">
            <span class="online-indicator"></span>
        </div>
        <h3 class="profile-name">{{ $user->name }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        <span class="role-badge">Student</span>
    </div>

    <!-- Navigation Menu -->
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.schedule') }}" class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Classroom Schedule</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ route('student.teachers') }}" class="nav-link {{ request()->routeIs('student.teachers') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-user"></i>
                <span>Professors</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </li>
    </ul>
</div>