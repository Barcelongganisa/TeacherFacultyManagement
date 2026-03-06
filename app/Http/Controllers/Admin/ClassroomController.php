<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends AdminBaseController
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $building = $request->get('building', '');
        $type = $request->get('type', '');
        $status = $request->get('status', '');
        
        $query = Classroom::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_name', 'like', "%{$search}%")
                  ->orWhere('building', 'like', "%{$search}%");
            });
        }
        
        if ($building) {
            $query->where('building', $building);
        }
        
        if ($type) {
            $query->where('room_type', $type);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $classrooms = $query->orderBy('building')->orderBy('room_number')->paginate(15);
        
        $buildings = Classroom::distinct('building')->pluck('building');
        
        return view('admin.classrooms.index', compact('classrooms', 'search', 'building', 'type', 'status', 'buildings'));
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $building = $request->get('building', '');
        $type = $request->get('type', '');
        $status = $request->get('status', '');
        
        $query = Classroom::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_name', 'like', "%{$search}%")
                  ->orWhere('building', 'like', "%{$search}%");
            });
        }
        
        if ($building) {
            $query->where('building', $building);
        }
        
        if ($type) {
            $query->where('room_type', $type);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $classrooms = $query->orderBy('building')->orderBy('room_number')->get();
        
        return response()->json(['classrooms' => $classrooms]);
    }
    
    public function create()
    {
        return view('admin.classrooms.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string',
            'room_name' => 'required|string',
            'building' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'room_type' => 'nullable|string|in:classroom,laboratory,lecture room,social hall',
            'equipment' => 'nullable|string',
            'floor' => 'nullable|string',
        ]);
        
        // Check for duplicate
        $exists = Classroom::where('room_number', $validated['room_number'])
            ->where('building', $validated['building'] ?? '')
            ->exists();
            
        if ($exists) {
            $buildingText = !empty($validated['building']) ? "in building '{$validated['building']}'" : "with no building specified";
            return back()->with('error', "A classroom with room number '{$validated['room_number']}' already exists {$buildingText}.")->withInput();
        }
        
        try {
            Classroom::create($validated + ['status' => 'active']);
            
            return redirect()->route('admin.classrooms.index')
                ->with('success', 'Classroom added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding classroom: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('admin.classrooms.edit', compact('classroom'));
    }
    
    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        
        $validated = $request->validate([
            'room_number' => 'required|string',
            'room_name' => 'required|string',
            'building' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'room_type' => 'nullable|string|in:classroom,laboratory,lecture room,social hall',
            'equipment' => 'nullable|string',
            'floor' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
        ]);
        
        // Check for duplicate
        $exists = Classroom::where('room_number', $validated['room_number'])
            ->where('building', $validated['building'] ?? '')
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            $buildingText = !empty($validated['building']) ? "in building '{$validated['building']}'" : "with no building specified";
            return back()->with('error', "A classroom with room number '{$validated['room_number']}' already exists {$buildingText}.")->withInput();
        }
        
        try {
            $classroom->update($validated);
            
            return redirect()->route('admin.classrooms.index')
                ->with('success', 'Classroom updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating classroom: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);
            
            // Check if classroom is being used
            if ($classroom->schedules()->count() > 0) {
                return back()->with('error', 'Cannot delete classroom. It has been used in schedules.');
            }
            
            $classroom->delete();
            
            return redirect()->route('admin.classrooms.index')
                ->with('success', 'Classroom deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting classroom: ' . $e->getMessage());
        }
    }
}