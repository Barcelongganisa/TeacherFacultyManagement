<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends AdminBaseController
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);
        
        try {
            $data = ['email' => $validated['email']];
            
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                
                // Store new image
                $path = $request->file('profile_image')->store('profiles', 'public');
                $data['profile_image'] = $path;
            }
            
            // Handle password update
            if (!empty($validated['new_password'])) {
                $data['password'] = Hash::make($validated['new_password']);
            }
            
            $user->update($data);
            
            return redirect()->route('admin.profile.edit')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
}