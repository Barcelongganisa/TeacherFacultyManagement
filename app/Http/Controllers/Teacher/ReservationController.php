<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(){
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;

        $message = session('message');
        $rooms = DB::table('classrooms')
            ->where('status', 'active')
            ->select('id', 'room_number', 'room_name', 'capacity', 'room_type')
            ->get();

        $timeSlots = DB::table('time_slots')
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        $myReservations = [];
        if ($teacherId) {
            $myReservations = DB::table('reservations as r')
                ->join('classrooms as c', 'r.classroom_id', '=', 'c.id')
                ->join('time_slots as ts', 'r.time_slot_id', '=', 'ts.id')
                ->where('r.teacher_id', $teacherId)
                ->orderBy('r.reservation_date', 'desc')
                ->orderBy('ts.start_time')
                ->select('r.*', 'c.room_number', 'c.room_name', 'ts.slot_name')
                ->get();
        }
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('teacher.reservations', compact('rooms', 'timeSlots', 'myReservations', 'message', 'days'));
    }

    public function store(Request $request){
        $request->validate([
            'classroom_id'     => 'required|integer',
            'reservation_date' => 'required|date',
            'time_slot_id'     => 'required|integer',
            'notes'            => 'nullable|string',
            'day_of_week'      => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        if (!$teacherId) {
            return redirect()->route('teacher.reservations')->with('error', 'Teacher record not found.');
        }
        $subjectID = DB::table('teacher_subjects')
            ->where('teacher_id', $teacherId)
            ->value('subject_id');

        if (!$subjectID) {
            return redirect()->route('teacher.reservations')->with('error', 'No subject assigned to this teacher.');
        }

        $existing = DB::table('reservations')
            ->where('classroom_id', $request->classroom_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('time_slot_id', $request->time_slot_id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return redirect()->route('teacher.reservations')->with('error', 'This time slot is already reserved.');
        }
        DB::transaction(function () use ($request, $teacherId, $subjectID, $user) {
            DB::table('reservations')->insert([
                'teacher_id'       => $teacherId,
                'classroom_id'     => $request->classroom_id,
                'reservation_date' => $request->reservation_date,
                'time_slot_id'     => $request->time_slot_id,
                'notes'            => $request->notes,
                'status'           => 'pending',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            DB::table('schedules')->insert([
                'teacher_id'   => $user->id,
                'subject_id'   => $subjectID,
                'classroom_id' => $request->classroom_id,
                'time_slot_id' => $request->time_slot_id,
                'day_of_week'  => $request->day_of_week,
                'day'       => $request->day_of_week,
                'status'       => 'active',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        });
        return redirect()->route('teacher.reservations')->with('success', 'Reservation request submitted successfully.');
    }
}
