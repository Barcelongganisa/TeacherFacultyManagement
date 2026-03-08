<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function schedule()
    {
        $user = Auth::user();

        $schedules = DB::table('schedules as s')
            ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
            ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
            ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
            ->join('users as u', 's.teacher_id', '=', 'u.id') // 👈 fixed: join users directly
            ->where('sub.course_id', $user->course_id)
            ->where('s.status', 'active')
            ->select(
                's.day',
                's.day_of_week',
                'sub.subject_code',
                'sub.subject_name',
                'ts.start_time',
                'ts.end_time',
                'c.room_number',
                'c.room_name',
                'u.name as teacher_name'
            )
            ->orderByRaw("FIELD(s.day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->get();

        return view('student.schedule', compact('schedules'));
    }
}