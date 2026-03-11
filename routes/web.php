<?php
// Student Routes
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentAnnouncementController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\StudentSubjectController;
use App\Http\Controllers\Student\TeacherController as StudentTeacherController;

// Teacher Routes
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Teacher\ReservationController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\StudentSubjectController as TeacherSubjectController;
use App\Http\Controllers\Teacher\CurrentAssignmentController;

// Admin Routes
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Super Admin Routes
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\CampusController as SuperAdminCampusController;
use App\Http\Controllers\SuperAdmin\AssignmentController as SuperAdminAssignmentController;
use App\Http\Controllers\SuperAdmin\ReservationController as SuperAdminReservationController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\SuperAdmin\SuperAdminDepartmentController;
use App\Http\Controllers\SuperAdmin\SuperAdminCourseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});
// Super Admin Routes
Route::middleware(['auth', 'verified', 'super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', SuperAdminUserController::class);
    Route::post('users/search', [SuperAdminUserController::class, 'search'])->name('users.search');
    Route::post('users/{user}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [SuperAdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // Campus Management
    Route::resource('campuses', SuperAdminCampusController::class);
    Route::post('campuses/search', [SuperAdminCampusController::class, 'search'])->name('campuses.search');

    Route::resource('departments', SuperAdminDepartmentController::class);
    Route::resource('courses', SuperAdminCourseController::class);
    // Reservations (global view)
    Route::get('reservations', [SuperAdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('reservations/{reservation}', [SuperAdminReservationController::class, 'show'])->name('reservations.show'); 
    Route::post('reservations/{reservation}/approve', [SuperAdminReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('reservations/{reservation}/reject', [SuperAdminReservationController::class, 'reject'])->name('reservations.reject');

    // Profile
    Route::get('profile', [SuperAdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [SuperAdminProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [SuperAdminProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('profile/image', [SuperAdminProfileController::class, 'updateImage'])->name('profile.image');
    Route::delete('profile/image', [SuperAdminProfileController::class, 'removeImage'])->name('profile.image.remove');
    Route::get('profile/activity', [SuperAdminProfileController::class, 'activityLogs'])->name('profile.activity');
    Route::get('profile/logins', [SuperAdminProfileController::class, 'loginHistory'])->name('profile.logins');
    Route::post('profile/notifications', [SuperAdminProfileController::class, 'updateNotifications'])->name('profile.notifications');
    Route::post('profile/two-factor', [SuperAdminProfileController::class, 'setupTwoFactor'])->name('profile.two-factor');
    Route::get('profile/export', [SuperAdminProfileController::class, 'exportData'])->name('profile.export');
    Route::delete('profile', [SuperAdminProfileController::class, 'deleteAccount'])->name('profile.delete');
});
// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Teachers Management
    Route::resource('teachers', AdminTeacherController::class);
    Route::post('teachers/search', [AdminTeacherController::class, 'search'])->name('teachers.search');
    Route::get('teachers/{id}/view', [AdminTeacherController::class, 'show'])->name('teachers.view');

    // Students Management
    Route::resource('students', AdminStudentController::class);
    Route::post('students/search', [AdminStudentController::class, 'search'])->name('students.search');

    // Subjects Management
    Route::resource('subjects', AdminSubjectController::class);
    Route::post('subjects/search', [AdminSubjectController::class, 'search'])->name('subjects.search');

    // Classrooms Management
    Route::resource('classrooms', ClassroomController::class);
    Route::post('classrooms/search', [ClassroomController::class, 'search'])->name('classrooms.search');

    // Schedules Management
    Route::get('schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
    Route::get('schedules/teacher/{teacher_id}', [AdminScheduleController::class, 'viewSchedule'])->name('schedules.view');
    Route::post('schedules/add', [AdminScheduleController::class, 'store'])->name('schedules.store');
    Route::put('schedules/{schedule_id}', [AdminScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('schedules/{schedule_id}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('schedules/get-data/{schedule_id}', [AdminScheduleController::class, 'getScheduleData'])->name('schedules.get-data');

    // Teacher Assignments
    Route::resource('assignments', AssignmentController::class)->except(['edit', 'update', 'show']);
    Route::post('assignments/search', [AssignmentController::class, 'search'])->name('assignments.search');

    // Time Slots
    Route::resource('time-slots', TimeSlotController::class);

    // Reservations Management
    Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::post('reservations/approve/{id}', [AdminReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('reservations/reject/{id}', [AdminReservationController::class, 'reject'])->name('reservations.reject');

    // Profile
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    // Route::get('/announcements', [StudentAnnouncementController::class, 'index'])->name('announcements');
    // Route::post('/announcements/mark-read', [StudentAnnouncementController::class, 'markRead'])->name('announcements.mark-read');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/schedule', [StudentScheduleController::class, 'schedule'])->name('schedule');
    Route::get('/subjects', [StudentSubjectController::class, 'index'])->name('subjects');
    Route::post('/subjects/enroll', [StudentSubjectController::class, 'enroll'])->name('subjects.enroll');
    Route::post('/subjects/unenroll', [StudentSubjectController::class, 'unenroll'])->name('subjects.unenroll');
    Route::get('/teachers', [StudentTeacherController::class, 'index'])->name('teachers');
    Route::post('/teachers/search', [StudentTeacherController::class, 'search'])->name('teachers.search');
    Route::get('/teacher-profile/{id}', [StudentTeacherController::class, 'show'])->name('teacher-profile');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [TeacherProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [TeacherProfileController::class, 'update'])->name('profile.update');

    // Schedule
    Route::get('/schedule', [TeacherScheduleController::class, 'index'])->name('schedule');

    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::post('/reservations/create', [ReservationController::class, 'store'])->name('reservations.store');

    // Availability
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability');
    Route::post('/availability/set', [AvailabilityController::class, 'setAvailability'])->name('availability.set');

    // Subjects
    Route::get('/subjects', [TeacherSubjectController::class, 'index'])->name('subjects');

    // Current Assignment
    Route::get('/current-assignment', [CurrentAssignmentController::class, 'index'])->name('current-assignment');

    Route::get('/subject/{id}/schedule', [TeacherSubjectController::class, 'getSchedule'])->name('subjects.schedule');
    Route::get('/subject/{id}/students', [TeacherSubjectController::class, 'getStudents'])->name('subjects.students');
});


require __DIR__ . '/auth.php';
