<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('superadmin.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);
        return redirect()->route('superadmin.profile.edit')
                         ->with('success', 'Profile updated successfully.');
    }

    /**
     * Change the user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log the activity
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('changed_password');

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Update profile image only.
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old image if exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('profile_image')->store('profile-images', 'public');
        $user->profile_image = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile image updated successfully.',
            'image_url' => Storage::url($path)
        ]);
    }

    /**
     * Remove profile image.
     */
    public function removeImage()
    {
        $user = Auth::user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile image removed successfully.'
        ]);
    }

    /**
     * Get activity logs for the user.
     */
    public function activityLogs()
    {
        $user = Auth::user();
        
        $logs = activity()
            ->causedBy($user)
            ->withProperties()
            ->latest()
            ->paginate(20);

        return view('superadmin.profile.activity', compact('logs'));
    }

    /**
     * Get login history.
     */
    public function loginHistory()
    {
        $user = Auth::user();
        
        // You'll need a login_logs table for this
        $logins = DB::table('login_logs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('superadmin.profile.logins', compact('logins'));
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'reservation_reminders' => 'boolean',
            'system_alerts' => 'boolean',
            'newsletter' => 'boolean'
        ]);

        // Assuming you have a notifications_settings column or a separate settings table
        $user->notification_settings = json_encode($validated);
        $user->save();

        return back()->with('success', 'Notification preferences updated.');
    }

    /**
     * Two-factor authentication setup.
     */
    public function setupTwoFactor(Request $request)
    {
        $user = Auth::user();

        if ($user->two_factor_secret) {
            // Disable 2FA
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->save();

            return back()->with('success', 'Two-factor authentication disabled.');
        }

        // Enable 2FA - you'll need to implement this with a package like laravel/fortify
        // This is just a placeholder
        $user->two_factor_secret = encrypt('secret-key');
        $user->two_factor_recovery_codes = encrypt(json_encode(['code1', 'code2']));
        $user->save();

        return back()->with('success', 'Two-factor authentication enabled.');
    }

    /**
     * Export user data (GDPR compliance).
     */
    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user->toArray(),
            'activities' => activity()->causedBy($user)->get()->toArray(),
            // Add more user data as needed
        ];

        $filename = 'user-data-' . $user->id . '-' . date('Y-m-d') . '.json';
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Delete account (GDPR compliance).
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|string|in:DELETE',
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();

        // Log the activity before deletion
        activity()
            ->causedBy($user)
            ->log('deleted_account');

        // Logout the user
        Auth::logout();

        // Delete the user
        $user->delete();

        return redirect()->route('login')->with('success', 'Your account has been permanently deleted.');
    }
}