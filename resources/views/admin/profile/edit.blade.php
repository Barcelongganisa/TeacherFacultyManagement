@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-edit me-2 text-success"></i>Edit Profile</h1>
            <p class="text-muted">Update your account information</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Image Card -->
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-4">
                    <div class="position-relative d-inline-block">
                        @php
                            $profileImage = $user->profile_image 
                                ? asset('storage/'.$user->profile_image) 
                                : asset('assets/img/default-avatar.png');
                        @endphp
                        <img src="{{ $profileImage }}" 
                             alt="Profile" 
                             class="profile-image-large rounded-circle border border-3 border-success"
                             id="profileImagePreview">
                        <span class="online-indicator-large"></span>
                    </div>
                </div>
                
                <h4>{{ $user->username }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                <div class="alert alert-success mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Administrator</strong>
                    <p class="small mb-0 mt-2">You have full system access</p>
                </div>
            </div>
        </div>
        
        <!-- Account Info Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-success"></i>Account Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Member Since</small>
                    <strong>{{ $user->created_at ? $user->created_at->format('F j, Y') : 'N/A' }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Last Updated</small>
                    <strong>{{ $user->updated_at ? $user->updated_at->format('F j, Y') : 'N/A' }}</strong>
                </div>
                @if($user->last_login_at)
                    <div>
                        <small class="text-muted d-block">Last Login</small>
                        <strong>{{ $user->last_login_at->format('F j, Y g:i A') }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Edit Profile Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2 text-success"></i>Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" 
                               class="form-control @error('profile_image') is-invalid @enderror" 
                               id="profile_image" 
                               name="profile_image" 
                               accept="image/*">
                        <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div class="mt-2" id="imagePreviewContainer" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;" id="imagePreview">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" 
                               class="form-control" 
                               value="{{ $user->username }}" 
                               disabled>
                        <small class="text-muted">Username cannot be changed</small>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password">
                        <small class="text-muted">Minimum 8 characters. Leave blank to keep current password.</small>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation">
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Changing your password will log you out of all other sessions.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image-large {
    width: 150px;
    height: 150px;
    object-fit: cover;
}

.online-indicator-large {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    background-color: #2ecc71;
    border: 3px solid white;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(46, 204, 113, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
    }
}
</style>

<script>
// Image preview
$('#profile_image').change(function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
            $('#imagePreviewContainer').show();
            
            // Also update the profile image preview
            $('#profileImagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    } else {
        $('#imagePreviewContainer').hide();
    }
});
</script>
@endsection