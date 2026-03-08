<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'campus_id',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}