<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email',
        'phone', 'department', 'qualification',
        'hire_date', 'bio', 'profile_image', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}