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
        $userCourse = $request->user();
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = Teacher::with('user');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $teachers = $query->orderBy('created_at', 'desc')->where('course_id', $userCourse->course_id)->paginate(15);

        return view('admin.teachers.index', compact('teachers', 'search', 'status'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = Teacher::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
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
        $campuses    = DB::table('campuses')->orderBy('campus_name')->get();
        $departments = DB::table('departments')->orderBy('name')->get();
        $courses     = DB::table('courses')->orderBy('name')->get();
        return view('admin.teachers.create', compact('campuses', 'departments', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|string|unique:teachers,employee_id',
            'email' => 'required|email|unique:users,email|unique:teachers,email',
            'password' => 'required|string|min:8',
            'first_name'  => 'required|string',
            'last_name' => 'required|string',
            'phone'   => 'nullable|string',
            'campus_id'  => 'required|exists:campuses,id',      
            'department_id' => 'nullable|exists:departments,id',   
            'course_id'   => 'nullable|exists:courses,id',       
            'qualification' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'bio'  => 'nullable|string',
            'status'  => 'nullable|in:active,inactive,on_leave',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'  => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'  => 'teacher',
                'status' => $validated['status'] ?? 'active',
                'phone' => $validated['phone'] ?? null,
                'campus_id' => $validated['campus_id'],        
                'department_id' => $validated['department_id'] ?? null, 
                'course_id' => $validated['course_id'] ?? null,     
            ]);

            Teacher::create([
                'user_id'=> $user->id,
                'employee_id' => $validated['employee_id'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'campus_id' => $validated['campus_id'],       
                'department_id' => $validated['department_id'] ?? null, 
                'course_id' => $validated['course_id'] ?? null,     
                'specialization' => $validated['qualification'] ?? null,
                'hire_date' => $validated['hire_date'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'status'=> $validated['status'] ?? 'active',
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

        $location = $this->getCurrentTeacherLocation($teacher->id);

        return view('admin.teachers.show', compact('teacher', 'location'));
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

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
        $currentDay  = now()->format('l');
        $currentTime = now()->format('H:i:s');

        $schedule = Schedule::where('teacher_id', $teacherId)
            ->where('day_of_week', $currentDay)
            ->where('status', 'active')
            ->with(['classroom', 'timeSlot'])
            ->first();

        if ($schedule && $schedule->timeSlot) {
            $startTime        = strtotime($schedule->timeSlot->start_time);
            $endTime          = strtotime($schedule->timeSlot->end_time);
            $currentTimestamp = strtotime($currentTime);

            if ($currentTimestamp >= ($startTime - 300) && $currentTimestamp <= ($endTime + 300)) {
                return $schedule->classroom->room_name . ' (' . $schedule->classroom->room_number . ')';
            }
        }

        return '-';
    }
}
