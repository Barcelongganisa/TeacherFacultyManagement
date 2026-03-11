<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SuperAdminDepartmentController extends Controller
{
    public function index(Request $request){
        $query = DB::table('departments')
            ->join('campuses', 'departments.campus_id', '=', 'campuses.id')
            ->select('departments.*', 'campuses.campus_name')
            ->orderBy('departments.name');
        if ($request->search) {
            $query->where('departments.name', 'like', '%' . $request->search . '%')
                ->orWhere('departments.code', 'like', '%' . $request->search . '%');
        }
        if ($request->campus_id) {
            $query->where('departments.campus_id', $request->campus_id);
        }

        $departments = $query->paginate(10);
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();
        return view('superadmin.departments.index', compact('departments', 'campuses'));
    }

    public function create(){
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();
        return view('superadmin.departments.create', compact('campuses'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'code'=> 'required|string|max:20|unique:departments,code',
            'campus_id'=> 'required|exists:campuses,id',
        ]);

        DB::table('departments')->insert([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'campus_id' => $request->campus_id,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'College created successfully.');
    }

    public function show($id){
        $department = DB::table('departments')
            ->join('campuses', 'departments.campus_id', '=', 'campuses.id')
            ->select('departments.*', 'campuses.campus_name')
            ->where('departments.id', $id)
            ->first();

        if (!$department) {
            return redirect()->route('superadmin.departments.index')
                ->with('error', 'College not found.');
        }

        $courses = DB::table('courses')
            ->where('department_id', $id)
            ->get();

        return view('superadmin.departments.show', compact('department', 'courses'));
    }

    public function edit($id){
        $department = DB::table('departments')->where('id', $id)->first();
        if (!$department) {
            return redirect()->route('superadmin.departments.index')
                ->with('error', 'College not found.');
        }
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();
        return view('superadmin.departments.edit', compact('department', 'campuses'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,' . $id,
            'campus_id' => 'required|exists:campuses,id',
        ]);

        DB::table('departments')->where('id', $id)->update([
            'name' => $request->name,
            'code'=> strtoupper($request->code),
            'campus_id' => $request->campus_id,
            'updated_at'=> now(),
        ]);
        return redirect()->route('superadmin.departments.show', $id)
            ->with('success', 'College updated successfully.');
    }
}
