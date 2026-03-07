@extends('layouts.student')

@section('title', 'Classroom Schedule')

<style>
        body > div.sidebar > div:nth-child(1){
        display: flex !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        flex-direction: column !important;
        align-items: center !important;
    }
</style>

@section('content')
<div class="row">
    <div class="row mb-3">
        <div class="col-12">
            <h4><i class="fa-regular fa-calendar-days"></i> Schedule</h4>
            <p class="text-muted">Current Schedule</p>
        </div>
    </div>
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-table me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Weekly Class Schedule</h3>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if($schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: var(--soft-green);">
                                <tr>
                                    <th class="py-3 ps-4"><i class="fas fa-calendar-day me-2"></i>Day</th>
                                    <th class="py-3"><i class="fas fa-clock me-2"></i>Time Slot</th>
                                    <th class="py-3"><i class="fas fa-book me-2"></i>Subject</th>
                                    <th class="py-3"><i class="fas fa-user-tie me-2"></i>Professor</th>
                                    <th class="py-3"><i class="fas fa-map-marker-alt me-2"></i>Room</th>
                                    <th class="py-3 pe-4"><i class="fas fa-stopwatch me-2"></i>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge" style="background: var(--primary-green); color: white; padding: 0.5rem 1rem; border-radius: 50px; min-width: 100px;">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $schedule->day_of_week }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $schedule->start_time .' - '. $schedule->end_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                                    <i class="fas fa-book-open text-success" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <div>
                                                    <strong style="color: var(--text-dark);">{{ $schedule->subject_code }}</strong>
                                                    <div class="small text-muted">{{ $schedule->subject_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                                    <i class="fas fa-user-tie text-success" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span>Prof. {{ $schedule->teacher_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-success me-2"></i>
                                                <div>
                                                    <strong>{{ $schedule->room_number }}</strong>
                                                   @if(!empty($schedule->room_name))
                                                        <br><small class="text-muted">{{ $schedule->room_name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            <span class="badge bg-light text-dark" style="padding: 0.5rem 1rem; border-radius: 50px;">
                                                <i class="far fa-hourglass me-1"></i>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Schedule Footer Info -->
                    <div class="mt-3 p-3" style="background: var(--soft-green); border-radius: 15px;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1" style="color: var(--primary-green);"></i>
                                    Showing {{ $schedules->count() }} scheduled classes for your enrolled subjects.
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1" style="color: var(--primary-green);"></i>
                                    Times are displayed in 24-hour format
                                </small>
                            </div>
                        </div>
                    </div>
                    
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times fa-5x" style="color: var(--primary-green-light);"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Schedule Available</h4>
                        <p class="text-muted mb-4" style="max-width: 400px; margin-left: auto; margin-right: auto;">
                            You haven't enrolled in any subjects yet or no schedules are set for your enrolled subjects.
                        </p>
                        <a href="{{ route('student.subjects') }}" class="btn" style="background: var(--primary-green); color: white; border-radius: 50px; padding: 0.8rem 2rem;">
                            <i class="fas fa-plus-circle me-2"></i> Enroll in Subjects
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Weekly Overview Card (Optional) -->
@if($schedules->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-pie me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Weekly Overview</h3>
                </div>
                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                    <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('F j, Y') }}
                </span>
            </div>
            
            <div class="card-body">
                <div class="row">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $dayColors = ['#e8f8f5', '#d5f5e3', '#a9dfbf', '#7dcea0', '#52be80', '#27ae60', '#1e8449'];
                    @endphp
                    
                    @foreach($days as $index => $day)
                        @php
                           $daySchedules = $schedules->filter(fn($s) => $s->day_of_week === $day);
                        @endphp
                        <div class="col-md-3 mb-3">
                            <div class="p-3 rounded-3" style="background: {{ $dayColors[$index % count($dayColors)] }}; border-left: 4px solid var(--primary-green);">
                                <h6 class="fw-bold mb-2" style="color: var(--text-dark);">{{ $day }}</h6>
                                @if($daySchedules->count() > 0)
                                    <span class="badge" style="background: var(--primary-green); color: white; border-radius: 50px;">
                                        {{ $daySchedules->count() }} class(es)
                                    </span>
                                    <div class="mt-2 small">
                                        @foreach($daySchedules as $schedule)
                                           <div class="text-muted">
                                                <i class="far fa-clock me-1" style="font-size: 0.7rem;"></i>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted" style="border-radius: 50px;">
                                        No classes
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection