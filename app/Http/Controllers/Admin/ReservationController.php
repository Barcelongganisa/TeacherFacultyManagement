<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomReservation;
use Illuminate\Http\Request;

class ReservationController extends AdminBaseController
{
    public function index()
    {
        $pending = RoomReservation::with(['teacher', 'classroom', 'timeSlot'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $all = RoomReservation::with(['teacher', 'classroom', 'timeSlot'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.reservations.index', compact('pending', 'all'));
    }
    
    public function approve($id)
    {
        try {
            $reservation = RoomReservation::findOrFail($id);
            $reservation->update(['status' => 'approved']);
            
            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error approving reservation: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string',
        ]);
        
        try {
            $reservation = RoomReservation::findOrFail($id);
            $reservation->update([
                'status' => 'rejected',
                'notes' => $validated['reason'] ?? null,
            ]);
            
            return redirect()->route('admin.reservations.index')
                ->with('success', 'Reservation rejected successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error rejecting reservation: ' . $e->getMessage());
        }
    }
}