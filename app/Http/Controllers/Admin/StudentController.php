<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = User::where('role', 'student');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $students = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.students.index', compact('students', 'search'));
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = User::where('role', 'student');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $students = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json(['students' => $students]);
    }
    
    public function create()
    {
        return view('admin.students.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive',
        ]);
        
        try {
            User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'status' => $validated['status'],
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
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8',
        ]);
        
        try {
            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
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