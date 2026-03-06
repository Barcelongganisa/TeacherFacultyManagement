@extends('layouts.student')

@section('title', 'Student Profile')
<Style>
body > div.main-content > div > div.col-lg-4.mb-4{
    margin-bottom: 0 !important;
}
body > div.sidebar > div:nth-child(1){
    display: flex !important;
    justify-content: center !important;
    flex-wrap: wrap !important;
    flex-direction: column !important;
    align-items: center !important;
}
</Style>

@section('content')

<div class="row">
    <div class="row mb-3">
        <div class="col-12">
            <h4><i class="fa-solid fa-person"></i> Student Profile</h4>
            <p class="text-muted">View and update your profile information</p>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="content-card text-center">
            <div class="position-relative d-inline-block mb-4">
                <img id="profilePreview" 
                    src="{{ $user->profile_image 
                            ? asset('storage/' . $user->profile_image) . '?v=' . $user->updated_at->timestamp
                            : asset('images/default_profile.png') }}" 
                    class="rounded-circle border-4" 
                    style="width: 150px; height: 150px; object-fit: cover; border-color: var(--primary-green) !important;">
                <button type="button" class="btn position-absolute bottom-0 end-0 rounded-circle" 
                        style="background: var(--primary-green); color: white; width: 40px; height: 40px;"
                        onclick="document.getElementById('profile_image').click();">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            
            <h4 class="mb-1" style="color: var(--text-dark);">{{ $user->name }}</h4>
            <p class="text-muted mb-3">
                <i class="fas fa-envelope me-1" style="color: var(--primary-green);"></i>{{ $user->email }}
            </p>
            
            <div class="d-flex justify-content-center gap-2 mb-4">
                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.6rem 1.2rem; border-radius: 50px;">
                    <i class="fas fa-id-card me-1"></i> ID: {{ $user->id }}
                </span>
                <span class="badge" style="background: var(--primary-green); color: white; padding: 0.6rem 1.2rem; border-radius: 50px;">
                    <i class="fas fa-circle me-1"></i> {{ ucfirst($user->status) }}
                </span>
            </div>
            
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-3 rounded-3" style="background: var(--soft-green);">
                        <i class="fas fa-calendar-alt mb-2" style="color: var(--primary-green);"></i>
                        <h6 class="mb-0">Member Since</h6>
                        <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-3" style="background: var(--soft-green);">
                        <i class="fas fa-clock mb-2" style="color: var(--primary-green);"></i>
                        <h6 class="mb-0">Last Updated</h6>
                        <small class="text-muted">{{ $user->updated_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Update Form -->
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Edit Profile Information</h3>
                </div>
                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                    <i class="fas fa-lock me-1"></i> Secure Form
                </span>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    
                    <!-- Hidden Profile Image Input -->
                    <input type="file" class="d-none" id="profile_image" name="profile_image" 
                           accept="image/*" onchange="previewImage(this)">
                    
                    <!-- Basic Information Section -->
                    <h6 class="mb-3" style="color: var(--primary-green);">
                        <i class="fas fa-info-circle me-2"></i>Basic Information
                    </h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                    <i class="fas fa-user" style="color: var(--primary-green);"></i>
                                </span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required
                                       style="border: 2px solid var(--soft-green); border-left: none; border-radius: 0 50px 50px 0; padding: 0.6rem 1rem;">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                    <i class="fas fa-envelope" style="color: var(--primary-green);"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required
                                       style="border: 2px solid var(--soft-green); border-left: none; border-radius: 0 50px 50px 0; padding: 0.6rem 1rem;">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4" style="border-color: var(--soft-green);">
                    
                    <!-- Password Change Section -->
                    <h6 class="mb-3" style="color: var(--primary-green);">
                        <i class="fas fa-lock me-2"></i>Change Password <small class="text-muted ms-2">(Leave blank to keep current password)</small>
                    </h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label fw-semibold">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                    <i class="fas fa-key" style="color: var(--primary-green);"></i>
                                </span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password"
                                       style="border: 2px solid var(--soft-green); border-left: none; border-radius: 0 50px 50px 0; padding: 0.6rem 1rem;">
                            </div>
                            <small class="text-muted">Required only if changing password</small>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="new_password" class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                    <i class="fas fa-lock" style="color: var(--primary-green);"></i>
                                </span>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password" minlength="6"
                                       style="border: 2px solid var(--soft-green); border-left: none; border-radius: 0 50px 50px 0; padding: 0.6rem 1rem;">
                            </div>
                            <small class="text-muted">Minimum 6 characters</small>
                            @error('new_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                    <i class="fas fa-check-circle" style="color: var(--primary-green);"></i>
                                </span>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="new_password_confirmation" minlength="6"
                                       style="border: 2px solid var(--soft-green); border-left: none; border-radius: 0 50px 50px 0; padding: 0.6rem 1rem;">
                            </div>
                            <small class="text-muted">Re-enter new password</small>
                        </div>
                    </div>
                    
                    <hr class="my-4" style="border-color: var(--soft-green);">
                    
                    <!-- Password Strength Indicator -->
                    <div id="passwordStrength" class="mb-4" style="display: none;">
                        <label class="form-label fw-semibold">Password Strength</label>
                        <div class="progress" style="height: 8px; border-radius: 10px;">
                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%; background: var(--primary-green);"></div>
                        </div>
                        <small id="strengthText" class="text-muted mt-1"></small>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn" style="background: var(--primary-green); color: white; border-radius: 50px; padding: 0.8rem 2.5rem;">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                        
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt me-2" style="color: var(--primary-green);"></i>
                            <small class="text-muted">All changes are securely saved</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const currentPassword = document.getElementById('current_password');
    const form = document.getElementById('profileForm');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    // Profile image preview
    window.previewImage = function(input) {
        const preview = document.getElementById('profilePreview');
        const file = input.files[0];
        
        if (file) {
            // Check file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            // Check file type
            if (!file.type.match('image.*')) {
                alert('Please select a valid image file (JPEG, PNG, GIF)');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                
                // Show success toast or animation
                preview.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    preview.style.transform = 'scale(1)';
                }, 200);
            };
            reader.readAsDataURL(file);
        }
    };
    
    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let tips = [];
        
        if (password.length >= 6) strength += 20;
        if (password.length >= 8) strength += 10;
        if (password.match(/[a-z]+/)) strength += 20;
        if (password.match(/[A-Z]+/)) strength += 20;
        if (password.match(/[0-9]+/)) strength += 15;
        if (password.match(/[$@#&!]+/)) strength += 15;
        
        if (strength < 30) {
            return { width: 20, text: 'Weak', color: '#dc3545' };
        } else if (strength < 50) {
            return { width: 40, text: 'Fair', color: '#ffc107' };
        } else if (strength < 70) {
            return { width: 60, text: 'Good', color: '#17a2b8' };
        } else if (strength < 90) {
            return { width: 80, text: 'Strong', color: '#28a745' };
        } else {
            return { width: 100, text: 'Very Strong', color: '#28a745' };
        }
    }
    
    // Password input event listeners
    newPassword.addEventListener('input', function() {
        if (this.value) {
            passwordStrength.style.display = 'block';
            const result = checkPasswordStrength(this.value);
            strengthBar.style.width = result.width + '%';
            strengthBar.style.background = result.color;
            strengthText.textContent = 'Password strength: ' + result.text;
            
            // Require current password when setting new password
            currentPassword.required = true;
        } else {
            passwordStrength.style.display = 'none';
            currentPassword.required = false;
            confirmPassword.value = '';
        }
    });
    
    // Password validation
    function validatePasswords() {
        if (newPassword.value && newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            confirmPassword.style.borderColor = '#dc3545';
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.style.borderColor = 'var(--soft-green)';
        }
    }
    
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        if (newPassword.value && !currentPassword.value) {
            e.preventDefault();
            alert('Please enter your current password to change your password.');
            currentPassword.focus();
            return false;
        }
        
        if (newPassword.value && newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            alert('New password and confirmation do not match.');
            confirmPassword.focus();
            return false;
        }
        
        if (newPassword.value && newPassword.value.length < 6) {
            e.preventDefault();
            alert('New password must be at least 6 characters long.');
            newPassword.focus();
            return false;
        }
    });
    
    // Animate stats on load
    const profileCard = document.querySelector('.content-card');
    if (profileCard) {
        profileCard.style.opacity = '0';
        profileCard.style.transform = 'translateY(20px)';
        setTimeout(() => {
            profileCard.style.transition = 'all 0.5s ease';
            profileCard.style.opacity = '1';
            profileCard.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
@endpush