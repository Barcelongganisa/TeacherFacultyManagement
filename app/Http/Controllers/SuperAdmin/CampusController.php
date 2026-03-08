<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampusController extends Controller
{
    /**
     * Display a listing of campuses.
     */
    public function index(Request $request)
    {
        $query = Campus::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('campus_name', 'like', '%' . $request->search . '%')
                  ->orWhere('campus_code', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Load counts
        $campuses = $query->withCount([
            'users as admins_count' => function($q) {
                $q->where('role', 'admin');
            },
            'users as teachers_count' => function($q) {
                $q->where('role', 'teacher');
            },
            'users as students_count' => function($q) {
                $q->where('role', 'student');
            },
            'classrooms',
            'reservations'
        ])->paginate(15);
        
        // Get totals for statistics
        $totalClassrooms = Classroom::count();
        $activeCampuses = Campus::where('status', 'active')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        
        return view('superadmin.campuses.index', compact(
            'campuses', 
            'totalClassrooms', 
            'activeCampuses', 
            'totalAdmins'
        ));
    }

    /**
     * Show the form for creating a new campus.
     */
    public function create()
    {
        return view('superadmin.campuses.create');
    }

    /**
     * Store a newly created campus.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_name' => 'required|string|max:255',
            'campus_code' => 'required|string|max:50|unique:campuses',
            'address' => 'required|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $campus = Campus::create($validated);

        return redirect()->route('superadmin.campuses.index')
            ->with('success', 'Campus created successfully.');
    }

    /**
     * Display the specified campus.
     */
    public function show(Campus $campus)
    {
        // Load relationships with counts
        $campus->load([
            'admins',
            'classrooms' => function ($query) {
                $query->withCount('reservations');
            }
        ]);
        
        // Get counts
        $adminsCount = $campus->users()->where('role', 'admin')->count();
        $teachersCount = $campus->users()->where('role', 'teacher')->count();
        $studentsCount = $campus->users()->where('role', 'student')->count();
        $classroomsCount = $campus->classrooms()->count();
        $reservationsCount = $campus->reservations()->count();
        
        // Recent reservations
        $recentReservations = $campus->reservations()
            ->with(['classroom', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $campus->loadCount([
            'admins',
            'teachers',
            'students',
            'classrooms',
            'reservations'
        ]);
        
        return view('superadmin.campuses.show', compact(
            'campus', 
            'adminsCount', 
            'teachersCount', 
            'studentsCount', 
            'classroomsCount', 
            'reservationsCount',
            'recentReservations'
        ));
    }

    /**
     * Show the form for editing the specified campus.
     */
    public function edit(Campus $campus)
    {
        return view('superadmin.campuses.edit', compact('campus'));
    }

    /**
     * Update the specified campus.
     */
    public function update(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'campus_name' => 'required|string|max:255',
            'campus_code' => 'required|string|max:50|unique:campuses,campus_code,' . $campus->id,
            'address' => 'required|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $campus->update($validated);
        
        return redirect()->route('superadmin.campuses.index')
                     ->with('success', 'Campus updated successfully.');
    }

    /**
     * Remove the specified campus.
     */
    public function destroy(Campus $campus)
    {
        // Check if campus has users
        if ($campus->users()->count() > 0) {
            return back()->with('error', 'Cannot delete campus with assigned users.');
        }
        
        // Check if campus has classrooms
        if ($campus->classrooms()->count() > 0) {
            return back()->with('error', 'Cannot delete campus with existing classrooms.');
        }
        
        // Check if campus has reservations
        if ($campus->reservations()->count() > 0) {
            return back()->with('error', 'Cannot delete campus with reservation history.');
        }

        // Log the activity before deletion
        activity()
            ->performedOn($campus)
            ->causedBy(auth()->user())
            ->log('deleted_campus');

        $campus->delete();

        return redirect()->route('superadmin.campuses.index')
            ->with('success', 'Campus deleted successfully.');
    }

    /**
     * Toggle campus status.
     */
    public function toggleStatus(Campus $campus)
    {
        $campus->status = $campus->status === 'active' ? 'inactive' : 'active';
        $campus->save();

        $status = $campus->status === 'active' ? 'activated' : 'deactivated';

        // Log the activity
        activity()
            ->performedOn($campus)
            ->causedBy(auth()->user())
            ->log($status . '_campus');

        return back()->with('success', "Campus {$status} successfully.");
    }

    /**
     * Export campuses data.
     */
    public function export(Request $request)
    {
        $query = Campus::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $campuses = $query->get();
        
        // Generate CSV
        $filename = 'campuses_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, ['ID', 'Campus Name', 'Campus Code', 'Address', 'Contact Email', 'Contact Phone', 'Status', 'Created At']);
        
        // Add data
        foreach ($campuses as $campus) {
            fputcsv($handle, [
                $campus->id,
                $campus->campus_name,
                $campus->campus_code,
                $campus->address,
                $campus->contact_email,
                $campus->contact_phone,
                $campus->status,
                $campus->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($handle);
        exit;
    }
}