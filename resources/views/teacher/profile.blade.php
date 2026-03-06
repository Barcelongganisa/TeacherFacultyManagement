@extends('teacher.layouts.app')

@section('title', 'Edit Profile')

@section('page-header')
    <div class="page-header">
        <h1>Edit My Profile</h1>
        <p>Update your personal information and account settings</p>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Profile Image -->
                        <div class="mb-4">
                            <label class="form-label">Profile Image</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @php
                                        $profileImage = $user->profile_image 
                                            ? asset('storage/'.$user->profile_image) 
                                            : asset('assets/img/default-avatar.png');
                                    @endphp
                                    <img src="{{ $profileImage }}"
                                        alt="Profile" 
                                        class="profile-preview"
                                        style="width:100px; height:100px; border-radius:50%; object-fit:cover; border: 3px solid var(--primary-green);">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*" id="profileImageInput">
                                    <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max 5MB)</small>
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                   value="{{ old('full_name', $userData->name ?? '') }}" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $userData->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $teacher->phone ?? '') }}" placeholder="e.g., (123) 456-7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" 
                                   value="{{ old('department', $teacher->department ?? '') }}" 
                                   placeholder="e.g., Computer Science">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Display Name (read-only) -->
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" value="{{ $userData->name ?? '' }}" disabled>
                            <small class="text-muted">Your full name as shown in the system</small>
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Leave blank to keep current">
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" 
                                   placeholder="Confirm your new password">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Profile Tips Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Tips</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Profile Image:</strong>
                        <p class="text-muted small">Upload a professional photo. Square images work best.</p>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Contact Info:</strong>
                        <p class="text-muted small">Keep your email and phone number up to date for student communications.</p>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Department:</strong>
                        <p class="text-muted small">Specify your primary department for better visibility.</p>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success"></i>
                        <strong>Password:</strong>
                        <p class="text-muted small">Use a strong password with letters, numbers, and symbols.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Changes will be reflected immediately across the system.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-preview').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush