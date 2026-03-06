<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Enrollment;

class StudentSubjectController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Load subjects with teachers
        $subjects = Subject::with('teachers')->get();

        // Get IDs of subjects the student is already enrolled in
        $enrolledSubjectIds = Enrollment::where('student_id', $studentId)
            ->pluck('subject_id')
            ->toArray();

        // Prepare data for Blade
        foreach ($subjects as $subject) {
            // Mark if enrolled
            $subject->enrollment_id = in_array($subject->id, $enrolledSubjectIds) ? 1 : null;

            // Prepare teacher names and IDs for Blade
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
        $studentId = Auth::id();

        Enrollment::create([
            'student_id' => $studentId,
            'subject_id' => $request->subject_id
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