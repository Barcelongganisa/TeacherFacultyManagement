<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('campus');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $users = $query->paginate(15);
        $campuses = Campus::where('status', 'active')->get();
        
        return view('superadmin.users.index', compact('users', 'campuses'));
    }
    
    public function create()
    {
        $campuses = Campus::where('status', 'active')->get();
        return view('superadmin.users.create', compact('campuses'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,teacher,student',
            'campus_id' => 'nullable|exists:campuses,id',
            'status' => 'required|in:active,inactive',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create the user
        User::create($validated);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User created successfully.');
    }
    
    public function edit(User $user)
    {
        $campuses = Campus::where('status', 'active')->get();
        return view('superadmin.users.edit', compact('user', 'campuses'));
    }
    
    public function update(Request $request, User $user)
    {
        // 1️⃣ Validate all fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,admin,teacher,student',
            'campus_id' => 'nullable|exists:campuses,id',
            'status' => 'required|in:active,inactive,pending',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verified' => 'boolean',
        ]);

        // 2️⃣ Handle password
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // don't change current password
        }

        // 3️⃣ Handle email verification
        $validated['email_verified_at'] = $request->has('email_verified') ? now() : null;

        // 4️⃣ Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        }

        // 5️⃣ Update user
        $user->update($validated);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User updated successfully.');
    }
    
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        
        return back()->with('success', 'User status updated.');
    }
    
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return back()->with('success', 'Password reset successfully.');
    }
}