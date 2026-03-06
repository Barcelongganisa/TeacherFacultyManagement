@extends('superadmin.layouts.app')

@section('title', 'My Profile - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-edit me-2"></i>My Profile</h1>
            <p class="text-muted">Update your personal information and account settings</p>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Profile Image -->
                        <div class="mb-4">
                            <label class="form-label">Profile Image</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/img/default-avatar.png') }}" 
                                         alt="Profile" 
                                         class="profile-preview rounded-circle"
                                         id="profilePreview"
                                         width="100" height="100" style="object-fit: cover; border: 3px solid var(--primary-purple);">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" 
                                           accept="image/*" id="profileImageInput">
                                    <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Info -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department', $user->department) }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-key me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.profile.password') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password" required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Account Overview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-id-card me-2"></i>Account Overview</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/img/default-avatar.png') }}" 
                             class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        <h5 class="mt-3">{{ $user->name }}</h5>
                        <span class="badge bg-purple">Super Admin</span>
                    </div>
                    
                    <hr>
                    
                    <table class="table table-sm">
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Member Since:</th>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Login:</th>
                            <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $user->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tips -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-lightbulb me-2"></i>Profile Tips</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-purple me-2"></i>
                        <strong>Profile Image:</strong>
                        <p class="text-muted small">Upload a professional photo. Square images work best.</p>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-purple me-2"></i>
                        <strong>Contact Info:</strong>
                        <p class="text-muted small">Keep your contact information up to date.</p>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-purple me-2"></i>
                        <strong>Password:</strong>
                        <p class="text-muted small">Use a strong password with letters, numbers, and symbols.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Profile image preview
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush