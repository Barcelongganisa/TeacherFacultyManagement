<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'teacher_id', 'subject_id', 'classroom_id',
        'day_of_week', 'time_slot_id', 'semester',
        'academic_year', 'status', 'notes'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function timeSlot() {
        return $this->belongsTo(TimeSlot::class);
    }
}