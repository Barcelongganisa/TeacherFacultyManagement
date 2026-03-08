<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'time_slot_id',
        'date',
        'start_time',
        'end_time',
        'status'
    ];

    protected $casts = [
    'reservation_date' => 'date', // This converts to Carbon
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    ];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Add this relationship
    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
