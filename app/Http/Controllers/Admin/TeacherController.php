<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $query = Teacher::with('user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $teachers = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.teachers.index', compact('teachers', 'search', 'status'));
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $query = Teacher::with('user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $teachers = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json(['teachers' => $teachers]);
    }
    
    public function create()
    {
        return view('admin.teachers.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'qualification' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'bio' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create user
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'teacher',
                'status' => 'active',
            ]);
            
            // Create teacher
            Teacher::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'department' => $validated['department'],
                'qualification' => $validated['qualification'],
                'hire_date' => $validated['hire_date'],
                'bio' => $validated['bio'],
                'status' => 'active',
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error adding teacher: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        
        // Get current location
        $location = $this->getCurrentTeacherLocation($teacher->id);
        
        return view('admin.teachers.show', compact('teacher', 'location'));
    }
    
    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            
            // Delete associated user if exists
            if ($teacher->user_id) {
                User::where('id', $teacher->user_id)->delete();
            }
            
            $teacher->delete();
            
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting teacher: ' . $e->getMessage());
        }
    }
    
    private function getCurrentTeacherLocation($teacherId)
    {
        $currentDay = now()->format('l');
        $currentTime = now()->format('H:i:s');
        
        // Check scheduled classes
        $schedule = Schedule::where('teacher_id', $teacherId)
            ->where('day_of_week', $currentDay)
            ->where('status', 'active')
            ->with(['classroom', 'timeSlot'])
            ->first();
        
        if ($schedule && $schedule->timeSlot) {
            $startTime = strtotime($schedule->timeSlot->start_time);
            $endTime = strtotime($schedule->timeSlot->end_time);
            $currentTimestamp = strtotime($currentTime);
            
            if ($currentTimestamp >= ($startTime - 300) && $currentTimestamp <= ($endTime + 300)) {
                return $schedule->classroom->room_name . ' (' . $schedule->classroom->room_number . ')';
            }
        }
        
        return '-';
    }
}