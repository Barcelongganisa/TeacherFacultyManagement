<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
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
                ->select('s.subject_name', 's.subject_code', 's.description', 's.credits')
                ->get();
        }
        
        return view('teacher.subjects', compact('subjects'));
    }
}