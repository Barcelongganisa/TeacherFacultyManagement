<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get teacher ID
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        
        $timeSlots = DB::table('time_slots')
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();
        
        $scheduleData = [];
        if ($teacherId) {
            $schedules = DB::table('schedules as s')
                ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                ->where('s.teacher_id', $teacherId)
                ->where('s.status', 'active')
                ->select('s.day_of_week', 's.time_slot_id', 'sub.subject_code', 'sub.subject_name', 'c.room_number')
                ->get();
            
            foreach ($schedules as $schedule) {
                $scheduleData[$schedule->day_of_week][$schedule->time_slot_id] = $schedule;
            }
        }
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        return view('teacher.schedule', compact('timeSlots', 'scheduleData', 'days', 'teacherId'));
    }
}