@extends('layouts.student')

@section('title', 'Subjects & Enrollment')

@section('content')
<div class="container-fluid">
    <!-- Back Button and Title -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-success mb-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h3><i class="fas fa-book"></i> Available Subjects</h3>
            <p class="text-muted">Browse and enroll in subjects offered by our teachers</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Subjects Grid -->
    <div class="row">
        @forelse($subjects as $subject)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-graduation-cap"></i>
                            {{ $subject->subject_code }}
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-subtitle mb-2">{{ $subject->subject_name }}</h6>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-building"></i> 
                                {{ $subject->department ?? 'General' }}
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-credit-card"></i> 
                                {{ $subject->credits }} Credits
                            </small>
                        </div>

                        @if(!empty($subject->description))
                            <p class="card-text small text-muted">
                                {{ Str::limit($subject->description, 100) }}
                            </p>
                        @endif

                        <div class="mb-3">
                            <label class="fw-bold text-muted small">Assigned Professor:</label>
                            @if(!empty($subject->teachers))
                                <div class="mt-1">
                                    @php
                                        $teacherNames = explode(', ', $subject->teachers);
                                        $teacherIds = explode(',', $subject->teacher_ids);
                                    @endphp
                                    @foreach($teacherNames as $index => $teacherName)
                                        @if(!empty(trim($teacherName)))
                                            <a href="{{ route('student.teacher-profile', ['id' => trim($teacherIds[$index])]) }}" 
                                               class="badge bg-success text-white text-decoration-none me-1">
                                                <i class="fas fa-user"></i> {{ trim($teacherName) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <small class="text-muted">No professor assigned yet</small>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @if(!empty($subject->enrollment_id))
                                <!-- Already enrolled -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle"></i> Enrolled
                                    </span>
                                    <form method="POST" action="{{ route('student.subjects.unenroll') }}" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to unenroll from this subject?');">
                                        @csrf
                                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i> Unenroll
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Not enrolled -->
                                <form method="POST" action="{{ route('student.subjects.enroll') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-plus-circle"></i> Enroll Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>No Subjects Available</h5>
                    <p>There are currently no subjects available for enrollment. Please check back later.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection