@extends('superadmin.layouts.app')

@section('title', 'Create User - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-plus me-2"></i>Create New User</h1>
            <p class="text-muted">Add a new user to the system</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.users.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Profile Image -->
                        <div class="mb-4">
                            <label class="form-label">Profile Image</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ asset('assets/img/default-avatar.png') }}" 
                                         alt="Profile Preview" 
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

                        <!-- Basic Info -->
                        <div class="row">
                            <div class="col-md-13 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Campus Admin</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Campus Selection (shown/hidden based on role) -->
                        <div class="mb-3" id="campusField">
                            <label for="campus_id" class="form-label">Campus</label>
                            <select class="form-select @error('campus_id') is-invalid @enderror" id="campus_id" name="campus_id">
                                <option value="">No Campus (Global Access)</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->campus_name }} ({{ $campus->campus_code }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" id="campusHelp">Required for Campus Admin role</small>
                            @error('campus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Additional Info (Optional) -->
                        <h6 class="mb-3"><i class="fas fa-address-card me-2"></i>Additional Information (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department') }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="2">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tips Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-lightbulb me-2"></i>Tips</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-info-circle text-purple me-2"></i>
                        <strong>Required Fields:</strong>
                        <p class="text-muted small">All fields marked with <span class="text-danger">*</span> are required.</p>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-university text-purple me-2"></i>
                        <strong>Campus Assignment:</strong>
                        <p class="text-muted small">Campus Admin role requires a campus assignment. Super Admin has global access.</p>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-key text-purple me-2"></i>
                        <strong>Password Strength:</strong>
                        <p class="text-muted small">Use at least 8 characters with a mix of letters, numbers, and symbols.</p>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-image text-purple me-2"></i>
                        <strong>Profile Image:</strong>
                        <p class="text-muted small">Upload a professional photo. Square images work best.</p>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Email Notification:</strong>
                        <p class="small mb-0">The user will receive a welcome email with login instructions.</p>
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

    // Show/hide campus field based on role
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const campusField = document.getElementById('campusField');
        const campusSelect = document.getElementById('campus_id');
        const campusHelp = document.getElementById('campusHelp');
        
        if (role === 'admin') {
            campusField.style.display = 'block';
            campusSelect.required = true;
            campusHelp.innerHTML = 'Required for Campus Admin role';
        } else if (role === 'super_admin') {
            campusField.style.display = 'block';
            campusSelect.required = false;
            campusHelp.innerHTML = 'Super Admin has global access to all campuses';
        } else {
            campusField.style.display = 'block';
            campusSelect.required = false;
            campusHelp.innerHTML = 'Optional for this role';
        }
    });

    // Trigger on page load
    document.getElementById('role').dispatchEvent(new Event('change'));
</script>
@endpush