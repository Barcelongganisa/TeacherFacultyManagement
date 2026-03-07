@extends('admin.layouts.app')

@section('title', 'Edit Subject')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book-edit me-2 text-success"></i>Edit Subject</h1>
            <p class="text-muted">Update subject information</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Subject Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('subject_code') is-invalid @enderror" 
                                   id="subject_code" 
                                   name="subject_code" 
                                   value="{{ old('subject_code', $subject->subject_code) }}" 
                                   required>
                            @error('subject_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="credits" class="form-label">Credits</label>
                            <input type="number" 
                                   class="form-control @error('credits') is-invalid @enderror" 
                                   id="credits" 
                                   name="credits" 
                                   value="{{ old('credits', $subject->credits) }}" 
                                   min="1" 
                                   max="10">
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('subject_name') is-invalid @enderror" 
                               id="subject_name" 
                               name="subject_name" 
                               value="{{ old('subject_name', $subject->subject_name) }}" 
                               required>
                        @error('subject_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select @error('department') is-invalid @enderror" 
                                id="department" 
                                name="department">
                            <option value="">Select Department</option>
                            <option value="Computer Science" {{ old('department') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                            <option value="Mathematics" {{ old('department') == 'BSCS' ? 'selected' : '' }}>BSCS</option>
                            <option value="Physics" {{ old('department') == 'BSIS' ? 'selected' : '' }}>BSIS</option>
                            <option value="Chemistry" {{ old('department') == 'BSEMC' ? 'selected' : '' }}>BSEMC</option>
                        </select>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4">{{ old('description', $subject->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status">
                            <option value="active" {{ old('status', $subject->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $subject->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Subject
                        </button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection