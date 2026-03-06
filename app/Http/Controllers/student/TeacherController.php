<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return view('student.teachers');
    }

    public function search(Request $request)
    {
        return back();
    }

    public function show($id)
    {
        return view('student.teacher-profile');
    }
}