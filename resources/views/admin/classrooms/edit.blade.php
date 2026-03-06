@extends('admin.layouts.app')

@section('title', 'Edit Classroom')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-school-edit me-2 text-success"></i>Edit Classroom</h1>
            <p class="text-muted">Update classroom information</p>
        </div>
        <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Classroom Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('room_number') is-invalid @enderror" 
                                   id="room_number" 
                                   name="room_number" 
                                   value="{{ old('room_number', $classroom->room_number) }}" 
                                   required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" 
                                   name="capacity" 
                                   value="{{ old('capacity', $classroom->capacity) }}" 
                                   min="1"
                                   required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="room_name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('room_name') is-invalid @enderror" 
                               id="room_name" 
                               name="room_name" 
                               value="{{ old('room_name', $classroom->room_name) }}" 
                               required>
                        @error('room_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="building" class="form-label">Building</label>
                            <select class="form-select @error('building') is-invalid @enderror" 
                                    id="building" 
                                    name="building">
                                <option value="">Select Building</option>
                                <option value="Main Building" {{ old('building', $classroom->building) == 'Main Building' ? 'selected' : '' }}>Main Building</option>
                                <option value="Orange Building" {{ old('building', $classroom->building) == 'Orange Building' ? 'selected' : '' }}>Orange Building</option>
                                <option value="Admin Building" {{ old('building', $classroom->building) == 'Admin Building' ? 'selected' : '' }}>Admin Building</option>
                                <option value="Registrar Building" {{ old('building', $classroom->building) == 'Registrar Building' ? 'selected' : '' }}>Registrar Building</option>
                                <option value="Library Building" {{ old('building', $classroom->building) == 'Library Building' ? 'selected' : '' }}>Library Building</option>
                                <option value="Science Block" {{ old('building', $classroom->building) == 'Science Block' ? 'selected' : '' }}>Science Block</option>
                                <option value="Engineering Block" {{ old('building', $classroom->building) == 'Engineering Block' ? 'selected' : '' }}>Engineering Block</option>
                            </select>
                            @error('building')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="floor" class="form-label">Floor</label>
                            <select class="form-select @error('floor') is-invalid @enderror" 
                                    id="floor" 
                                    name="floor">
                                <option value="">Select Floor</option>
                                <option value="Ground Floor" {{ old('floor', $classroom->floor) == 'Ground Floor' ? 'selected' : '' }}>Ground Floor</option>
                                <option value="1st Floor" {{ old('floor', $classroom->floor) == '1st Floor' ? 'selected' : '' }}>1st Floor</option>
                                <option value="2nd Floor" {{ old('floor', $classroom->floor) == '2nd Floor' ? 'selected' : '' }}>2nd Floor</option>
                                <option value="3rd Floor" {{ old('floor', $classroom->floor) == '3rd Floor' ? 'selected' : '' }}>3rd Floor</option>
                                <option value="4th Floor" {{ old('floor', $classroom->floor) == '4th Floor' ? 'selected' : '' }}>4th Floor</option>
                                <option value="5th Floor" {{ old('floor', $classroom->floor) == '5th Floor' ? 'selected' : '' }}>5th Floor</option>
                                <option value="Court" {{ old('floor', $classroom->floor) == 'Court' ? 'selected' : '' }}>Court</option>
                            </select>
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Room Type</label>
                            <select class="form-select @error('room_type') is-invalid @enderror" 
                                    id="room_type" 
                                    name="room_type">
                                <option value="classroom" {{ old('room_type', $classroom->room_type) == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="laboratory" {{ old('room_type', $classroom->room_type) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                <option value="lecture room" {{ old('room_type', $classroom->room_type) == 'lecture room' ? 'selected' : '' }}>Lecture Room</option>
                                <option value="social hall" {{ old('room_type', $classroom->room_type) == 'social hall' ? 'selected' : '' }}>Social Hall</option>
                            </select>
                            @error('room_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="equipment" class="form-label">Equipment</label>
                            <input type="text" 
                                   class="form-control @error('equipment') is-invalid @enderror" 
                                   id="equipment" 
                                   name="equipment" 
                                   value="{{ old('equipment', $classroom->equipment) }}" 
                                   placeholder="e.g., Projector, Whiteboard, AC">
                            @error('equipment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status">
                            <option value="active" {{ old('status', $classroom->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status', $classroom->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            <option value="inactive" {{ old('status', $classroom->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Classroom
                        </button>
                        <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection