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

        if (!$teacher) {
            abort(404, 'Teacher not found');
        }

        $teacherId = $teacher->id;

        $currentAssignment = $teacher->schedules()
            ->where('status', 'active')
            ->where('day_of_week', date('l'))
            ->with(['subject', 'classroom'])
            ->first();

        $scheduleCount = $teacher->schedules()->where('status', 'active')->count();
        $subjectCount  = $teacher->subjects()->wherePivot('status', 'active')->count();
        $roomCount     = $teacher->schedules()->where('status', 'active')->distinct('classroom_id')->count('classroom_id');

        $todayClasses = DB::table('schedules as s')
            ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
            ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
            ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
            ->where('s.teacher_id', $teacherId)
            ->where('s.status', 'active')
            ->where('s.day_of_week', date('l'))
            ->orderBy('ts.start_time')
            ->select('s.day_of_week', 'ts.interval_time', 'ts.start_time', 'ts.end_time', 'sub.subject_name', 'c.room_number', 'c.room_name')
            ->get();

        $upcomingClasses = DB::table('schedules as s')
            ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
            ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
            ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
            ->where('s.teacher_id', $teacherId)
            ->where('s.status', 'active')
            ->orderByRaw("FIELD(s.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('ts.start_time')
            ->limit(10)
            ->select('s.day_of_week', 'ts.interval_time', 'ts.start_time', 'ts.end_time', 'sub.subject_name', 'c.room_number', 'c.room_name')
            ->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedules = DB::table('schedules')
            ->leftJoin('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->leftJoin('classrooms', 'classrooms.id', '=', 'schedules.classroom_id')
            ->leftJoin('courses', 'courses.id', '=', 'subjects.course_id')
            ->where('schedules.teacher_id', $teacherId)
            ->where('schedules.status', 'active')
            ->select(
                'schedules.id',
                'schedules.day_of_week',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.section',
                'courses.code as course_coode',
                'subjects.subject_code',
                'subjects.subject_name',
                'subjects.year_level',
                'classrooms.room_number'
            )
            ->get()
            ->each(function ($s) {
                $s->day_of_week = ucfirst(strtolower(trim($s->day_of_week)));
            });

        return view('teacher.dashboard', compact(
            'teacher',
            'currentAssignment',
            'scheduleCount',
            'subjectCount',
            'roomCount',
            'todayClasses',
            'upcomingClasses',
            'days',
            'schedules'
        ));
    }

    public function getSched(Request $request)
    {
        $teacherId = $request->user();
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
            ->leftJoin('courses', 'courses.id', '=', 'subjects.course_id')
            ->where('schedules.teacher_id', $teacherId)
            ->select(
                'schedules.id',
                'schedules.day_of_week',
                'schedules.start_time',
                'schedules.section',
                'courses.code as course_coode',
                'schedules.end_time',
                'subjects.subject_code',
                'subjects.subject_name',
                'classrooms.room_number',
                'subjects.year_level'
            )
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

        return view('teacher.schedule', compact(
            'teacher',
            'schedules',
            'days',
            'subjects',
            'classrooms'
        ));
    }
}
