@extends('superadmin.layouts.app')

@section('title', 'System Settings - Super Admin')

@section('page-header')
    <div class="flex-between">
        <div>
            <h1><i class="fas fa-cog me-2"></i>System Settings</h1>
            <p class="text-muted">Configure global system settings</p>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 mb-4">
            <!-- Settings Navigation -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-sliders-h me-2"></i>Settings Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="tab">
                        <i class="fas fa-globe me-2"></i>General Settings
                    </a>
                    <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-shield-alt me-2"></i>Security
                    </a>
                    <a href="#email" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-envelope me-2"></i>Email Configuration
                    </a>
                    <a href="#reservations" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-calendar-alt me-2"></i>Reservation Settings
                    </a>
                    <a href="#backup" class="list-group-item list-group-item-action" data-bs-toggle="tab">
                        <i class="fas fa-database me-2"></i>Backup & Maintenance
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-globe me-2"></i>General Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="system_name" class="form-label">System Name</label>
                                    <input type="text" class="form-control" id="system_name" name="system_name" 
                                           value="{{ $settings['system_name'] ?? 'Teacher Faculty Management System' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="system_email" class="form-label">System Email</label>
                                    <input type="email" class="form-control" id="system_email" name="system_email" 
                                           value="{{ $settings['system_email'] ?? 'admin@cms.edu' }}">
                                    <small class="text-muted">Used for system notifications</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="UTC" {{ ($settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                        <option value="America/Chicago" {{ ($settings['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                        <option value="America/Denver" {{ ($settings['timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                        <option value="America/Los_Angeles" {{ ($settings['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Date Format</label>
                                    <select class="form-select" id="date_format" name="date_format">
                                        <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>2024-03-15</option>
                                        <option value="m/d/Y" {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>03/15/2024</option>
                                        <option value="d/m/Y" {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>15/03/2024</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="time_format" class="form-label">Time Format</label>
                                    <select class="form-select" id="time_format" name="time_format">
                                        <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') == 'H:i' ? 'selected' : '' }}>24-hour (14:30)</option>
                                        <option value="h:i A" {{ ($settings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>12-hour (02:30 PM)</option>
                                    </select>
                                </div>
                                
                                <hr>
                                
                                <h6 class="mb-3">Maintenance Mode</h6>
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="maintenance_mode">Enable Maintenance Mode</label>
                                    <br><small class="text-muted">When enabled, only super admins can access the system</small>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save General Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="password_min_length" class="form-label">Minimum Password Length</label>
                                    <input type="number" class="form-control" id="password_min_length" name="password_min_length" 
                                           value="{{ $settings['password_min_length'] ?? 8 }}" min="6" max="20">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_complexity" class="form-label">Password Complexity</label>
                                    <select class="form-select" id="password_complexity" name="password_complexity">
                                        <option value="none" {{ ($settings['password_complexity'] ?? 'medium') == 'none' ? 'selected' : '' }}>None</option>
                                        <option value="low" {{ ($settings['password_complexity'] ?? '') == 'low' ? 'selected' : '' }}>Low (letters + numbers)</option>
                                        <option value="medium" {{ ($settings['password_complexity'] ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium (letters, numbers, special chars)</option>
                                        <option value="high" {{ ($settings['password_complexity'] ?? '') == 'high' ? 'selected' : '' }}>High (uppercase, lowercase, numbers, special)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                           value="{{ $settings['session_timeout'] ?? 120 }}" min="5" max="480">
                                </div>
                                
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" 
                                           {{ ($settings['two_factor_auth'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="two_factor_auth">Enable Two-Factor Authentication</label>
                                </div>
                                
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="login_attempts" name="login_attempts" 
                                           {{ ($settings['login_attempts'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="login_attempts">Limit Login Attempts</label>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                                           value="{{ $settings['max_login_attempts'] ?? 5 }}" min="3" max="10">
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Security Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div class="tab-pane fade" id="email">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-envelope me-2"></i>Email Configuration</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="mail_driver" class="form-label">Mail Driver</label>
                                    <select class="form-select" id="mail_driver" name="mail_driver">
                                        <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ ($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_host" class="form-label">SMTP Host</label>
                                    <input type="text" class="form-control" id="mail_host" name="mail_host" 
                                           value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_port" class="form-label">SMTP Port</label>
                                    <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                           value="{{ $settings['mail_port'] ?? 587 }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_username" class="form-label">SMTP Username</label>
                                    <input type="text" class="form-control" id="mail_username" name="mail_username" 
                                           value="{{ $settings['mail_username'] ?? '' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_password" class="form-label">SMTP Password</label>
                                    <input type="password" class="form-control" id="mail_password" name="mail_password" 
                                           placeholder="Leave blank to keep current">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_encryption" class="form-label">Encryption</label>
                                    <select class="form-select" id="mail_encryption" name="mail_encryption">
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_from_address" class="form-label">From Address</label>
                                    <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                                           value="{{ $settings['mail_from_address'] ?? 'noreply@cms.edu' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_from_name" class="form-label">From Name</label>
                                    <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" 
                                           value="{{ $settings['mail_from_name'] ?? 'CMS System' }}">
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Email Settings
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="testEmail()">
                                        <i class="fas fa-paper-plane me-2"></i>Send Test Email
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reservation Settings -->
                <div class="tab-pane fade" id="reservations">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Reservation Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="reservation_advance_days" class="form-label">Advance Booking (days)</label>
                                    <input type="number" class="form-control" id="reservation_advance_days" name="reservation_advance_days" 
                                           value="{{ $settings['reservation_advance_days'] ?? 30 }}" min="1" max="365">
                                    <small class="text-muted">How many days in advance can users book?</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reservation_duration_min" class="form-label">Minimum Duration (minutes)</label>
                                    <input type="number" class="form-control" id="reservation_duration_min" name="reservation_duration_min" 
                                           value="{{ $settings['reservation_duration_min'] ?? 30 }}" min="15" step="15">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reservation_duration_max" class="form-label">Maximum Duration (minutes)</label>
                                    <input type="number" class="form-control" id="reservation_duration_max" name="reservation_duration_max" 
                                           value="{{ $settings['reservation_duration_max'] ?? 240 }}" min="30" step="15">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reservation_buffer_time" class="form-label">Buffer Time (minutes)</label>
                                    <input type="number" class="form-control" id="reservation_buffer_time" name="reservation_buffer_time" 
                                           value="{{ $settings['reservation_buffer_time'] ?? 15 }}" min="0" step="5">
                                    <small class="text-muted">Time between consecutive reservations</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="max_reservations_per_day" class="form-label">Max Reservations Per Day (per user)</label>
                                    <input type="number" class="form-control" id="max_reservations_per_day" name="max_reservations_per_day" 
                                           value="{{ $settings['max_reservations_per_day'] ?? 3 }}" min="1" max="10">
                                </div>
                                
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="require_approval" name="require_approval" 
                                           {{ ($settings['require_approval'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_approval">Require Approval for Reservations</label>
                                </div>
                                
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="allow_weekend" name="allow_weekend" 
                                           {{ ($settings['allow_weekend'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_weekend">Allow Weekend Reservations</label>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="working_days" class="form-label">Working Days</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="mon" name="working_days[]" value="Monday"
                                                       {{ in_array('Monday', $settings['working_days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="mon">Monday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="tue" name="working_days[]" value="Tuesday"
                                                       {{ in_array('Tuesday', $settings['working_days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tue">Tuesday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="wed" name="working_days[]" value="Wednesday"
                                                       {{ in_array('Wednesday', $settings['working_days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="wed">Wednesday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="thu" name="working_days[]" value="Thursday"
                                                       {{ in_array('Thursday', $settings['working_days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="thu">Thursday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="fri" name="working_days[]" value="Friday"
                                                       {{ in_array('Friday', $settings['working_days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fri">Friday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sat" name="working_days[]" value="Saturday"
                                                       {{ in_array('Saturday', $settings['working_days'] ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sat">Saturday</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sun" name="working_days[]" value="Sunday"
                                                       {{ in_array('Sunday', $settings['working_days'] ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sun">Sunday</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Reservation Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Backup & Maintenance -->
                <div class="tab-pane fade" id="backup">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-database me-2"></i>Backup & Maintenance</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-database fa-3x text-primary mb-3"></i>
                                            <h5>Database Backup</h5>
                                            <p class="text-muted">Last backup: {{ $lastBackup ?? 'Never' }}</p>
                                            <button class="btn btn-primary" onclick="createBackup()">
                                                <i class="fas fa-play me-2"></i>Create Backup Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-export fa-3x text-success mb-3"></i>
                                            <h5>Export Data</h5>
                                            <p class="text-muted">Export system data</p>
                                            <div class="btn-group">
                                                <button class="btn btn-success" onclick="exportData('users')">
                                                    <i class="fas fa-users me-2"></i>Users
                                                </button>
                                                <button class="btn btn-info" onclick="exportData('reservations')">
                                                    <i class="fas fa-calendar me-2"></i>Reservations
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Automatic Backup Schedule</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('superadmin.settings.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="backup_frequency" class="form-label">Backup Frequency</label>
                                            <select class="form-select" id="backup_frequency" name="backup_frequency">
                                                <option value="daily" {{ ($settings['backup_frequency'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="weekly" {{ ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                <option value="monthly" {{ ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="backup_time" class="form-label">Backup Time</label>
                                            <input type="time" class="form-control" id="backup_time" name="backup_time" 
                                                   value="{{ $settings['backup_time'] ?? '02:00' }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="backup_retention" class="form-label">Retention Period (days)</label>
                                            <input type="number" class="form-control" id="backup_retention" name="backup_retention" 
                                                   value="{{ $settings['backup_retention'] ?? 30 }}" min="1" max="365">
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Backup Settings
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Backup History -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Backup History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>File Name</th>
                                                    <th>Size</th>
                                                    <th>Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($backups ?? [] as $backup)
                                                <tr>
                                                    <td>{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>{{ $backup->filename }}</td>
                                                    <td>{{ $backup->size }}</td>
                                                    <td><span class="badge bg-{{ $backup->type === 'automatic' ? 'info' : 'success' }}">{{ ucfirst($backup->type) }}</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadBackup('{{ $backup->id }}')">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="restoreBackup('{{ $backup->id }}')">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No backups found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Test email function
    function testEmail() {
        fetch('{{ route("superadmin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        });
    }
    
    // Create backup
    function createBackup() {
        if (confirm('Create a new database backup?')) {
            fetch('{{ route("superadmin.settings.backup") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            });
        }
    }
    
    // Export data
    function exportData(type) {
        window.location.href = '{{ route("superadmin.settings.export") }}?type=' + type;
    }
    
    // Download backup
    function downloadBackup(id) {
        window.location.href = '{{ route("superadmin.settings.download-backup") }}?id=' + id;
    }
    
    // Restore backup
    function restoreBackup(id) {
        if (confirm('WARNING: This will restore the database from this backup. All current data will be overwritten. Continue?')) {
            fetch('{{ route("superadmin.settings.restore-backup") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ backup_id: id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush