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
    z-index: 1000;
}

.sidebar::-webkit-scrollbar { width: 6px; }
.sidebar::-webkit-scrollbar-track { background: var(--soft-green); }
.sidebar::-webkit-scrollbar-thumb { background: var(--primary-green); border-radius: 10px; }
.sidebar::-webkit-scrollbar-thumb:hover { background: var(--primary-green-dark); }

/* ── Profile Card ── */
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
    background-color: var(--primary-green);
    border: 2px solid var(--white);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
    70%  { box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
    100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
}

.profile-name {
    color: var(--text-dark);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    text-align: center;
    word-break: break-word;
}

.profile-email {
    color: var(--text-soft);
    font-size: 0.85rem;
    margin-bottom: 12px;
    word-break: break-word;
    text-align: center;
}

.role-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-green), var(--primary-green-dark));
    color: var(--white);
    padding: 6px 20px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
    text-transform: uppercase;
    text-align: center;
    width: 100%;
    margin-top: 8px;
}

/* ── Navigation ── */
.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 5px;
}

.nav-section-title {
    color: var(--primary-green-dark);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0 20px;
    margin: 15px 0 8px;
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
    font-size: 1.1rem;
    margin-right: 12px;
    color: var(--primary-green);
    transition: var(--transition);
    flex-shrink: 0;
}

.nav-link:before {
    content: '';
    position: absolute;
    left: 0; top: 0;
    height: 100%; width: 0;
    background: linear-gradient(90deg, rgba(46, 204, 113, 0.1), transparent);
    transition: var(--transition);
    z-index: 0;
}

.nav-link:hover {
    background: var(--soft-green);
    transform: translateX(5px);
    color: var(--text-dark);
}

.nav-link:hover:before { width: 100%; }

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
    animation: slideIn 0.3s ease;
}

.nav-link.active i { color: var(--white); }

.nav-link.active:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.5);
    color: var(--white);
}

/* ── Divider ── */
.nav-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--primary-green), transparent);
    margin: 15px 0;
    opacity: 0.3;
}

/* ── Footer Menu ── */
.nav-footer {
    margin-top: 10px;
    padding-top: 5px;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .sidebar {
        width: 240px;
        transform: translateX(-100%);
        z-index: 1025;
    }
    .sidebar.active { transform: translateX(0); }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-10px); }
    to   { opacity: 1; transform: translateX(0); }
}
</style>

<div class="sidebar" id="studentSidebar">

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-image-wrapper">
            <img src="{{ $profileImageUrl }}" class="profile-image" alt="{{ $user->name }}">
            <span class="online-indicator"></span>
        </div>
        <h3 class="profile-name">{{ $user->name }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        <span class="role-badge">Student</span>
    </div>

    <!-- Main Navigation -->
    <ul class="nav-menu">

        <li class="nav-item">
            <a href="{{ route('student.dashboard') }}"
               class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <div class="nav-divider"></div>

        <!-- Academics Section -->
        <div class="nav-section-title">ACADEMICS</div>

        <li class="nav-item">
            <a href="{{ route('student.schedule') }}"
               class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Classroom Schedule</span>
            </a>
        </li>

        {{-- Uncomment when ready:
        <li class="nav-item">
            <a href="{{ route('student.teachers') }}"
               class="nav-link {{ request()->routeIs('student.teachers') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-user"></i>
                <span>Professors</span>
            </a>
        </li>
        --}}

        <div class="nav-divider"></div>

        <!-- Account Section -->
        <div class="nav-section-title">ACCOUNT</div>

        <li class="nav-item">
            <a href="{{ route('student.profile') }}"
               class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i>
                <span>My Profile</span>
            </a>
        </li>

    </ul>

    <!-- Footer Menu -->
    <ul class="nav-menu nav-footer">
        <div class="nav-divider"></div>

        <li class="nav-item">
            <a href="#" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
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
                <h5 class="modal-title" id="helpModalLabel">Help & Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-headset" style="font-size: 3rem; color: var(--primary-green);"></i>
                </div>

                <h6>Quick Tips:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        View your class schedule in the "Classroom Schedule" section
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Switch between Week and Time Grid views on your schedule
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Export or print your schedule using the PDF / CSV buttons
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Update your profile photo and details in "My Profile"
                    </li>
                </ul>

                <hr>

                <div class="text-center">
                    <p class="mb-2"><strong>Need assistance?</strong></p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i> support@cms.edu<br>
                        <i class="fas fa-phone me-2"></i> (123) 456-7890
                    </p>
                    <p class="text-muted small">Available Monday–Friday, 8:00 AM – 5:00 PM</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    // Mobile sidebar toggle
    $('#sidebarToggle').click(function () {
        $('#studentSidebar').toggleClass('active');
    });

    // Close sidebar on outside click (mobile)
    $(document).click(function (e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('#studentSidebar').length &&
                !$(e.target).closest('#sidebarToggle').length) {
                $('#studentSidebar').removeClass('active');
            }
        }
    });
});
</script>
@endpush