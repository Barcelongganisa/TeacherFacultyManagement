<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SuperAdminCourseController extends Controller
{
    public function index(Request $request){
        $query = DB::table('courses')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->join('campuses', 'departments.campus_id', '=', 'campuses.id')
            ->leftJoin('users', 'courses.coordinator_id', '=', 'users.id')
            ->select( 'courses.*','departments.name as department_name','campuses.campus_name',
                'users.name as coordinator_name')
            ->orderBy('courses.created_at', 'desc');
        if ($request->search) {
            $query->where('courses.name', 'like', '%' . $request->search . '%')
                ->orWhere('courses.code', 'like', '%' . $request->search . '%');
        }
        if ($request->department_id) {
            $query->where('courses.department_id', $request->department_id);
        }
        if ($request->status) {
            $query->where('courses.status', $request->status);
        }
        $courses = $query->paginate(10);
        $departments = DB::table('departments')->orderBy('name')->get();
        return view('superadmin.courses.index', compact('courses', 'departments'));
    }

    public function create(){
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();
        $coordinators = DB::table('users')->where('role', 'admin')->orderBy('name')->get();
        return view('superadmin.courses.create', compact('campuses', 'departments', 'coordinators'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=> 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
            'coordinator_id' => 'nullable|exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ]);
        DB::table('courses')->insert([
            'name'=> $request->name,
            'code'  => strtoupper($request->code),
            'description' => $request->description,
            'status' => $request->status,
            'coordinator_id'=> $request->coordinator_id,
            'department_id'=> $request->department_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('superadmin.courses.index')->with('success', 'Course created successfully.');
    }
}
