<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = [];
        $unreadCount = 0;
        $announcements = collect(Announcement::all());

        return view('student.announcements', compact('announcements', 'unreadCount'));
    }

    public function markRead(Request $request)
    {
        return back();
    }
}
