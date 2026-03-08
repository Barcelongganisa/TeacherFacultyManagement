<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        $userId = $user->id;
        $timeSlots = DB::table('time_slots')
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();
        $scheduleData = [];
        if ($teacherId) {
            $schedules = DB::table('schedules as s')
                ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                ->where('s.teacher_id', $userId)
                ->where('s.status', 'active')
                ->select('s.day','s.time_slot_id','sub.subject_code','sub.subject_name','c.room_number','c.room_name')->get();
            foreach ($schedules as $schedule) {
                $scheduleData[$schedule->day][$schedule->time_slot_id] = $schedule;
            }
        }
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('teacher.schedule', compact( 'timeSlots', 'scheduleData','days','teacherId','userId'));
    }
}
