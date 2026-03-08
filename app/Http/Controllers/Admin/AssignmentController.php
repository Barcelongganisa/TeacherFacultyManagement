<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends AdminBaseController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $search     = $request->get('search', '');
        $year       = $request->get('year', '');
        $semester   = $request->get('semester', '');
        $status     = $request->get('status', '');
        $department = $request->get('department', '');

        $query = DB::table('teacher_subjects')
            ->join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->join('courses', 'subjects.course_id', '=', 'courses.id')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->select('teacher_subjects.*','teachers.name',
                'teachers.department','subjects.subject_name','subjects.subject_code')
            ->where('departments.campus_id', $user->campus_id) 
            ->orderBy('teacher_subjects.id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('teachers.name', 'like', "%{$search}%")
                    ->orWhere('subjects.subject_name', 'like', "%{$search}%")
                    ->orWhere('subjects.subject_code', 'like', "%{$search}%");
            });
        }

        if ($year) {
            $query->where('teacher_subjects.academic_year', $year);
        }

        if ($semester) {
            $query->where('teacher_subjects.semester', $semester);
        }

        if ($status) {
            $query->where('teacher_subjects.status', $status);
        }

        if ($department) {
            $query->where('departments.id', $department);
        }

        $assignments = $query->paginate(15);
        $years = DB::table('teacher_subjects')->distinct()->pluck('academic_year');

        $departments = DB::table('departments')
            ->where('campus_id', $user->campus_id)
            ->orderBy('name')
            ->get();

        return view('admin.assignments.index', compact(
            'assignments','search','year','semester',
            'status','department','years','departments'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $year = $request->get('year', '');
        $semester = $request->get('semester', '');
        $status = $request->get('status', '');
        $department = $request->get('department', '');

        $query = TeacherSubject::with(['teacher', 'subject']);

        if ($search) {
            $query->whereHas('teacher', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($q) use ($search) {
                $q->where('subject_name', 'like', "%{$search}%")
                    ->orWhere('subject_code', 'like', "%{$search}%");
            });
        }

        if ($year) {
            $query->where('academic_year', $year);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($department) {
            $query->whereHas('teacher', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $assignments = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['assignments' => $assignments]);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = DB::table('subjects')->where('course_id', $user->course_id)->get();

        return view('admin.assignments.create', compact('teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'nullable|string',
            'semester' => 'nullable|string',
        ]);

        try {
            TeacherSubject::create($validated + [
                'academic_year' => $validated['academic_year'] ?? date('Y'),
                'semester' => $validated['semester'] ?? '1',
                'status' => 'active',
            ]);

            return redirect()->route('admin.assignments.index')
                ->with('success', 'Assignment created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating assignment: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $assignment = TeacherSubject::findOrFail($id);
            $assignment->delete();

            return redirect()->route('admin.assignments.index')
                ->with('success', 'Assignment deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting assignment: ' . $e->getMessage());
        }
    }
}
