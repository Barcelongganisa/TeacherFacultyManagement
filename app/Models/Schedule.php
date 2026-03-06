<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\TimeSlot;

class Schedule extends Model
{
    protected $fillable = [
        'teacher_id', 'subject_id', 'classroom_id',
        'day_of_week', 'time_slot_id', 'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
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