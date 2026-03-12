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
        $schedules = DB::table('enrollments as e')
            ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
            ->join('schedules as s', 's.subject_id', '=', 'sub.id')
            ->leftJoin('classrooms as c', 's.classroom_id', '=', 'c.id')
            ->leftJoin('teachers as t', 's.teacher_id', '=', 't.id')
            ->where('e.student_id', $user->id)
            ->where('e.status', 'active')
            ->where('s.status', 'active')
            ->select( 's.day_of_week','s.start_time',
                's.end_time','sub.subject_code',
                'sub.subject_name','sub.credits',
                'c.room_number','c.room_name',
                't.name as teacher_name'
            )
            ->orderByRaw("FIELD(s.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('s.start_time')
            ->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('student.schedule', compact('schedules', 'days'));
    }
}