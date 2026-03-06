@extends('layouts.student')

@section('title', 'Student Dashboard')

<style>
        body > div.sidebar > div:nth-child(1){
        display: flex !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        flex-direction: column !important;
        align-items: center !important;
    }
</style>

@php
$enrolledCount = $enrolledCount ?? 0;
$totalSubjects = $totalSubjects ?? 0;
$todaySchedule = $todaySchedule ?? collect([]);
$unreadCount = $unreadCount ?? 0;
$recentAnnouncements = $recentAnnouncements ?? collect([]);
@endphp

@section('content')

<!-- Today's Schedule Card -->
<div class="row">
    <div class="row mb-3">
        <div class="col-12">
            <h4><i class="fa-solid fa-tachograph-digital"></i> Student Dashboard</h4>
            <p class="text-muted">Dashboard for students</p>
        </div>
    </div>
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-day me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Today's Schedule</h3>
                </div>
                <a href="{{ route('student.schedule') }}" class="btn btn-sm" style="background: var(--primary-green); color: white; border-radius: 50px; padding: 0.5rem 1.5rem;">
                    <i class="fas fa-calendar-alt me-1"></i> View Full Schedule
                </a>
            </div>
            
            <div class="card-body p-0">
                @if($todaySchedule->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: var(--soft-green);">
                                <tr>
                                    <th class="py-3 ps-4">Time</th>
                                    <th class="py-3">Subject</th>
                                    <th class="py-3">Professor</th>
                                    <th class="py-3 pe-4">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todaySchedule as $class)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge" style="background: var(--primary-green); color: white; padding: 0.5rem 1rem; border-radius: 50px;">
                                                <i class="far fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong style="color: var(--text-dark);">{{ $class->subject_code }}</strong>
                                                <div class="small text-muted">{{ $class->subject_name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                                    <i class="fas fa-user-tie text-success" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span>Prof. {{ $class->first_name }} {{ $class->last_name }}</span>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-success me-2"></i>
                                                <div>
                                                    <span>{{ $class->classroom_name }}</span>
                                                    @if($class->classroom_location)
                                                        <br><small class="text-muted">{{ $class->classroom_location }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-calendar-times fa-4x" style="color: var(--primary-green-light);"></i>
                        </div>
                        <h5 class="text-muted mb-2">No classes scheduled for today</h5>
                        <p class="text-muted small">Enjoy your free time! Check your full schedule for upcoming classes.</p>
                        <a href="{{ route('student.schedule') }}" class="btn btn-outline-success btn-sm mt-3" style="border-radius: 50px; padding: 0.5rem 1.5rem;">
                            <i class="fas fa-calendar-alt me-1"></i> View Full Schedule
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="#" class="btn btn-outline-success" style="border-radius: 50px; padding: 0.6rem 1.5rem; border-color: var(--primary-green); color: var(--primary-green);">
                <i class="fas fa-book me-2"></i> View Subjects
            </a>
            <a href="#" class="btn btn-outline-success" style="border-radius: 50px; padding: 0.6rem 1.5rem; border-color: var(--primary-green); color: var(--primary-green);">
                <i class="fas fa-calendar-alt me-2"></i> Academic Calendar
            </a>
            <a href="#" class="btn btn-outline-success" style="border-radius: 50px; padding: 0.6rem 1.5rem; border-color: var(--primary-green); color: var(--primary-green);">
                <i class="fas fa-download me-2"></i> Download Resources
            </a>
            <a href="#" class="btn btn-outline-success" style="border-radius: 50px; padding: 0.6rem 1.5rem; border-color: var(--primary-green); color: var(--primary-green);">
                <i class="fas fa-question-circle me-2"></i> Help & Support
            </a>
        </div>
    </div>
</div>
@endsection