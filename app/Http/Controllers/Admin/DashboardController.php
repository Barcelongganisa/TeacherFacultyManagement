<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\RoomReservation;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        // Get statistics
        $totalTeachers = Teacher::where('status', 'active')->count();
        $totalStudents = User::where('role', 'student')->where('status', 'active')->count();
        $totalSubjects = Subject::where('status', 'active')->count();
        $totalClassrooms = Classroom::where('status', 'active')->count();
        $totalSchedules = Schedule::count();
        $totalTimeSlots = TimeSlot::where('status', 'active')->count();
        $pendingReservations = RoomReservation::where('status', 'pending')->count();
        $totalAssignments = TeacherSubject::count();
        
        // Recent teachers
        $recentTeachers = Teacher::with('user')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalTeachers',
            'totalStudents',
            'totalSubjects',
            'totalClassrooms',
            'totalSchedules',
            'totalTimeSlots',
            'pendingReservations',
            'totalAssignments',
            'recentTeachers'
        ));
    }
}