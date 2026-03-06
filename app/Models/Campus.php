<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        return $this->hasMany(User::class, 'campus_id'); // assuming 'campus_id' is in users table
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Get all reservations for this campus (through classrooms).
     */
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Classroom::class);
    }

    /**
     * Get all admins for this campus.
     */
    public function admins()
    {
        return $this->users()->where('role', 'admin');
    }

    /**
     * Get all teachers for this campus.
     */
    public function teachers()
    {
        return $this->users()->where('role', 'teacher');
    }

    /**
     * Get all students for this campus.
     */
    public function students()
    {
        return $this->users()->where('role', 'student');
    }

    /**
     * Scope a query to only include active campuses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive campuses.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the campus statistics.
     */
    public function getStatistics()
    {
        return [
            'total_users' => $this->users()->count(),
            'admins' => $this->admins()->count(),
            'teachers' => $this->teachers()->count(),
            'students' => $this->students()->count(),
            'classrooms' => $this->classrooms()->count(),
            'reservations' => $this->reservations()->count()
        ];
    }
}