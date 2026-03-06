@extends('admin.layouts.app')

@section('title', 'Add Time Slot')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-clock-plus me-2 text-success"></i>Add New Time Slot</h1>
            <p class="text-muted">Create a new class time slot</p>
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
                <form action="{{ route('admin.time-slots.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="slot_name" class="form-label">Slot Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('slot_name') is-invalid @enderror" 
                               id="slot_name" 
                               name="slot_name" 
                               value="{{ old('slot_name') }}" 
                               placeholder="e.g., Morning Session, Slot 1, Afternoon Class"
                               required>
                        <small class="text-muted">Give this time slot a descriptive name</small>
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
                                   value="{{ old('start_time') }}"
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
                                   value="{{ old('end_time') }}"
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Time Slot Guidelines</h6>
                                <ul class="mb-0 small">
                                    <li>Time slots should not overlap with existing slots</li>
                                    <li>Duration should be reasonable for classes (typically 1-3 hours)</li>
                                    <li>Consider break times between slots</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Add Time Slot
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

<!-- Preview Card -->
<div class="row mt-4">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-eye me-2 text-success"></i>Preview</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center p-3 border rounded" id="previewCard">
                            <i class="fas fa-clock fa-3x text-success mb-3"></i>
                            <h5 id="previewName">Slot Name</h5>
                            <p class="mb-1" id="previewTime">--:-- -- to --:-- --</p>
                            <p class="text-muted small" id="previewDuration">Duration: -- hours</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Current Time Slots:</h6>
                        <div class="list-group" style="max-height: 200px; overflow-y: auto;">
                            @foreach($timeSlots ?? [] as $slot)
                                <div class="list-group-item">
                                    <strong>{{ $slot->slot_name }}</strong>
                                    <br>
                                    <small>{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} - 
                                           {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function updatePreview() {
        const name = $('#slot_name').val() || 'Slot Name';
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();
        
        $('#previewName').text(name);
        
        if (startTime && endTime) {
            const start = formatTime(startTime);
            const end = formatTime(endTime);
            $('#previewTime').text(`${start} to ${end}`);
            
            // Calculate duration
            const startDate = new Date('2000-01-01T' + startTime);
            const endDate = new Date('2000-01-01T' + endTime);
            const diffMs = endDate - startDate;
            const diffHrs = diffMs / (1000 * 60 * 60);
            $('#previewDuration').text(`Duration: ${diffHrs.toFixed(1)} hours`);
        } else {
            $('#previewTime').text('--:-- -- to --:-- --');
            $('#previewDuration').text('Duration: -- hours');
        }
    }
    
    function formatTime(timeStr) {
        const [hours, minutes] = timeStr.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }
    
    $('#slot_name, #start_time, #end_time').on('input change', updatePreview);
});
</script>
@endsection