<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher   = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        $userId= $user->id; 

        $timeSlots = DB::table('time_slots')
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        $scheduleData = [];

        if ($teacherId) {
            $schedules = DB::table('schedules as s')
                ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                ->join('classrooms as c',  's.classroom_id', '=', 'c.id')
                ->where('s.teacher_id', $userId)
                ->where('s.status', 'active')
                ->select( 's.day_of_week', 's.time_slot_id','sub.subject_code', 'sub.subject_name', 'c.room_number', 'c.room_name')
                ->get();

            foreach ($schedules as $schedule) {
                $scheduleData[$schedule->day_of_week][$schedule->time_slot_id] = $schedule;
            }
            $reservations = DB::table('reservations as r')
                ->join('classrooms as c',  'r.classroom_id', '=', 'c.id')
                ->join('time_slots as ts', 'r.time_slot_id',  '=', 'ts.id')
                ->where('r.teacher_id', $teacherId)
                ->where('r.status', 'approved')
                ->select( 'r.id', 'r.reservation_date','r.notes','ts.id as time_slot_id','ts.slot_name',
                    'ts.start_time','ts.end_time','c.room_number','c.room_name')->get();

            foreach ($reservations as $res) {
                $day = Carbon::parse($res->reservation_date)->format('l');
                if (!isset($scheduleData[$day][$res->time_slot_id])) {
                    $scheduleData[$day][$res->time_slot_id] = $res;
                }
            }
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('teacher.schedule', compact('timeSlots', 'scheduleData', 'days', 'teacherId', 'userId'));
    }
}