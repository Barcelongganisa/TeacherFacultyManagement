@extends('layouts.student')

@section('title', 'Professor Directory')

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
<!-- Main Directory Card -->
<div class="row">
    <div class="row mb-3">
        <div class="col-12">
            <h4><i class="fa-solid fa-person"></i> Professor Directory</h4>
            <p class="text-muted">Find and connect with our professors</p>
        </div>
    </div>
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-search me-2" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <h3 class="mb-0">Find a Professor</h3>
                </div>
                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                    <i class="fas fa-users me-1"></i> Faculty Directory
                </span>
            </div>
            
            <div class="card-body">
                <!-- Enhanced Search Section -->
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--soft-green); border: none; border-radius: 50px 0 0 50px;">
                                <i class="fas fa-search" style="color: var(--primary-green);"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, department, email, or location..." 
                                   style="border: 2px solid var(--soft-green); border-left: none; border-right: none; padding: 0.8rem 1rem;">
                            <button id="searchBtn" class="btn" style="background: var(--primary-green); color: white; border-radius: 0 50px 50px 0; padding: 0 2rem;">
                                <i class="fas fa-arrow-right me-2"></i>Search
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="departmentFilter" class="form-select" style="border: 2px solid var(--soft-green); border-radius: 50px; padding: 0.8rem 1.5rem;">
                            <option value="">All Departments</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Physics">Physics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Biology">Biology</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Business">Business</option>
                            <option value="Arts">Arts & Humanities</option>
                        </select>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: var(--soft-green);">
                            <tr>
                                <th class="py-3 ps-4">ID</th>
                                <th class="py-3"><i class="fas fa-user me-2"></i>Professor</th>
                                <th class="py-3"><i class="fas fa-envelope me-2"></i>Email</th>
                                <th class="py-3"><i class="fas fa-phone me-2"></i>Phone</th>
                                <th class="py-3"><i class="fas fa-building me-2"></i>Department</th>
                                <th class="py-3"><i class="fas fa-map-marker-alt me-2"></i>Location</th>
                                <th class="py-3 pe-4"><i class="fas fa-cog me-2"></i>Action</th>
                            </tr>
                        </thead>
                        <tbody id="resultsBody">
                            <!-- AJAX results will be loaded here -->
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="loading-spinner"></div>
                                    <p class="text-muted mt-3">Loading professors...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Results Info -->
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted" id="resultsInfo">
                        <i class="fas fa-info-circle me-1" style="color: var(--primary-green);"></i>
                        Showing <span id="displayCount">0</span> professors
                    </small>
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm" style="background: var(--soft-green); color: var(--primary-green-dark); border-radius: 50px 0 0 50px; padding: 0.4rem 1rem;">
                            <i class="fas fa-list"></i> List
                        </button>
                        <button type="button" class="btn btn-sm" style="background: var(--primary-green); color: white; border-radius: 0 50px 50px 0; padding: 0.4rem 1rem;">
                            <i class="fas fa-th"></i> Grid
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('searchInput');
    const btn = document.getElementById('searchBtn');
    const departmentFilter = document.getElementById('departmentFilter');
    const body = document.getElementById('resultsBody');
    const totalProfessors = document.getElementById('totalProfessors');
    const totalDepartments = document.getElementById('totalDepartments');
    const totalLocations = document.getElementById('totalLocations');
    const displayCount = document.getElementById('displayCount');
    let timeout;
    let allTeachers = [];

    function updateStats(data) {
        // Update total professors
        totalProfessors.textContent = data.length;
        
        // Calculate unique departments
        const departments = new Set(data.map(t => t.department).filter(d => d && d !== '-'));
        totalDepartments.textContent = departments.size;
        
        // Calculate unique locations
        const locations = new Set(data.map(t => t.location).filter(l => l && l !== '-'));
        totalLocations.textContent = locations.size;
        
        // Update display count
        displayCount.textContent = data.length;
    }

    function doSearch(){
        const searchVal = input.value.trim();
        const deptVal = departmentFilter.value;
        
        fetch('{{ route("student.teachers.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                search: searchVal,
                department: deptVal 
            })
        })
            .then(response => response.json())
            .then(data => {
                allTeachers = data;
                let html = '';
                
                if (data.length > 0) {
                    data.forEach(teacher => {
                        // Determine badge color based on department
                        const deptColors = {
                            'Computer Science': 'primary',
                            'Mathematics': 'success',
                            'Physics': 'info',
                            'Chemistry': 'warning',
                            'Biology': 'danger',
                            'Engineering': 'dark'
                        };
                        
                        const deptColor = deptColors[teacher.department] || 'secondary';
                        
                        html += `<tr>
                            <td class="ps-4">
                                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                                    ${teacher.id}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                        <i class="fas fa-user-graduate text-success"></i>
                                    </div>
                                    <div>
                                        <strong style="color: var(--text-dark);">${teacher.first_name} ${teacher.last_name}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:${teacher.email}" class="text-decoration-none" style="color: var(--primary-green);">
                                    <i class="fas fa-envelope me-1"></i>${teacher.email}
                                </a>
                            </td>
                            <td>
                                ${teacher.phone && teacher.phone !== '-' ? 
                                    `<span><i class="fas fa-phone-alt me-1" style="color: var(--primary-green);"></i>${teacher.phone}</span>` : 
                                    '<span class="text-muted">-</span>'}
                            </td>
                            <td>
                                <span class="badge" style="background: var(--soft-green); color: var(--primary-green-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                                    <i class="fas fa-building me-1"></i>${teacher.department || 'General'}
                                </span>
                            </td>
                            <td>
                                ${teacher.location && teacher.location !== '-' ? 
                                    `<span class="badge" style="background: var(--primary-green-light); color: var(--text-dark); padding: 0.5rem 1rem; border-radius: 50px;">
                                        <i class="fas fa-map-marker-alt me-1"></i>${teacher.location}
                                    </span>` : 
                                    '<span class="text-muted">-</span>'}
                            </td>
                            <td class="pe-4">
                                <a href="/student/teacher-profile/${teacher.id}" class="btn btn-sm" style="background: var(--primary-green); color: white; border-radius: 50px; padding: 0.4rem 1.2rem;">
                                    <i class="fas fa-user me-1"></i> View Profile
                                </a>
                            </td>
                        </tr>`;
                    });
                    
                    // Update results info
                    document.getElementById('resultsInfo').innerHTML = `
                        <i class="fas fa-info-circle me-1" style="color: var(--primary-green);"></i>
                        Showing <strong>${data.length}</strong> professor${data.length > 1 ? 's' : ''}
                    `;
                } else {
                    html = '<tr><td colspan="7" class="text-center py-5">' +
                        '<div class="mb-3"><i class="fas fa-user-slash fa-4x" style="color: var(--primary-green-light);"></i></div>' +
                        '<h5 class="text-muted mb-2">No professors found</h5>' +
                        '<p class="text-muted small">Try adjusting your search or filter criteria</p>' +
                        '</td></tr>';
                    
                    document.getElementById('resultsInfo').innerHTML = `
                        <i class="fas fa-info-circle me-1" style="color: var(--primary-green);"></i>
                        Showing <strong>0</strong> professors
                    `;
                }
                
                body.innerHTML = html;
                updateStats(data);
            })
            .catch(error => {
                console.error('Error:', error);
                body.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-danger">Error loading professors. Please try again.</td></tr>';
            });
    }

    // Search input with debounce
    input.addEventListener('input', function(){ 
        clearTimeout(timeout); 
        timeout = setTimeout(doSearch, 250); 
    });
    
    // Search button click
    btn.addEventListener('click', doSearch);
    
    // Department filter change
    departmentFilter.addEventListener('change', doSearch);

    // Initial load
    doSearch();
});
</script>
@endpush