<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'content', 'user_id', 'status'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}