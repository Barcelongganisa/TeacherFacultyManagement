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
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        $subjects = collect();
        $totalCredits = 0;
        $totalStudents = 0;
        $totalSubjects = 0;

        if ($teacherId) {
            $subjects = DB::table('teacher_subjects as ts')
                ->join('subjects as s', 'ts.subject_id', '=', 's.id')
                ->where('ts.teacher_id', $teacherId)
                ->where('ts.status', 'active')
                ->select( 's.id',
                    's.subject_name',
                    's.subject_code',
                    's.description',
                    's.credits',
                    's.year_level',
                    DB::raw("(SELECT COUNT(*) FROM enrollments WHERE subject_id = s.id AND status = 'active') as enrolled_students"),
                    DB::raw("(SELECT COUNT(*) FROM schedules WHERE subject_id = s.id AND teacher_id = {$teacherId}) as schedule_count")
                )
                ->get();

            $totalCredits = $subjects->sum('credits');
            $totalStudents = $subjects->sum('enrolled_students');
            $totalSubjects = $subjects->count();
        }

        return view('teacher.subjects', compact('subjects', 'totalCredits', 'totalStudents', 'totalSubjects'));
    }

    public function getSchedule($subjectId)
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();

        if (!$teacher) {
            abort(403, 'You are not registered as a teacher.');
        }

        $teacherId = $teacher->id;

        $schedules = DB::table('schedules as sc')
            ->leftJoin('time_slots as ts', 'sc.time_slot_id', '=', 'ts.id')
            ->leftJoin('classrooms as c', 'sc.classroom_id', '=', 'c.id')
            ->where('sc.subject_id', $subjectId)
            ->where('sc.teacher_id', $teacherId)
            ->select(
                'sc.day',
                DB::raw('COALESCE(ts.start_time, sc.start_time) as start_time'),
                DB::raw('COALESCE(ts.end_time, sc.end_time) as end_time'),
                DB::raw('COALESCE(c.room_name, sc.room_number) as room')
            )
            ->get();

        if ($schedules->isEmpty()) {
            $schedules = DB::table('reservations as r')
                ->leftJoin('time_slots as ts', 'r.time_slot_id', '=', 'ts.id')
                ->leftJoin('classrooms as c', 'r.classroom_id', '=', 'c.id')
                ->leftJoin('teacher_subjects as tsu', 'r.teacher_id', '=', 'tsu.teacher_id')
                ->where('r.teacher_id', $teacherId)
                ->where('tsu.subject_id', $subjectId)
                ->where('tsu.status', 'active')
                ->where('r.status', 'approved')
                ->whereNotNull('r.reservation_date')
                ->select(
                    DB::raw('DAYNAME(r.reservation_date) as day'),
                    'ts.start_time',
                    'ts.end_time',
                    DB::raw('COALESCE(c.room_name, r.room_number) as room')
                )
                ->distinct()
                ->get();
        }

        return response()->json($schedules);
    }

    public function getStudents($subjectId)
    {
        try {
            $user = Auth::user();
            $teacher = DB::table('teachers')->where('user_id', $user->id)->first();

            if (!$teacher) {
                abort(403, 'You are not registered as a teacher.');
            }

            $teacherId = $teacher->id;

            $isAssigned = DB::table('teacher_subjects')
                ->where('teacher_id', $teacherId)
                ->where('subject_id', $subjectId)
                ->where('status', 'active')
                ->exists();

            if (!$isAssigned) {
                return response()->json([]);
            }

            $students = DB::table('enrollments as e')
                ->join('users as u', 'e.student_id', '=', 'u.id')
                ->join('subjects as s', 'e.subject_id', '=', 's.id')
                ->where('e.subject_id', $subjectId)
                ->where('e.status', 'active')
                ->where('u.role', 'student')
                ->select(
                    'u.id as student_number',
                    'u.name',
                    'u.email',
                    's.subject_code',
                    's.subject_name',
                    'e.created_at'
                )
                ->get();

            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function autoEnrollStudentsByYearLevel($subjectId, $teacherId){
        $subject = DB::table('subjects')->where('id', $subjectId)->first();
        if (!$subject || !$subject->year_level || !$subject->course_id) {
            return;
        }

        $students = DB::table('users')
            ->where('role', 'student')
            ->where('status', 'active')
            ->where('course_id', $subject->course_id)
            ->where('year_level', $subject->year_level)
            ->whereNotExists(function ($query) use ($subjectId) {
                $query->select(DB::raw(1))
                    ->from('enrollments')
                    ->whereColumn('enrollments.student_id', 'users.id')
                    ->where('enrollments.subject_id', $subjectId);
            })
            ->get();

        if ($students->isEmpty()) {
            return;
        }
        $enrollments = $students->map(function ($student) use ($subjectId) {
            return [
                'student_id' => $student->id,
                'subject_id' => $subjectId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('enrollments')->insert($enrollments);
    }
}