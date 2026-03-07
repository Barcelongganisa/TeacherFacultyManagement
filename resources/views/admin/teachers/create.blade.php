@extends('admin.layouts.app')

@section('title', 'Add Professor')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-user-plus me-2 text-success"></i>Add New Professor</h1>
            <p class="text-muted">Create a new professor account</p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Professor Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('employee_id') is-invalid @enderror"
                                   id="employee_id"
                                   name="employee_id"
                                   value="{{ old('employee_id') }}"
                                   required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select @error('department') is-invalid @enderror"
                                    id="department"
                                    name="department">
                                <option value="">Select Department</option>
                                <option value="BSIT" {{ old('department') == 'BSIT' ? 'selected' : '' }}>BSIT</option>
                                <option value="BSCS" {{ old('department') == 'BSCS' ? 'selected' : '' }}>BSCS</option>
                                <option value="BSIS" {{ old('department') == 'BSIS' ? 'selected' : '' }}>BSIS</option>
                                <option value="BSEMC" {{ old('department') == 'BSEMC' ? 'selected' : '' }}>BSEMC</option>
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="qualification" class="form-label">Qualification</label>
                            <input type="text"
                                   class="form-control @error('qualification') is-invalid @enderror"
                                   id="qualification"
                                   name="qualification"
                                   value="{{ old('qualification') }}"
                                   placeholder="e.g., PhD, Masters">
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date"
                                   class="form-control @error('hire_date') is-invalid @enderror"
                                   id="hire_date"
                                   name="hire_date"
                                   value="{{ old('hire_date') }}">
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                  id="bio"
                                  name="bio"
                                  rows="4">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Professor
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection