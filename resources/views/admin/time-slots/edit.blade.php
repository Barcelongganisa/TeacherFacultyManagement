@extends('admin.layouts.app')

@section('title', 'Edit Time Slot')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-clock-edit me-2 text-success"></i>Edit Time Slot</h1>
            <p class="text-muted">Update time slot information</p>
        </div>
        <a href="{{ route('admin.time-slots.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Time Slot Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.time-slots.update', $timeSlot->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="slot_name" class="form-label">Slot Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('slot_name') is-invalid @enderror" 
                               id="slot_name" 
                               name="slot_name" 
                               value="{{ old('slot_name', $timeSlot->slot_name) }}" 
                               required>
                        @error('slot_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time', $timeSlot->start_time) }}"
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time', $timeSlot->end_time) }}"
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status">
                            <option value="active" {{ old('status', $timeSlot->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $timeSlot->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Changing time slots may affect existing schedules. Please ensure no active schedules are using this time slot before modifying.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Time Slot
                        </button>
                        <a href="{{ route('admin.time-slots.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Time Slot Usage -->
<div class="row mt-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Time Slot Usage</h6>
            </div>
            <div class="card-body">
                @php
                    $scheduleCount = \App\Models\Schedule::where('time_slot_id', $timeSlot->id)->count();
                    $reservationCount = \App\Models\RoomReservation::where('time_slot_id', $timeSlot->id)->count();
                @endphp
                
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="stat-box">
                            <div class="display-6 fw-bold text-success">{{ $scheduleCount }}</div>
                            <div class="text-muted">Schedules Using This Slot</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-box">
                            <div class="display-6 fw-bold text-success">{{ $reservationCount }}</div>
                            <div class="text-muted">Reservations Using This Slot</div>
                        </div>
                    </div>
                </div>
                
                @if($scheduleCount > 0 || $reservationCount > 0)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This time slot is currently in use. Changes may affect existing schedules and reservations.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    padding: 1.5rem;
    border-radius: 10px;
    background: #f8f9fa;
    transition: var(--transition);
}

.stat-box:hover {
    background: var(--soft-green);
    transform: translateY(-2px);
}
</style>
@endsection