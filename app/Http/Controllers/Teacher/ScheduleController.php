<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index($teacherId){
        $teacher = DB::table('teachers')
            ->where('id', $teacherId)
            ->first();
        if (!$teacher) {
            abort(404);
        }
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $schedules = DB::table('schedules')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('classrooms', 'classrooms.id', '=', 'schedules.classroom_id')
            ->leftJoin('courses','courses.id', '=', 'subjects.course_id')
            ->where('schedules.teacher_id', $teacherId)
            ->select('schedules.id','schedules.day_of_week','schedules.start_time','schedules.section','courses.code as course_coode',
                'schedules.end_time','schedules.section','subjects.subject_code','subjects.subject_name','classrooms.room_number','subjects.year_level')
            ->get();
        foreach ($schedules as $s) {
            $s->day_of_week = ucfirst(strtolower(trim($s->day_of_week)));
        }
        $subjects = DB::table('subjects')
            ->where('status', 'active')
            ->orderBy('subject_name')
            ->get();

        $classrooms = DB::table('classrooms')
            ->where('status', 'active')
            ->orderBy('room_number')
            ->get();

        return view('teacher.schedule', compact('teacher','schedules','days',
            'subjects','classrooms'));
    }
}
