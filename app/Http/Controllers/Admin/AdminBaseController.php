<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminBaseController extends Controller
{
    protected $user;
    protected $userId;

    protected function initUser(): void
    {
        $this->user = Auth::user();
        $this->userId = Auth::id();
    }
}