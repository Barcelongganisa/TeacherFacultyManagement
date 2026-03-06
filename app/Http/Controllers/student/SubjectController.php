<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Enrollment;


class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        $enrollments = Enrollment::where('student_id', Auth::id())->get();

        return view('student.subjects', compact('subjects', 'enrollments'));
    }

    public function enroll(Request $request)
    {
        return back();
    }

    public function unenroll(Request $request)
    {
        return back();
    }
}
