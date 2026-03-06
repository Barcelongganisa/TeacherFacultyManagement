@extends('admin.layouts.app')

@section('title', 'Add Classroom')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-school-plus me-2 text-success"></i>Add New Classroom</h1>
            <p class="text-muted">Create a new classroom</p>
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
                <form action="{{ route('admin.classrooms.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('room_number') is-invalid @enderror" 
                                   id="room_number" 
                                   name="room_number" 
                                   value="{{ old('room_number') }}" 
                                   placeholder="e.g., 101A"
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
                                   value="{{ old('capacity') }}" 
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
                               value="{{ old('room_name') }}" 
                               placeholder="e.g., Lecture Hall A"
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
                                <option value="Main Building" {{ old('building') == 'Main Building' ? 'selected' : '' }}>Main Building</option>
                                <option value="Orange Building" {{ old('building') == 'Orange Building' ? 'selected' : '' }}>Orange Building</option>
                                <option value="Admin Building" {{ old('building') == 'Admin Building' ? 'selected' : '' }}>Admin Building</option>
                                <option value="Registrar Building" {{ old('building') == 'Registrar Building' ? 'selected' : '' }}>Registrar Building</option>
                                <option value="Library Building" {{ old('building') == 'Library Building' ? 'selected' : '' }}>Library Building</option>
                                <option value="Science Block" {{ old('building') == 'Science Block' ? 'selected' : '' }}>Science Block</option>
                                <option value="Engineering Block" {{ old('building') == 'Engineering Block' ? 'selected' : '' }}>Engineering Block</option>
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
                                <option value="Ground Floor" {{ old('floor') == 'Ground Floor' ? 'selected' : '' }}>Ground Floor</option>
                                <option value="1st Floor" {{ old('floor') == '1st Floor' ? 'selected' : '' }}>1st Floor</option>
                                <option value="2nd Floor" {{ old('floor') == '2nd Floor' ? 'selected' : '' }}>2nd Floor</option>
                                <option value="3rd Floor" {{ old('floor') == '3rd Floor' ? 'selected' : '' }}>3rd Floor</option>
                                <option value="4th Floor" {{ old('floor') == '4th Floor' ? 'selected' : '' }}>4th Floor</option>
                                <option value="5th Floor" {{ old('floor') == '5th Floor' ? 'selected' : '' }}>5th Floor</option>
                                <option value="Court" {{ old('floor') == 'Court' ? 'selected' : '' }}>Court</option>
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
                                <option value="classroom" {{ old('room_type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="laboratory" {{ old('room_type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                <option value="lecture room" {{ old('room_type') == 'lecture room' ? 'selected' : '' }}>Lecture Room</option>
                                <option value="social hall" {{ old('room_type') == 'social hall' ? 'selected' : '' }}>Social Hall</option>
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
                                   value="{{ old('equipment') }}" 
                                   placeholder="e.g., Projector, Whiteboard, AC">
                            @error('equipment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Classroom
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