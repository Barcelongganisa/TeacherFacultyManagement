<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'room_number', 'room_name', 'building',
        'capacity', 'room_type', 'equipment', 'floor', 'status'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}