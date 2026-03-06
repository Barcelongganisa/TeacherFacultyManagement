<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Campus;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['classroom.campus', 'user']);
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('purpose', 'like', '%' . $request->search . '%')
                  ->orWhereHas('classroom', function($cq) use ($request) {
                      $cq->where('room_number', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('user', function($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by campus
        if ($request->filled('campus_id')) {
            $query->whereHas('classroom', function($q) use ($request) {
                $q->where('campus_id', $request->campus_id);
            });
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('reserved_date', $request->date);
        }
        
        // Date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('reserved_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('reserved_date', '<=', $request->end_date);
        }
        
        $reservations = $query->latest()->paginate(15);
        
        // Get statistics
        $totalReservations = Reservation::count();
        $pendingCount = Reservation::where('status', 'pending')->count();
        $approvedCount = Reservation::where('status', 'approved')->count();
        $todayCount = Reservation::whereDate('reserved_date', today())->count();
        
        // Get campuses for filter dropdown
        $campuses = Campus::where('status', 'active')->get();
        
        return view('superadmin.reservations.index', compact(
            'reservations',
            'totalReservations',
            'pendingCount',
            'approvedCount',
            'todayCount',
            'campuses'
        ));
    }

    /**
     * Approve a reservation.
     */
    public function approve(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be approved.');
        }

        // Check for conflicts
        $conflicts = Reservation::where('classroom_id', $reservation->classroom_id)
            ->where('id', '!=', $reservation->id)
            ->where('reserved_date', $reservation->reserved_date)
            ->where('status', 'approved')
            ->where(function($q) use ($reservation) {
                $q->whereBetween('start_time', [$reservation->start_time, $reservation->end_time])
                  ->orWhereBetween('end_time', [$reservation->start_time, $reservation->end_time])
                  ->orWhere(function($q2) use ($reservation) {
                      $q2->where('start_time', '<=', $reservation->start_time)
                         ->where('end_time', '>=', $reservation->end_time);
                  });
            })
            ->exists();

        if ($conflicts) {
            return back()->with('error', 'Cannot approve: Time conflict with existing approved reservation.');
        }

        $reservation->status = 'approved';
        $reservation->approved_by = auth()->id();
        $reservation->approved_at = now();
        $reservation->save();

        // Log the activity
        activity()
            ->performedOn($reservation)
            ->causedBy(auth()->user())
            ->withProperties(['classroom' => $reservation->classroom->room_number])
            ->log('approved_reservation');

        return back()->with('success', 'Reservation approved successfully.');
    }

    /**
     * Reject a reservation.
     */
    public function reject(Request $request, Reservation $reservation)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be rejected.');
        }

        $reservation->status = 'rejected';
        $reservation->rejection_reason = $request->rejection_reason;
        $reservation->rejected_by = auth()->id();
        $reservation->rejected_at = now();
        $reservation->save();

        // Log the activity
        activity()
            ->performedOn($reservation)
            ->causedBy(auth()->user())
            ->withProperties([
                'classroom' => $reservation->classroom->room_number,
                'reason' => $request->rejection_reason
            ])
            ->log('rejected_reservation');

        return back()->with('success', 'Reservation rejected successfully.');
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Reservation $reservation)
    {
        if (!in_array($reservation->status, ['pending', 'approved'])) {
            return back()->with('error', 'This reservation cannot be cancelled.');
        }

        $reservation->status = 'cancelled';
        $reservation->cancelled_by = auth()->id();
        $reservation->cancelled_at = now();
        $reservation->save();

        // Log the activity
        activity()
            ->performedOn($reservation)
            ->causedBy(auth()->user())
            ->log('cancelled_reservation');

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * View reservation details.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['classroom.campus', 'user', 'approvedBy', 'rejectedBy', 'cancelledBy']);
        
        return response()->json($reservation);
    }

    /**
     * Export reservations data.
     */
    public function export(Request $request)
    {
        $query = Reservation::with(['classroom.campus', 'user']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('reserved_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('reserved_date', '<=', $request->end_date);
        }
        
        $reservations = $query->get();
        
        // Generate CSV
        $filename = 'reservations_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, [
            'ID', 'Date', 'Start Time', 'End Time', 'Campus', 'Room', 
            'Reserved By', 'Email', 'Purpose', 'Status', 'Approved By', 
            'Approved At', 'Rejection Reason', 'Created At'
        ]);
        
        // Add data
        foreach ($reservations as $res) {
            fputcsv($handle, [
                $res->id,
                $res->reserved_date->format('Y-m-d'),
                $res->start_time,
                $res->end_time,
                $res->classroom->campus->campus_name ?? 'N/A',
                $res->classroom->room_number ?? 'N/A',
                $res->user->name ?? 'N/A',
                $res->user->email ?? 'N/A',
                $res->purpose,
                $res->status,
                $res->approvedBy->name ?? 'N/A',
                $res->approved_at ? $res->approved_at->format('Y-m-d H:i:s') : 'N/A',
                $res->rejection_reason ?? 'N/A',
                $res->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($handle);
        exit;
    }

    /**
     * Check availability for a room.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'reserved_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'exclude_id' => 'nullable|exists:reservations,id'
        ]);

        $query = Reservation::where('classroom_id', $request->classroom_id)
            ->where('reserved_date', $request->reserved_date)
            ->where('status', 'approved')
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            });

        if ($request->filled('exclude_id')) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $conflicts = $query->with('user')->get();

        return response()->json([
            'available' => $conflicts->isEmpty(),
            'conflicts' => $conflicts
        ]);
    }
}