<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $status = $request->get('status', '');

        $query = Teacher::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $teachers = $query->orderBy('name')->paginate(20);

        $departments = Teacher::distinct('department')->pluck('department');

        return view('admin.schedules.index', compact('teachers', 'search', 'department', 'status', 'departments'));
    }

    public function viewSchedule($teacherId)
    {
        $teacher = Teacher::with('user')->findOrFail($teacherId);

        $timeSlots = TimeSlot::where('status', 'active')->orderBy('start_time')->get();
        $userId = $teacher->user_id;
        $schedules = Schedule::where('teacher_id', $userId)
            ->with(['subject', 'classroom', 'timeSlot'])
            ->get();

        $scheduleData = [];
        foreach ($schedules as $schedule) {
            $scheduleData[$schedule->day_of_week][$schedule->time_slot_id] = $schedule;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $subjects = Subject::where('status', 'active')->orderBy('subject_name')->get();
        $classrooms = Classroom::where('status', 'active')->orderBy('room_number')->get();

        return view('admin.schedules.view', compact(
            'teacher',
            'timeSlots',
            'scheduleData',
            'days',
            'subjects',
            'classrooms'
        ));
    }

    public function getScheduleData($scheduleId)
    {
        $schedule = Schedule::with(['subject', 'classroom', 'timeSlot'])
            ->findOrFail($scheduleId);

        return response()->json([
            'success' => true,
            'subject_id' => $schedule->subject_id,
            'classroom_id' => $schedule->classroom_id,
            'day_of_week' => $schedule->day_of_week,
            'time_slot_id' => $schedule->time_slot_id,
            'status' => $schedule->status,
            'subject_name' => $schedule->subject->subject_name,
            'room_number' => $schedule->classroom->room_number,
            'room_name' => $schedule->classroom->room_name,
            'slot_name' => $schedule->timeSlot->slot_name,
            'start_time' => $schedule->timeSlot->start_time,
            'end_time' => $schedule->timeSlot->end_time,
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'teacher_id'   => 'required|exists:users,id',
            'subject_id'   => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week'  => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'time_slot_id' => 'required|exists:time_slots,id',
            'status'       => 'nullable|in:active,inactive,cancelled',
        ]);
        $conflict = Schedule::where('teacher_id', $validated['teacher_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('time_slot_id', $validated['time_slot_id'])
            ->where('status', 'active')
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Schedule conflict: Teacher already has a schedule for this day and time slot.');
        }
        $roomConflict = Schedule::where('classroom_id', $validated['classroom_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('time_slot_id', $validated['time_slot_id'])
            ->where('status', 'active')
            ->where('teacher_id', '!=', $validated['teacher_id'])
            ->with(['teacher', 'subject'])
            ->first();
        if ($roomConflict) {
            $conflictUser    = $roomConflict->teacher;
            $conflictTeacher = Teacher::where('user_id', $conflictUser->id)->first();
            $fullName        = $conflictTeacher ? $conflictTeacher->name : $conflictUser->name;
            return back()->with('error', "Room conflict: This classroom is already booked on {$validated['day_of_week']} at this time by {$fullName} for {$roomConflict->subject->subject_name}.");
        }
        try {
            Schedule::create($validated + ['status' => $validated['status'] ?? 'active']);

            $teacher = Teacher::where('user_id', $validated['teacher_id'])->firstOrFail();

            return redirect()->route('admin.schedules.view', $teacher->id)
                ->with('success', 'Schedule added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding schedule: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $scheduleId){
        $schedule = Schedule::findOrFail($scheduleId);
        $validated = $request->validate([
            'subject_id'   => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'day_of_week'  => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'time_slot_id' => 'required|exists:time_slots,id',
            'status'       => 'required|in:active,inactive,cancelled',
        ]);

        try {
            $schedule->update($validated);
            $teacher = Teacher::where('user_id', $schedule->teacher_id)->firstOrFail();

            return redirect()->route('admin.schedules.view', $teacher->id)
                ->with('success', 'Schedule updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating schedule: ' . $e->getMessage());
        }
    }

    public function destroy($scheduleId)
    {
        try {
            $schedule = Schedule::findOrFail($scheduleId);
            $teacherId = $schedule->teacher_id;

            $schedule->delete();

            return redirect()->route('admin.schedules.view', $teacherId)
                ->with('success', 'Schedule deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting schedule: ' . $e->getMessage());
        }
    }
}
