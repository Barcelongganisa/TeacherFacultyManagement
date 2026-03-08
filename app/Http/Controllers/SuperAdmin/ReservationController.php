<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Campus;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with([
            'classroom.campus',
            'teacher',
            'timeSlot'
        ]);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('purpose', 'like', '%' . $request->search . '%')
                  ->orWhereHas('classroom', function($cq) use ($request) {
                      $cq->where('room_number', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('teacher', function($tq) use ($request) {
                      $tq->where('name', 'like', '%' . $request->search . '%')
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
            $query->whereHas('classroom', function($cq) use ($request) {
                $cq->where('campus_id', $request->campus_id);
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        $reservations = $query->latest()->paginate(15);

        $totalReservations = Reservation::count();
        $pendingCount = Reservation::where('status', 'pending')->count();
        $approvedCount = Reservation::where('status', 'approved')->count();
        $todayCount = Reservation::whereDate('reservation_date', today())->count();

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

    public function approve(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be approved.');
        }

        $conflict = Reservation::where('classroom_id', $reservation->classroom_id)
            ->where('reservation_date', $reservation->reservation_date)
            ->where('time_slot_id', $reservation->time_slot_id)
            ->where('status', 'approved')
            ->where('id', '!=', $reservation->id)
            ->exists();

        if ($conflict) {
            return back()->with('error', 'This room is already reserved for that time slot.');
        }

        $reservation->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Reservation approved successfully.');
    }

    public function reject(Request $request, Reservation $reservation)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be rejected.');
        }

        $reservation->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_by' => auth()->id(),
            'rejected_at' => now()
        ]);

        return back()->with('success', 'Reservation rejected successfully.');
    }

    public function cancel(Reservation $reservation)
    {
        if (!in_array($reservation->status, ['pending', 'approved'])) {
            return back()->with('error', 'This reservation cannot be cancelled.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_by' => auth()->id(),
            'cancelled_at' => now()
        ]);

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load([
            'classroom.campus',
            'teacher',
            'timeSlot',
            'approvedBy',
            'rejectedBy',
            'cancelledBy'
        ]);

        return response()->json([
            'id' => $reservation->id,
            'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
            'time_slot' => $reservation->timeSlot ? [
                'start' => $reservation->timeSlot->start_time,
                'end' => $reservation->timeSlot->end_time,
                'full' => $reservation->timeSlot->start_time . ' - ' . $reservation->timeSlot->end_time
            ] : null,
            'purpose' => $reservation->purpose,
            'notes' => $reservation->notes,
            'status' => $reservation->status,

            'user' => [
                'name' => $reservation->teacher->name ?? 'N/A',
                'email' => $reservation->teacher->email ?? 'N/A',
                'campus' => $reservation->teacher->campus ?? 'N/A'
            ],

            'classroom' => [
                'room_number' => $reservation->classroom->room_number,
                'campus' => $reservation->classroom->campus ? [
                    'id' => $reservation->classroom->campus->id,
                    'name' => $reservation->classroom->campus->campus_name
                ] : null
            ],

            'approved_by' => $reservation->approvedBy ? [
                'name' => $reservation->approvedBy->name,
                'email' => $reservation->approvedBy->email
            ] : null,

            'approved_at' => $reservation->approved_at,
            'rejection_reason' => $reservation->rejection_reason,
            'created_at' => $reservation->created_at
        ]);
    }

    public function export()
    {
        $reservations = Reservation::with([
            'classroom.campus',
            'teacher',
            'timeSlot'
        ])->get();

        $filename = 'reservations_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, [
            'ID','Date','Time Slot','Room Campus','Room',
            'Reserved By','Email','Teacher Campus','Purpose','Status'
        ]);

        foreach ($reservations as $res) {
            fputcsv($handle, [
                $res->id,
                $res->reservation_date,
                $res->timeSlot ? $res->timeSlot->start_time . ' - ' . $res->timeSlot->end_time : 'N/A',
                $res->classroom->campus->campus_name ?? 'N/A',
                $res->classroom->room_number ?? 'N/A',
                $res->teacher->name ?? 'N/A',
                $res->teacher->email ?? 'N/A',
                $res->teacher->campus ?? 'N/A',
                $res->purpose,
                $res->status
            ]);
        }

        fclose($handle);
        exit;
    }
}