<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departmentData = DB::table('departments')
            ->join('campuses', 'departments.campus_id', '=', 'campuses.id')
            ->select('departments.*', 'campuses.campus_name')
            ->orderBy('departments.created_at', 'desc');
        if ($request->search) {
            $departmentData->where('departments.name', 'like', '%' . $request->search . '%')
                ->orWhere('departments.code', 'like', '%' . $request->search . '%');
        }

        if ($request->campus_id) {
            $departmentData->where('departments.campus_id', $request->campus_id);
        }
        $departments = $departmentData->paginate(10);
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();

        return view('superadmin.departments.index', compact('departments', 'campuses'));
    }

    public function create()
    {
        $campuses = Campus::orderBy('campus_name')->get();
        return view('superadmin.departments.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:departments,name',
            'code'      => 'required|string|max:10|unique:departments,code', 
            'campus_id' => 'required|exists:campuses,id',
        ]);
        $exists = DB::table('departments')
            ->where('name', $request->name)
            ->where('campus_id', $request->campus_id)
            ->exists();

        if ($exists) {
            return back()->withInput()
            ->withErrors(['name' => 'This department already exists under the selected campus.']);
        }
        DB::table('departments')->insert([
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
            'campus_id'  => $request->campus_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'Department created successfully.');
    }
}
