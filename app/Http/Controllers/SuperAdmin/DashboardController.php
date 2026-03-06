<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campus;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalCampuses = Campus::where('status', 'active')->count();
        $totalUsers = User::where('role', '!=', 'superadmin')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        
        // Today's reservations
        $todaysReservations = Reservation::with(['classroom.campus', 'user'])
            ->whereDate('reservation_date', today())
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Pending reservations
        $pendingReservations = Reservation::where('status', 'pending')->count();
        
        // User statistics by role and status
        $userStats = User::select('role', 'status', DB::raw('count(*) as total'))
            ->groupBy('role', 'status')
            ->get()
            ->groupBy('role');
            
        // Campus overview with counts
        $campuses = Campus::withCount(['users' => function($q) {
                $q->where('role', 'admin');
            }, 'classrooms'])
            ->where('status', 'active')
            ->get();
            
        return view('superadmin.dashboard', compact(
            'totalCampuses',
            'totalUsers',
            'totalTeachers',
            'totalStudents',
            'totalAdmins',
            'todaysReservations',
            'pendingReservations',
            'userStats',
            'campuses'
        ));
    }
}