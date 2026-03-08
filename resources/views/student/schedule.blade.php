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
                                            {{ $schedule->day }} 
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
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
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
                                    Showing {{ $schedules->count() }} scheduled classes.
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1" style="color: var(--primary-green);"></i>
                                    Times are displayed in 12-hour format
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
                            No schedules are set for your course yet. Please contact your administrator.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Weekly Calendar Overview -->
@if($schedules->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-week me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Weekly Calendar</h3>
                </div>

                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                    {{ now()->format('F j, Y') }}
                </span>
            </div>

            <div class="card-body">

                @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead style="background: var(--soft-green);">
                            <tr>
                                @foreach($days as $day)
                                    <th>{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($days as $day)
                                    @php
                                        $daySchedules = $schedules->filter(fn($s) => $s->day === $day);
                                    @endphp
                                    <td style="min-width:180px; vertical-align: top;">
                                        @if($daySchedules->count() > 0)
                                            @foreach($daySchedules as $schedule)
                                            <div class="p-2 mb-2 rounded"
                                                style="background:#e8f8f5; border-left:4px solid var(--primary-green);">
                                                <strong>{{ $schedule->subject_code }}</strong>
                                                <div class="small text-muted">
                                                    {{ $schedule->subject_name }}
                                                </div>
                                                <div class="small">
                                                    <i class="far fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $schedule->room_number }}
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">No Class</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection