<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends AdminBaseController
{
    public function index(Request $request){
        $search     = $request->get('search', '');
        $department = $request->get('department', '');
        $status     = $request->get('status', '');
        $query = DB::table('teachers');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name',       'like', "%{$search}%")
                    ->orWhere('email',      'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }
        if ($department) {
            $query->where('department', $department);
        }

        if ($status) {
            $query->where('status', $status);
        }
        $teachers    = $query->orderBy('name')->paginate(20);
        $departments = DB::table('teachers')->distinct()->pluck('department');
        return view('admin.schedules.index', compact(
            'teachers',
            'search',
            'department',
            'status',
            'departments'
        ));
    }

    public function viewSchedule($teacherId)
    {
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
            ->where('schedules.teacher_id', $teacherId)
            ->select('schedules.id','schedules.day_of_week','schedules.start_time',
                'schedules.end_time','schedules.section','subjects.subject_code','subjects.subject_name','classrooms.room_number')
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

        return view('admin.schedules.view', compact('teacher','schedules','days',
            'subjects','classrooms'));
    }
    public function getScheduleData($scheduleId){
        $schedule = DB::table('schedules')->where('id', $scheduleId)->first();

        if (!$schedule) {
            return response()->json(['success' => false]);
        }
        return response()->json([
            'success'      => true,
            'subject_id'   => $schedule->subject_id,
            'classroom_id' => $schedule->classroom_id,
            'day_of_week'  => $schedule->day_of_week,
            'section'      => $schedule->section,
            'start_time'   => \Carbon\Carbon::parse($schedule->start_time)->format('H:i'),
            'end_time'     => \Carbon\Carbon::parse($schedule->end_time)->format('H:i'),
            'status'       => $schedule->status,
        ]);
    }
}
