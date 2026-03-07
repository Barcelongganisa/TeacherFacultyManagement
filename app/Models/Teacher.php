<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'employee_id',
        'user_id',
        'name',
        'department',
        'specialization',
        'email',
        'phone',
        'profile_image',
        'password',
        'status',
        'campus'
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