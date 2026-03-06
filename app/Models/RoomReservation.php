<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\TimeSlot;

class RoomReservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'time_slot_id',
        'date',
        'status',
        'notes'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}