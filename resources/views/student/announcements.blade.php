@extends('layouts.student')

@section('title', 'Announcements')

@push('styles')
<style>
.announcement-card.unread {
    border-left: 4px solid #1b5e20;
}
.announcement-card.read {
    opacity: 0.8;
}
.announcement-message {
    line-height: 1.6;
    font-size: 1rem;
}
.btn-group .btn.active {
    background-color: #1b5e20;
    border-color: #1b5e20;
    color: white;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button and Title -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-success mb-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h3>
                <i class="fas fa-bullhorn"></i> Announcements
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }} unread</span>
                @endif
            </h3>
            <p class="text-muted">Important updates and notices from your professors</p>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-success active" data-filter="all">
                    <i class="fas fa-list"></i> All Announcements
                </button>
                <button type="button" class="btn btn-outline-warning" data-filter="unread">
                    <i class="fas fa-eye-slash"></i> Unread ({{ $unreadCount }})
                </button>
                <button type="button" class="btn btn-outline-success" data-filter="read">
                    <i class="fas fa-check"></i> Read
                </button>
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="row">
        <div class="col-12">
            @if($announcements->count() > 0)
                @foreach($announcements as $announcement)
                    <div class="card mb-3 shadow-sm announcement-card {{ $announcement->read_status ? 'read' : 'unread' }}" 
                         data-read-status="{{ $announcement->read_status ? 'read' : 'unread' }}">
                        
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <!-- Priority Badge -->
                                <span class="badge me-2 
                                    @if($announcement->priority == 'urgent') bg-danger
                                    @elseif($announcement->priority == 'high') bg-warning text-dark
                                    @elseif($announcement->priority == 'normal') bg-success
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($announcement->priority) }}
                                </span>

                                <!-- Read Status -->
                                @if(!$announcement->read_status)
                                    <span class="badge bg-success me-2">NEW</span>
                                @endif

                                <!-- Title -->
                                <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
                            </div>

                            <!-- Mark as Read Button -->
                            @if(!$announcement->read_status)
                                <form method="POST" action="{{ route('student.announcements.mark-read') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
                                    <button type="submit" name="mark_read" class="btn btn-sm btn-outline-success" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-success" title="Read on {{ $announcement->read_status->format('M d, Y g:i A') }}">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>

                        <div class="card-body">
                            <!-- Teacher and Subject Info -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> From: <strong>{{ $announcement->teacher_name }}</strong>
                                    
                                    @if($announcement->subject_code)
                                        <span class="ms-3">
                                            <i class="fas fa-book"></i> Subject: 
                                            <span class="badge bg-light text-dark">
                                                {{ $announcement->subject_code }} - {{ $announcement->subject_name }}
                                            </span>
                                        </span>
                                    @else
                                        <span class="ms-3">
                                            <i class="fas fa-globe"></i> 
                                            <span class="badge bg-light text-dark">General Announcement</span>
                                        </span>
                                    @endif
                                    
                                    <span class="ms-3">
                                        <i class="fas fa-clock"></i> {{ $announcement->publish_date->format('M d, Y g:i A') }}
                                    </span>
                                </small>
                            </div>

                            <!-- Message -->
                            <div class="announcement-message">
                                {!! nl2br(e($announcement->message)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Announcements</h5>
                        <p class="text-muted">You don't have any announcements at the moment. Check back later for updates from your professors.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const announcementCards = document.querySelectorAll('.announcement-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            announcementCards.forEach(card => {
                const readStatus = card.getAttribute('data-read-status');
                
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (filter === 'unread' && readStatus === 'unread') {
                    card.style.display = 'block';
                } else if (filter === 'read' && readStatus === 'read') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush