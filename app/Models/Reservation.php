<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'teacher_id',
        'room',
        'date',
        'start_time',
        'end_time',
        'status'
    ];
}
