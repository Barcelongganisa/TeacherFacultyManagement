<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentSubjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get teacher ID
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;

        $subjects = [];
        if ($teacherId) {
            $subjects = DB::table('teacher_subjects as ts')
                ->join('subjects as s', 'ts.subject_id', '=', 's.id')
                ->where('ts.teacher_id', $teacherId)
                ->where('ts.status', 'active')
                ->select('s.id', 's.subject_name', 's.subject_code', 's.description', 's.credits')
                ->get();
        }

        return view('teacher.subjects', compact('subjects'));
    }

    public function getSchedule($id)
    {
        $teacher = DB::table('teachers')->where('user_id', auth()->id())->first();

        if (!$teacher) {
            return response()->json([]);
        }

        // Try schedules table first
        $schedules = DB::table('schedules as sc')
            ->join('time_slots as ts', 'sc.time_slot_id', '=', 'ts.id')
            ->join('classrooms as c', 'sc.classroom_id', '=', 'c.id')
            ->where('sc.subject_id', $id)
            ->where('sc.teacher_id', $teacher->id)
            ->select(
                'sc.day',
                'ts.start_time',
                'ts.end_time',
                'c.room_name as room'
            )
            ->get();

        // Fall back to reservations if schedules is empty
        if ($schedules->isEmpty()) {
            $schedules = DB::table('reservations as r')
                ->join('time_slots as ts', 'r.time_slot_id', '=', 'ts.id')
                ->join('classrooms as c', 'r.classroom_id', '=', 'c.id')
                ->join('teacher_subjects as tsu', 'r.teacher_id', '=', 'tsu.teacher_id')
                ->where('r.teacher_id', $teacher->id)
                ->where('tsu.subject_id', $id)        // filter by subject
                ->where('tsu.status', 'active')
                ->where('r.status', 'approved')
                ->whereNotNull('r.reservation_date')
                ->select(
                    DB::raw('DAYNAME(r.reservation_date) as day'),
                    'ts.start_time',
                    'ts.end_time',
                    'c.room_name as room'
                )
                ->distinct()                          // avoid duplicate rows
                ->get();
        }

        return response()->json($schedules);
    }

    public function getStudents($id)
{
    try {
        $teacher = DB::table('teachers')->where('user_id', auth()->id())->first();

        if (!$teacher) {
            return response()->json([]);
        }

        $isAssigned = DB::table('teacher_subjects')
            ->where('teacher_id', $teacher->id)
            ->where('subject_id', $id)
            ->where('status', 'active')
            ->exists();

        if (!$isAssigned) {
            return response()->json([]);
        }

        $students = DB::table('enrollments as e')
            ->join('users as u', 'e.student_id', '=', 'u.id')
            ->where('e.subject_id', $id)
            ->where('e.status', 'enrolled')
            ->where('u.role', 'student')
            ->select(
                'u.id as student_number',
                'u.name',
                'u.email',
                'e.created_at'
            )
            ->get();

        return response()->json($students);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}