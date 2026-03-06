<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $status = $request->get('status', '');
        
        $query = Subject::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
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
        
        $subjects = $query->orderBy('subject_code')->paginate(15);
        
        $departments = Subject::distinct('department')->pluck('department');
        
        return view('admin.subjects.index', compact('subjects', 'search', 'department', 'status', 'departments'));
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $status = $request->get('status', '');
        
        $query = Subject::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
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
        return view('admin.subjects.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|unique:subjects',
            'subject_name' => 'required|string',
            'department' => 'nullable|string',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:1|max:10',
        ]);
        
        try {
            Subject::create($validated + ['status' => 'active']);
            
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding subject: ' . $e->getMessage());
        }
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