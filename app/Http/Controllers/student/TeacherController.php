<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        return view('student.teachers');
    }

   public function search(Request $request){
    $search = $request->input('search', '');
    $department = $request->input('department', '');
    $query = DB::table('teachers')->join('users', 'teachers.user_id', '=', 'users.id')
        ->select( 'teachers.id', 'teachers.employee_id','teachers.department','teachers.specialization',
            'teachers.phone', 'users.name','users.email', 'users.profile_image', 'users.status')
        ->where('users.role', 'teacher');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('users.name',              'like', "%{$search}%")
              ->orWhere('users.email',            'like', "%{$search}%")
              ->orWhere('teachers.department',    'like', "%{$search}%")
              ->orWhere('teachers.specialization','like', "%{$search}%");
        });
    }

    if (!empty($department)) {
        $query->where('teachers.department', $department);
    }

    $teachers = $query->orderBy('users.name')->get();

    return response()->json($teachers);
}

  public function show($id){
    $teacher = DB::table('teachers')->join('users', 'teachers.user_id', '=', 'users.id')
        ->select('teachers.id','teachers.employee_id','teachers.department',
            'teachers.specialization','teachers.phone','users.name','users.email','users.profile_image','users.status','users.role')
        ->where('teachers.id', $id)
        ->where('users.role', 'teacher')
        ->first();

    if (!$teacher) {
        abort(404, 'Teacher not found.');
    }

    return view('student.teacher-profile', compact('teacher'));
}
}
