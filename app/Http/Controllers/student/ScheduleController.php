<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(){
        $userId = Auth::id();
        $enrolledSubjectIds = Enrollment::where('student_id', $userId)->pluck('subject_id');
        if ($enrolledSubjectIds->isEmpty()) {
            return view('student.schedule', ['schedules' => collect()]);
        }
        $schedules = collect(DB::table('schedules')
            ->join('subjects',   'schedules.subject_id',   '=', 'subjects.id')
            ->join('time_slots', 'schedules.time_slot_id', '=', 'time_slots.id')
            ->join('classrooms', 'schedules.classroom_id', '=', 'classrooms.id')
            ->join('teachers',   'schedules.teacher_id',   '=', 'teachers.user_id')
            ->select(
                'schedules.id',
                'schedules.day_of_week',
                'subjects.subject_code',
                'subjects.subject_name',
                'time_slots.slot_name',
                'time_slots.start_time',
                'time_slots.end_time',
                'classrooms.room_number',
                'classrooms.room_name',
                'teachers.name as teacher_name'
            )
            ->whereIn('schedules.subject_id', $enrolledSubjectIds)
            ->where('schedules.status', 'active')
            ->orderByRaw("FIELD(schedules.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('time_slots.start_time')
            ->get()
        );

        return view('student.schedule', compact('schedules'));
    }
}