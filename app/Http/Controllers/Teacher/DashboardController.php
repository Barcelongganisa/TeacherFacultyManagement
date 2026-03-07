<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Teacher::with(['schedules', 'subjects'])->where('user_id', auth()->id())->first();
        $teacherId = $teacher->id ?? null;

        if (!$teacherId) {
            abort(404, 'Teacher not found');
        }

        // Current assignment
        $currentAssignment = $teacher->schedules()
            ->where('status', 'active')
            ->where('day_of_week', date('l'))
            ->with(['subject', 'classroom'])
            ->first();

        // Counts
        $scheduleCount = $teacher->schedules()->where('status', 'active')->count();
        $subjectCount = $teacher->subjects()
            ->wherePivot('status', 'active')
            ->count();
        $roomCount = $teacher->schedules()->where('status', 'active')->distinct('classroom_id')->count('classroom_id');

        // Today's classes
        $todayClasses = $teacher->schedules()
            ->where('status', 'active')
            ->where('day_of_week', date('l'))
            ->with(['subject', 'classroom', 'timeSlot'])
            ->get()
            ->sortBy(fn($schedule) => $schedule->timeSlot->start_time);

                // Upcoming classes (next 10)
        $upcomingClasses = DB::table('schedules as s')
            ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
            ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
            ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
            ->where('s.teacher_id', $teacherId)
            ->where('s.status', 'active')
            ->orderByRaw("FIELD(s.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('ts.start_time')
            ->limit(10)
            ->select('s.day_of_week', 'ts.slot_name', 'ts.start_time', 'ts.end_time', 'sub.subject_name', 'c.room_number', 'c.room_name')
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'currentAssignment',
            'scheduleCount',
            'subjectCount',
            'roomCount',
            'todayClasses',
            'upcomingClasses'
        ));
    }
}