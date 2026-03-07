<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Enrollment;

class StudentSubjectController extends Controller
{
    public function index(){
        $student = Auth::user();
        $studentId = $student->id;
        $studentCampus = $student->campus;

        $subjects = Subject::with('teachers')
            ->whereHas('teachers', function ($q) use ($studentCampus) {$q->where('campus', $studentCampus); })->get();

        $enrolledSubjectIds = Enrollment::where('student_id', $studentId)->pluck('subject_id')->toArray();
        foreach ($subjects as $subject) {
            $subject->enrollment_id = in_array($subject->id, $enrolledSubjectIds) ? 1 : null;

            if ($subject->teachers->isNotEmpty()) {
                $subject->teacher_ids = $subject->teachers->pluck('id')->implode(',');
                $subject->teachers = $subject->teachers->pluck('name')->implode(', ');
            } else {
                $subject->teacher_ids = '';
                $subject->teachers = '';
            }
        }

        return view('student.subjects', compact('subjects'));
    }

    public function enroll(Request $request)
    {
        $student = Auth::user();

        $subject = Subject::whereHas('teachers', function ($q) use ($student) {
                $q->where('campus', $student->campus);
            })
            ->where('id', $request->subject_id)
            ->firstOrFail();

        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        return back()->with('success', 'Enrolled successfully.');
    }

    public function unenroll(Request $request)
    {
        $studentId = Auth::id();

        Enrollment::where('student_id', $studentId)
            ->where('subject_id', $request->subject_id)
            ->delete();

        return back()->with('success', 'Unenrolled successfully.');
    }
}