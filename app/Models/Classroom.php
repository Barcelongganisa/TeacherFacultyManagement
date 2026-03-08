<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'room_number', 'room_name', 'building',
        'capacity', 'room_type', 'equipment', 'floor', 'status', 'campus_id'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

        public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}