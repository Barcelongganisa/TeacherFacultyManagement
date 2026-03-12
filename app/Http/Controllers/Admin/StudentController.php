<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends AdminBaseController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->get('search', '');
        $query = User::where('role', 'student');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.students.index', compact('students', 'search'));
    }

    public function search(Request $request)
    {
        $user = $request->user();
        $search = $request->get('search', '');

        $query = User::where('role', 'student')
            ->where('campus', $user->campus); // campus lock

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['students' => $students]);
    }

    public function create(){
        $campuses = DB::table('campuses')->orderBy('campus_name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();
        $courses = DB::table('courses')->orderBy('name')->get();
        return view('admin.students.create', compact('campuses', 'departments', 'courses'));
    }

    public function store(Request $request){
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:8',
            'campus_id'     => 'required|exists:campuses,id',
            'department_id' => 'nullable|exists:departments,id',
            'course_id'     => 'nullable|exists:courses,id',
            'year_level'    => 'required|in:1st,2nd,3rd,4th',
            'status'        => 'required|in:active,inactive',
        ]);

        try {
            DB::table('users')->insert([
                'name'=> $request->first_name . ' ' . $request->last_name,
                'email'=> $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'campus_id' => $request->campus_id,
                'department' => $request->department_id,
                'course_id' => $request->course_id,
                'year_level'=> $request->year_level,
                'status'=> $request->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->route('admin.students.index')
                ->with('success', 'Student added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding student: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:users,email,' . $id,
            'campus'     => 'required|string',
            'status'     => 'required|in:active,inactive',
            'password'   => 'nullable|string|min:8',
        ]);

        try {
            $data = [
                'name'=> $validated['first_name'] . ' ' . $validated['last_name'],
                'email'=> $validated['email'],
                'campus' => $validated['campus'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $student->update($data);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $student = User::where('role', 'student')->findOrFail($id);
            $student->delete();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }
}
