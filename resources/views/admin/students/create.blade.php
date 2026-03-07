@extends('admin.layouts.app')

@section('title', 'Add Student')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-plus me-2 text-success"></i>Add New Student</h1>
            <p class="text-muted">Create a new student account</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Student Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   placeholder="Enter first name"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   placeholder="Enter last name"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="mb-3">
                            <label for="campus" class="form-label">Campus <span class="text-danger">*</span></label>
                            <select class="form-control @error('campus') is-invalid @enderror"
                                    id="campus"
                                    name="campus"
                                    required>
                                <option value="">Select Campus</option>
                                <option value="Main Campus" {{ old('campus') == 'Main Campus' ? 'selected' : '' }}>Main Campus</option>
                                <option value="Congressional Extension Campus" {{ old('campus') == 'Congressional Extension Campus' ? 'selected' : '' }}>Congressional Extension Campus</option>
                                <option value="Bagong Silang Extension Campus" {{ old('campus') == 'Bagong Silang Extension Campus' ? 'selected' : '' }}>Bagong Silang Extension Campus</option>
                                <option value="Camarin Extension Campus" {{ old('campus') == 'Camarin Extension Campus' ? 'selected' : '' }}>Camarin Extension Campus</option>
                            </select>
                            @error('campus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Minimum 8 characters"
                               required>
                        <small class="text-muted">Minimum 8 characters</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection