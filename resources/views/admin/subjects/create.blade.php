@extends('admin.layouts.app')

@section('title', 'Add Subject')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-book-plus me-2 text-success"></i>Add New Subject</h1>
            <p class="text-muted">Create a new subject</p>
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf

                    {{-- Subject Code & Credits --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('subject_code') is-invalid @enderror"
                                   name="subject_code"
                                   value="{{ old('subject_code') }}"
                                   placeholder="e.g., CS101"
                                   style="text-transform: uppercase;"
                                   required>
                            @error('subject_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Credits</label>
                            <input type="number"
                                   class="form-control @error('credits') is-invalid @enderror"
                                   name="credits"
                                   value="{{ old('credits', 3) }}"
                                   min="1"
                                   max="10">
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Subject Name --}}
                    <div class="mb-3">
                        <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('subject_name') is-invalid @enderror"
                               name="subject_name"
                               value="{{ old('subject_name') }}"
                               placeholder="e.g., Introduction to Computer Science"
                               required>
                        @error('subject_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Course --}}
                    <div class="mb-3">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <select name="course_id"
                                class="form-select @error('course_id') is-invalid @enderror">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">The course this subject belongs to e.g. BSCS</small>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Year Level & Semester --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Year Level <span class="text-danger">*</span></label>
                            <select name="year_level"
                                    class="form-select @error('year_level') is-invalid @enderror">
                                <option value="">-- Select Year Level --</option>
                                <option value="1st" {{ old('year_level') == '1st' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd" {{ old('year_level') == '2nd' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd" {{ old('year_level') == '3rd' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th" {{ old('year_level') == '4th' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('year_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester"
                                    class="form-select @error('semester') is-invalid @enderror">
                                <option value="">-- Select Semester --</option>
                                <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer"       {{ old('semester') == 'Summer'       ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  name="description"
                                  rows="4"
                                  placeholder="Brief description of this subject...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Subject
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