<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'start_time', 'end_time', 'label'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}