<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'date',
        'start_time',
        'end_time',
        'status'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
