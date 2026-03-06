<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvailabilityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get teacher ID
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        
        $message = session('message');
        
        $timeSlots = DB::table('time_slots')
            ->orderBy('start_time')
            ->get();
        
        $availability = [];
        if ($teacherId) {
            $avail = DB::table('teacher_availability')
                ->where('teacher_id', $teacherId)
                ->get();
            
            foreach ($avail as $a) {
                $availability[$a->day_of_week][$a->time_slot_id] = $a->status;
            }
        }
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        return view('teacher.availability', compact('timeSlots', 'availability', 'days', 'message'));
    }
    
    public function setAvailability(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|string',
            'time_slot_id' => 'required|integer',
            'status' => 'required|in:available,unavailable'
        ]);
        
        $user = Auth::user();
        
        // Get teacher ID
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        $teacherId = $teacher->id ?? null;
        
        if (!$teacherId) {
            return redirect()->route('teacher.availability')->with('message', 'Teacher record not found.');
        }
        
        // Upsert availability
        DB::table('teacher_availability')->updateOrInsert(
            [
                'teacher_id' => $teacherId,
                'day_of_week' => $request->day_of_week,
                'time_slot_id' => $request->time_slot_id
            ],
            [
                'status' => $request->status,
                'updated_at' => now()
            ]
        );
        
        return redirect()->route('teacher.availability')->with('message', 'Availability updated successfully.');
    }
}