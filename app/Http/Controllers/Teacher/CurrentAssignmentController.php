<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurrentAssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get teacher ID
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        
        $currentAssignment = null;
        
        if ($teacherId) {
            $currentAssignment = DB::table('schedules as s')
                ->join('time_slots as ts', 's.time_slot_id', '=', 'ts.id')
                ->join('subjects as sub', 's.subject_id', '=', 'sub.id')
                ->join('classrooms as c', 's.classroom_id', '=', 'c.id')
                ->where('s.teacher_id', $teacherId)
                ->where('s.status', 'active')
                ->where('s.day_of_week', date('l'))
                ->whereTime('ts.start_time', '<=', now()->format('H:i:s'))
                ->whereTime('ts.end_time', '>=', now()->format('H:i:s'))
                ->select('sub.subject_name', 'c.room_number', 'c.room_name', 'ts.start_time', 'ts.end_time')
                ->first();
        }
        
        return view('teacher.current', compact('currentAssignment'));
    }
}