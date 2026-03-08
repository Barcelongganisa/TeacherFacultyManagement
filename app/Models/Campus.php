<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department; 

class Campus extends Model
{
    protected $fillable = [
        'name',
        'campus_name',
        'campus_code',
        'address',
        'contact_email',
        'contact_phone',
        'description',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'campus_id');
    }

    // 👇 add this
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Classroom::class);
    }

    public function admins()
    {
        return $this->users()->where('role', 'admin');
    }

    public function teachers()
    {
        return $this->users()->where('role', 'teacher');
    }

    public function students()
    {
        return $this->users()->where('role', 'student');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function getStatistics()
    {
        return [
            'total_users'  => $this->users()->count(),
            'admins'       => $this->admins()->count(),
            'teachers'     => $this->teachers()->count(),
            'students'     => $this->students()->count(),
            'classrooms'   => $this->classrooms()->count(),
            'reservations' => $this->reservations()->count(),
            'departments'  => $this->departments()->count(), 
        ];
    }
}