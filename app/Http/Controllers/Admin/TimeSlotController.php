<?php

namespace App\Http\Controllers\Admin;

use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends AdminBaseController
{
    public function index()
    {
        $timeSlots = TimeSlot::orderBy('start_time')->get();
        return view('admin.time-slots.index', compact('timeSlots'));
    }
    
    public function create()
    {
        return view('admin.time-slots.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
        try {
            TimeSlot::create($validated + ['status' => 'active']);
            
            return redirect()->route('admin.time-slots.index')
                ->with('success', 'Time slot added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding time slot: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        return view('admin.time-slots.edit', compact('timeSlot'));
    }
    
    public function update(Request $request, $id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        
        $validated = $request->validate([
            'slot_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ]);
        
        try {
            $timeSlot->update($validated);
            
            return redirect()->route('admin.time-slots.index')
                ->with('success', 'Time slot updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating time slot: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $timeSlot = TimeSlot::findOrFail($id);
            $timeSlot->delete();
            
            return redirect()->route('admin.time-slots.index')
                ->with('success', 'Time slot deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting time slot: ' . $e->getMessage());
        }
    }
}