@php
$user = auth()->user();

$profileImageUrl = $user->profile_image
    ? asset('storage/' . $user->profile_image)
    : asset('assets/img/default-avatar.png');

$displayName = $user->name ?? $user->username ?? 'Administrator';
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
    --sidebar-width: 280px;
}

.sidebar {
    position: fixed;
    left: 0;
    top: 70px;
    bottom: 0;
    width: var(--sidebar-width);
    background: linear-gradient(135deg, #ffffff 0%, #f8fff9 100%);
    padding: 25px 20px;
    border-right: 1px solid rgba(46, 204, 113, 0.15);
    box-shadow: 4px 0 10px rgba(46, 204, 113, 0.05);
    overflow-y: auto;
    transition: var(--transition);
    z-index: 1000;
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar.collapsed .profile-name,
.sidebar.collapsed .profile-email,
.sidebar.collapsed .role-badge,
.sidebar.collapsed .department-badge,
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .nav-section-title {
    display: none;
}

.sidebar.collapsed .nav-link {
    padding: 12px;
    justify-content: center;
}

.sidebar.collapsed .nav-link i {
    margin-right: 0;
    font-size: 1.4rem;
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

.sidebar.collapsed .profile-image-wrapper {
    width: 50px;
    height: 50px;
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

.sidebar.collapsed .profile-image {
    width: 50px;
    height: 50px;
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

/* Department Badge */
.department-badge {
    display: inline-block;
    background: var(--soft-green);
    color: var(--primary-green-dark);
    padding: 5px 15px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 10px;
    border: 1px solid var(--primary-green);
    margin-bottom: 10px;
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
    
    .main-content {
        margin-left: 0 !important;
    }
}

/* Sidebar Toggle Button */
.sidebar-toggle-btn {
    position: fixed;
    left: 20px;
    bottom: 20px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-green);
    color: white;
    border: none;
    box-shadow: var(--shadow-md);
    cursor: pointer;
    z-index: 1001;
    display: none;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.sidebar-toggle-btn:hover {
    background: var(--primary-green-dark);
    transform: scale(1.1);
}

@media (min-width: 769px) {
    .sidebar-toggle-btn {
        display: flex;
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

<div class="sidebar" id="adminSidebar">
    <!-- Profile Section -->
    <div class="profile-card">
        <div class="profile-image-wrapper">
            <img src="{{ $profileImageUrl }}" class="profile-image" alt="{{ $displayName }}">
            <span class="online-indicator"></span>
        </div>
        <h3 class="profile-name">{{ $displayName }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        @if($user->role)
            <span class="department-badge">{{ ucfirst($user->role) }}</span>
        @endif
        <span class="role-badge">{{ $user->campus }}</span>
    </div>

    <!-- Main Navigation Menu -->
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Management Section -->
        <div class="nav-section-title">MANAGEMENT</div>
        
        <li class="nav-item">
            <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Professors</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Subjects</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.classrooms.index') }}" class="nav-link {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
                <i class="fas fa-school"></i>
                <span>Classrooms</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Scheduling Section -->
        <div class="nav-section-title">SCHEDULING</div>
        
        <li class="nav-item">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Professor Schedules</span>
                @php
                    $pendingSchedules = DB::table('schedules')
                        ->where('status', 'pending')
                        ->count();
                @endphp
                @if($pendingSchedules > 0)
                    <span class="notification-badge">{{ $pendingSchedules }}</span>
                @endif
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.assignments.index') }}" class="nav-link {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span>Teacher Assignments</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.time-slots.index') }}" class="nav-link {{ request()->routeIs('admin.time-slots.*') ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Time Slots</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Reservations Section -->
        <div class="nav-section-title">RESERVATIONS</div>
        
        <li class="nav-item">
            <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i>
                <span>Room Reservations</span>
                @php
                    $pendingReservations = DB::table('reservations')  // ✅ correct table name
                        ->where('status', 'pending')
                        ->count();
                @endphp
                @if($pendingReservations > 0)
                    <span class="notification-badge">{{ $pendingReservations }}</span>
                @endif
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Account Section -->
        <div class="nav-section-title">ACCOUNT</div>
        
        <li class="nav-item">
            <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
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

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
    <i class="fas fa-chevron-left" id="toggleIcon"></i>
</button>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Help & Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-headset" style="font-size: 3rem; color: var(--primary-green);"></i>
                </div>
                
                <h6>Admin Quick Guide:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Manage professors, students, subjects, and classrooms
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Create and manage teaching schedules
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Assign subjects to professors
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Approve or reject room reservations
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Configure time slots for classes
                    </li>
                </ul>

                <hr>

                <div class="text-center">
                    <p class="mb-2"><strong>Need assistance?</strong></p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i> admin@cms.edu<br>
                        <i class="fas fa-phone me-2"></i> (123) 456-7890
                    </p>
                    <p class="text-muted small">Available 24/7 for admin support</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sidebar toggle functionality
    const sidebar = $('#adminSidebar');
    const toggleBtn = $('#sidebarToggleBtn');
    const toggleIcon = $('#toggleIcon');
    
    // Check localStorage for sidebar state
    if (localStorage.getItem('adminSidebarCollapsed') === 'true') {
        sidebar.addClass('collapsed');
        toggleIcon.removeClass('fa-chevron-left').addClass('fa-chevron-right');
    }
    
    toggleBtn.click(function() {
        sidebar.toggleClass('collapsed');
        
        if (sidebar.hasClass('collapsed')) {
            toggleIcon.removeClass('fa-chevron-left').addClass('fa-chevron-right');
            localStorage.setItem('adminSidebarCollapsed', 'true');
        } else {
            toggleIcon.removeClass('fa-chevron-right').addClass('fa-chevron-left');
            localStorage.setItem('adminSidebarCollapsed', 'false');
        }
    });
    
    // Mobile sidebar toggle
    $('#sidebarToggle').click(function() {
        sidebar.toggleClass('active');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).click(function(event) {
        if ($(window).width() <= 768) {
            if (!$(event.target).closest('.sidebar').length && !$(event.target).closest('#sidebarToggle').length) {
                sidebar.removeClass('active');
            }
        }
    });
});
</script>