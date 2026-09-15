@extends('Layout.admin-layout')

@section('page-title', 'Doctor Management')
@section('breadcrumb', 'Manage Doctors')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Doctor_management.css') }}">

<div class="doctor-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-user-md"></i> Doctor Management
            </h1>
            <p><i class="fas fa-arrow-trend-up"></i> Manage hospital specialized doctors</p>
        </div>
        <div>
            <a href="{{ route('admin.doctors.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New Doctor
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-modern success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    @php
        $total = $doctors->count();
        $active = $doctors->where('status', 'active')->count();
        $inactive = $doctors->where('status', 'inactive')->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Doctors</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $inactive }}</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" placeholder="Search doctors by name, specialization, or email...">
        </div>
        <div class="filter-wrapper">
            <select id="filterStatus">
                <option value="">All Status</option>
                <option value="active">🟢 Active</option>
                <option value="inactive">🔴 Inactive</option>
            </select>
            <button class="btn-reset" onclick="resetFilters()">
                <i class="fas fa-undo"></i> <span>Reset</span>
            </button>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader">
        <i class="fas fa-spinner"></i>
        <p>Loading...</p>
    </div>

    <!-- ✅ Swipe Hint (Sirf mobile par dikhega) -->
    <div class="table-scroll-hint">
        <i class="fas fa-arrows-left-right"></i> Swipe to see more
    </div>

    <!-- Table Wrapper (Horizontal scroll ke liye) -->
    <div class="table-wrapper">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            <span class="doctor-id">#{{ $doctor->id }}</span>
                        </td>
                        <td>
                            <div class="doctor-cell">
                                <div class="doctor-avatar">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <div class="doctor-name">Dr. {{ $doctor->name }}</div>
                                    @if($doctor->qualification)
                                        <div class="doctor-qualification">{{ $doctor->qualification }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="specialization-badge">{{ $doctor->specialization }}</span>
                        </td>
                        <td class="text-muted-cell">{{ $doctor->email }}</td>
                        <td class="text-muted-cell">{{ $doctor->phone }}</td>
                        <td>
                            <span class="status-badge-modern {{ $doctor->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($doctor->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button onclick="updateStatus({{ $doctor->id }}, 'active')" 
                                        class="action-btn status-active {{ $doctor->status == 'active' ? 'active' : '' }}" 
                                        title="Set Active">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button onclick="updateStatus({{ $doctor->id }}, 'inactive')" 
                                        class="action-btn status-inactive {{ $doctor->status == 'inactive' ? 'active' : '' }}" 
                                        title="Set Inactive">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="action-btn edit" title="Edit Doctor">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteDoctor({{ $doctor->id }})" class="action-btn delete" title="Delete Doctor">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <h3>No Doctors Found</h3>
                                <p>Get started by adding your first doctor to the system.</p>
                                <a href="{{ route('admin.doctors.create') }}" class="btn-primary-gradient">
                                    <i class="fas fa-plus"></i> Add New Doctor
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// ✅ Update Doctor Status
function updateStatus(id, status) {
    if (!confirm('Are you sure you want to change status to ' + status + '?')) return;
    
    fetch('/admin/doctors/' + id + '/status/' + status, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error updating status');
    });
}

// ✅ Delete Doctor (kept for fallback, JS file has better version)
if (typeof window.deleteDoctor === 'undefined') {
    window.deleteDoctor = function(id) {
        if (!confirm('Are you sure you want to delete this doctor?')) return;
        
        fetch('/admin/doctors/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting doctor');
        });
    };
}
</script>

<script src="{{ asset('js/doctor-management.js') }}"></script>
@endsection