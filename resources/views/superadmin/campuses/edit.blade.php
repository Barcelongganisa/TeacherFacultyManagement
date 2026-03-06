@extends('superadmin.layouts.app')

@section('title', 'Edit Campus - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-edit me-2"></i>Edit Campus</h1>
            <p class="text-muted">Update campus information</p>
        </div>
        <a href="{{ route('superadmin.campuses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Campuses
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Campus Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.campuses.update', $campus) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Info -->
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="campus_name" class="form-label">Campus Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('campus_name') is-invalid @enderror" 
                                       id="campus_name" name="campus_name" value="{{ old('campus_name', $campus->campus_name) }}" required>
                                @error('campus_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="campus_code" class="form-label">Campus Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('campus_code') is-invalid @enderror" 
                                       id="campus_code" name="campus_code" value="{{ old('campus_code', $campus->campus_code) }}" required>
                                @error('campus_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" required>{{ old('address', $campus->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <h6 class="mb-3"><i class="fas fa-address-card me-2"></i>Contact Information</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $campus->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $campus->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $campus->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $campus->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $campus->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Campus
                            </button>
                            <a href="{{ route('superadmin.campuses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Campus Stats Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line me-2"></i>Campus Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Admins</small>
                        <h5>{{ $campus->users_count ?? 0 }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Classrooms</small>
                        <h5>{{ $campus->classrooms_count ?? 0 }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <p class="mb-0"><strong>{{ $campus->created_at->format('F d, Y') }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shield-alt me-2"></i>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">These actions are irreversible. Please proceed with caution.</p>
                    
                    <form action="{{ route('superadmin.campuses.destroy', $campus) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('WARNING: This will permanently delete this campus and all associated data. Are you absolutely sure?')">
                            <i class="fas fa-trash-alt me-2"></i>Delete Campus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection