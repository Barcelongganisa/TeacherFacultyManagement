<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends AdminBaseController
{
    public function index(Request $request)
    {
        $userCourse = $request->user();
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = Subject::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_code', 'like', "%{$search}%")
                    ->orWhere('subject_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }


        if ($status) {
            $query->where('status', $status);
        }

        $subjects = $query->orderBy('subject_code')->where('course_id', $userCourse->course_id)->paginate(15);

        return view('admin.subjects.index', compact('subjects', 'search',  'status'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $status = $request->get('status', '');

        $query = Subject::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_code', 'like', "%{$search}%")
                    ->orWhere('subject_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $subjects = $query->orderBy('subject_code')->get();

        return response()->json(['subjects' => $subjects]);
    }

    public function create()
    {
        $courses = Course::orderBy('name')->get();
        return view('admin.subjects.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'credits'      => 'nullable|integer|min:1|max:10',
            'course_id'    => 'required|exists:courses,id',
            'year_level'   => 'required|in:1st,2nd,3rd,4th',
            'semester'     => 'required|in:1st Semester,2nd Semester,Summer',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
        ]);

        DB::table('subjects')->insert([
            'subject_code' => strtoupper($request->subject_code),
            'subject_name' => $request->subject_name,
            'credits'      => $request->credits,
            'course_id'    => $request->course_id,
            'year_level'   => $request->year_level,
            'semester'     => $request->semester,
            'description'  => $request->description,
            'status'       => $request->status,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject added successfully.');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code,' . $id,
            'subject_name' => 'required|string',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $subject->update($validated);

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating subject: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);

            // Check if subject is being used
            if ($subject->schedules()->count() > 0 || $subject->teacherSubjects()->count() > 0) {
                return back()->with('error', 'Cannot delete subject. It has been assigned to schedules or teachers.');
            }

            $subject->delete();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting subject: ' . $e->getMessage());
        }
    }
}
