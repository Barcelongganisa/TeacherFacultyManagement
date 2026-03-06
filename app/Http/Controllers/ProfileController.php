<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $profileImageUrl = $user->profile_image
            ? asset('storage/profile_images/' . $user->profile_image)
            : asset('images/default_profile.png'); // fallback

        // Append timestamp for cache-busting
        $profileImageUrl .= '?v=' . $user->updated_at->timestamp;

        return view('student.profile', [
            'user' => $user,
            'profileImageUrl' => $profileImageUrl,
        ]);
    }

    // Handle profile update form submission
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            // Store in 'profile_images' folder (inside storage/app/public)
            $image = $request->file('profile_image')->store('profile_images', 'public');

            // Optional: Delete old image to save space
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $image;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('student.profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
