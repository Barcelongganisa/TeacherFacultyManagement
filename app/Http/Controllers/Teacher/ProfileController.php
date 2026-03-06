<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $userData = DB::table('users')->where('id', $user->id)->first();
        $teacher = DB::table('teachers')->where('user_id', $user->id)->first();
        
        if (!$teacher) {
            $teacher = (object) ['phone' => '', 'department' => ''];
        }
        
        return view('teacher.profile', compact('user', 'userData', 'teacher'));
    }
        
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255', // Changed from first_name/last_name
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        $user = Auth::user();
        $userId = $user->id;
        
        // Handle profile image upload
        $profileImage = $user->profile_image;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();
            $newName = 'profile_' . $userId . '_' . time() . '.' . $ext;
            $file->move(public_path('assets/uploads'), $newName);
            
            // Delete old image if exists
            if ($profileImage && file_exists(public_path('assets/uploads/' . $profileImage))) {
                unlink(public_path('assets/uploads/' . $profileImage));
            }
            
            $profileImage = $newName;
        }
        
        // Update users table
        $userUpdate = [
            'name' => $request->full_name, // Just use full_name directly
            'email' => $request->email,
            'profile_image' => $profileImage,
        ];
        
        if ($request->filled('password')) {
            $userUpdate['password'] = Hash::make($request->password);
        }
        
        DB::table('users')->where('id', $userId)->update($userUpdate);
        
        // Check if teacher record exists
        $teacherExists = DB::table('teachers')->where('user_id', $userId)->exists();
        
        // For teachers table, we can either:
        // Option A: Store full name in first_name and leave last_name empty/null
        if ($teacherExists) {
            DB::table('teachers')
                ->where('user_id', $userId)
                ->update([
                    'first_name' => $request->full_name, // Store full name in first_name
                    'last_name' => '', // Leave empty or null
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('teachers')->insert([
                'user_id' => $userId,
                'first_name' => $request->full_name,
                'last_name' => '',
                'phone' => $request->phone,
                'department' => $request->department,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return redirect()->route('teacher.profile.edit')->with('success', 'Profile updated successfully!');
    }
}