@php
$user = auth()->user();
$profileImageUrl = $user->profile_image
    ? asset('storage/' . $user->profile_image)
    : asset('assets/img/default-avatar.png');

// Get pending counts for notifications
$pendingUsersCount = DB::table('users')->where('status', 'pending')->count();
$pendingReservationsCount = DB::table('reservations')->where('status', 'pending')->count();
$unassignedAdminsCount = DB::table('users')
    ->where('role', 'admin')
    ->whereNull('campus_id')
    ->count();
@endphp

<style>
:root {
    --primary-green: #2ecc71;
    --primary-green-dark: #27ae60;
    --light-green: #d4edda;
    --soft-green: #f0faf4;
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
    background: linear-gradient(135deg, #ffffff 0%, #f0faf4 100%);
    padding: 25px 20px;
    border-right: 1px solid rgba(46, 204, 113, 0.15);
    box-shadow: 4px 0 10px rgba(46, 204, 113, 0.05);
    overflow-y: auto;
    transition: var(--transition);
    z-index: 1000;
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
    display: flex;
    flex-direction: column;
    align-items: center;
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
    background-color: #28a745;
    border: 2px solid var(--white);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
}

.profile-name {
    color: var(--text-dark);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 5px;
    text-align: center;
    word-break: break-word;
}

.profile-email {
    color: var(--text-soft);
    font-size: 0.9rem;
    margin-bottom: 15px;
    word-break: break-word;
    text-align: center;
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
    text-align: center;
    width: 100%;
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

/* Notification Badge */
.notification-badge {
    background-color: #e74c3c;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: auto;
    position: relative;
    z-index: 1;
}

/* Divider */
.nav-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--primary-green), transparent);
    margin: 20px 0;
    opacity: 0.3;
}

/* Section Title */
.nav-section-title {
    color: var(--primary-green-dark);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 20px;
    margin: 15px 0 10px;
}

/* Footer Menu */
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

<div class="sidebar" id="superadminSidebar">
    <!-- Profile Section -->
    <div class="profile-card">
        <div class="profile-image-wrapper">
            <img src="{{ $profileImageUrl }}" class="profile-image" alt="{{ $user->name }}">
            <span class="online-indicator"></span>
        </div>
        <h3 class="profile-name">{{ $user->name }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        <span class="role-badge" style="margin-top: 10px;">
            <i class="fas fa-crown me-1"></i> Super Admin
        </span>
    </div>

    <!-- Main Navigation Menu -->
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- User Management Section -->
        <div class="nav-section-title">USER MANAGEMENT</div>
        
        <li class="nav-item">
            <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.index') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span>All Users</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('superadmin.users.create') }}" class="nav-link {{ request()->routeIs('superadmin.users.create') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <span>Create User</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Campus Management Section -->
        <div class="nav-section-title">CAMPUS MANAGEMENT</div>
       
        <li class="nav-item">
            <a href="{{ route('superadmin.campuses.index') }}" class="nav-link {{ request()->routeIs('superadmin.campuses.*') ? 'active' : '' }}">
                <i class="fas fa-university"></i>
                <span>Campuses</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.departments.index') }}" class="nav-link {{ request()->routeIs('superadmin.departments.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Departments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('superadmin.courses.index') }}" 
            class="nav-link {{ request()->routeIs('superadmin.courses.*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>Courses</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Reservations Section -->
        <div class="nav-section-title">RESERVATIONS</div>
        
        <li class="nav-item">
            <a href="{{ route('superadmin.reservations.index') }}" class="nav-link {{ request()->routeIs('superadmin.reservations.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>All Reservations</span>
                @if($pendingReservationsCount > 0)
                    <span class="notification-badge">{{ $pendingReservationsCount }}</span>
                @endif
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Account Section -->
        <div class="nav-section-title">ACCOUNT</div>
        
        <li class="nav-item">
            <a href="{{ route('superadmin.profile.edit') }}" class="nav-link {{ request()->routeIs('superadmin.profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>Edit Profile</span>
            </a>
        </li>
    </ul>

    <!-- Footer Menu -->
    <ul class="nav-menu nav-footer">
        <div class="nav-divider"></div>
        
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        
        <li class="nav-item">
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
        </li>
    </ul>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-headset me-2" style="color: var(--primary-green);"></i>
                    Help & Support
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-crown" style="font-size: 3rem; color: var(--primary-green);"></i>
                </div>
                
                <h6>Super Admin Quick Guide:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2" style="color: var(--primary-green);"></i>
                        <strong>User Management:</strong> Create, edit, or deactivate user accounts
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2" style="color: var(--primary-green);"></i>
                        <strong>Campus Management:</strong> Add new campuses and assign admins
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2" style="color: var(--primary-green);"></i>
                        <strong>Reservations:</strong> Monitor and manage all room reservations
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle me-2" style="color: var(--primary-green);"></i>
                        <strong>Reports:</strong> View system-wide analytics and statistics
                    </li>
                </ul>

                <hr>

                <div class="text-center">
                    <p class="mb-2"><strong>Need assistance?</strong></p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i> superadmin@cms.edu<br>
                        <i class="fas fa-phone me-2"></i> (123) 456-7890
                    </p>
                    <p class="text-muted small">Available 24/7 for emergency support</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-success" onclick="window.location.href='mailto:superadmin@cms.edu'">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Toggle Button (for responsive) -->
<button class="sidebar-toggle d-lg-none" id="sidebarToggle" style="display: none;">
    <i class="fas fa-bars"></i>
</button>

@push('scripts')
<script>
$(document).ready(function() {
    // Mobile sidebar toggle
    $('#sidebarToggle').click(function() {
        $('.sidebar').toggleClass('active');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).click(function(event) {
        if ($(window).width() <= 768) {
            if (!$(event.target).closest('.sidebar').length && !$(event.target).closest('#sidebarToggle').length) {
                $('.sidebar').removeClass('active');
            }
        }
    });
});
</script>
@endpush