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
                                {{-- ✅ NEW: View Button --}}
                                <button type="button"
                                        class="action-btn view"
                                        title="View Doctor"
                                        onclick="viewDoctor(
                                            '{{ addslashes($doctor->name) }}',
                                            '{{ addslashes($doctor->email) }}',
                                            '{{ addslashes($doctor->phone ?? 'N/A') }}',
                                            '{{ addslashes($doctor->specialization) }}',
                                            '{{ addslashes($doctor->qualification ?? 'N/A') }}',
                                            '{{ addslashes($doctor->status) }}',
                                            '{{ $doctor->id }}'
                                        )">
                                    <i class="fas fa-eye"></i>
                                </button>

                                {{-- ❌ REMOVED: Active/Inactive Tick & Cross Buttons --}}

                                {{-- ✅ NEW: Plus Button (formerly toggle) --}}
                                <button type="button"
                                        class="action-btn plus"
                                        title="Change Status"
                                        onclick="changeStatus({{ $doctor->id }}, '{{ $doctor->status }}')">
                                    <i class="fas fa-plus"></i>
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

{{-- ============================================ --}}
{{-- ✅ VIEW DOCTOR MODAL                        --}}
{{-- ============================================ --}}
<div class="doctor-modal-overlay" id="doctorModal">
    <div class="doctor-modal">
        {{-- Header --}}
        <div class="doctor-modal-header">
            <button type="button" class="modal-close" onclick="closeDoctorModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="doctor-modal-avatar">
                <i class="fas fa-user-md"></i>
            </div>
            <h3 id="modalName">Doctor Name</h3>
            <p id="modalSpecialization">Specialization</p>
        </div>

        {{-- Body --}}
        <div class="doctor-modal-body">
            {{-- Doctor ID --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Doctor ID</div>
                    <div class="doctor-detail-value" id="modalDoctorId">#0</div>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Full Name</div>
                    <div class="doctor-detail-value" id="modalFullName">-</div>
                </div>
            </div>

            {{-- Specialization --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Specialization</div>
                    <div class="doctor-detail-value" id="modalSpecializationValue">-</div>
                </div>
            </div>

            {{-- Qualification --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Qualification</div>
                    <div class="doctor-detail-value" id="modalQualification">-</div>
                </div>
            </div>

            {{-- Email --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Email Address</div>
                    <div class="doctor-detail-value" id="modalEmail">-</div>
                </div>
            </div>

            {{-- Phone --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Phone Number</div>
                    <div class="doctor-detail-value" id="modalPhone">-</div>
                </div>
            </div>

            {{-- Status --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">Status</div>
                    <div class="doctor-detail-value" id="modalStatus">-</div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="doctor-modal-footer">
            <button type="button" class="btn-close-modal" onclick="closeDoctorModal()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<style>
/* ============================================ */
/* ✅ VIEW DOCTOR MODAL STYLES                  */
/* ============================================ */
.doctor-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
    animation: modalFadeIn 0.3s ease;
}

.doctor-modal-overlay.active {
    display: flex;
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.doctor-modal {
    background: white;
    border-radius: 20px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

@keyframes modalSlideIn {
    from { transform: translateY(-30px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

.doctor-modal-header {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    padding: 24px;
    color: white;
    position: relative;
    text-align: center;
}

.doctor-modal-header .modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.doctor-modal-header .modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.doctor-modal-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 32px;
    color: white;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.doctor-modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
}

.doctor-modal-header p {
    margin: 4px 0 0 0;
    font-size: 13px;
    opacity: 0.9;
}

.doctor-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.doctor-detail-row {
    display: flex;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
}

.doctor-detail-row:last-child {
    border-bottom: none;
}

.doctor-detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 16px;
    margin-right: 14px;
    flex-shrink: 0;
}

.doctor-detail-content {
    flex: 1;
    min-width: 0;
}

.doctor-detail-label {
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 3px;
}

.doctor-detail-value {
    font-size: 14px;
    color: #1e293b;
    font-weight: 600;
    word-break: break-word;
}

.doctor-modal-footer {
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.doctor-modal-footer .btn-close-modal {
    padding: 10px 24px;
    background: #e2e8f0;
    color: #475569;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.doctor-modal-footer .btn-close-modal:hover {
    background: #cbd5e1;
    color: #1e293b;
}

/* ✅ Plus Button Style */
.action-btn.plus {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.action-btn.plus:hover {
    background: #065f46;
    color: white;
    box-shadow: 0 4px 16px rgba(6, 95, 70, 0.3);
}

/* ✅ View Button Style */
.action-btn.view {
    background: #e0e7ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
}

.action-btn.view:hover {
    background: #3730a3;
    color: white;
    box-shadow: 0 4px 16px rgba(55, 48, 163, 0.3);
}

/* ============================================ */
/* ✅ RESPONSIVE MODAL                          */
/* ============================================ */
@media (max-width: 768px) {
    .doctor-modal-overlay { padding: 12px; }
    .doctor-modal { border-radius: 16px; }
    .doctor-modal-header { padding: 20px 16px; }
    .doctor-modal-avatar { width: 70px; height: 70px; font-size: 26px; }
    .doctor-modal-header h3 { font-size: 17px; }
    .doctor-modal-header p { font-size: 12px; }
    .doctor-modal-body { padding: 18px; }
    .doctor-detail-row { padding: 12px 0; }
    .doctor-detail-icon { width: 36px; height: 36px; font-size: 14px; margin-right: 12px; }
    .doctor-detail-value { font-size: 13px; }
    .doctor-modal-footer { padding: 14px 18px; }
    .doctor-modal-footer .btn-close-modal { padding: 10px 20px; font-size: 13px; }
}

@media (max-width: 576px) {
    .doctor-modal-header { padding: 18px 14px; }
    .doctor-modal-avatar { width: 60px; height: 60px; font-size: 22px; }
    .doctor-modal-header h3 { font-size: 16px; }
    .doctor-modal-body { padding: 16px; }
    .doctor-detail-icon { width: 32px; height: 32px; font-size: 13px; margin-right: 10px; }
    .doctor-detail-label { font-size: 10px; }
    .doctor-detail-value { font-size: 12.5px; }
}
</style>

<script>
// ============================================
// ✅ VIEW DOCTOR MODAL
// ============================================
function viewDoctor(name, email, phone, specialization, qualification, status, doctorId) {
    // Set modal data
    document.getElementById('modalName').textContent = 'Dr. ' + name;
    document.getElementById('modalSpecialization').textContent = specialization;
    document.getElementById('modalDoctorId').textContent = '#' + doctorId;
    document.getElementById('modalFullName').textContent = 'Dr. ' + name;
    document.getElementById('modalSpecializationValue').textContent = specialization;
    document.getElementById('modalQualification').textContent = qualification || 'N/A';
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalPhone').textContent = phone || 'N/A';
    document.getElementById('modalStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);

    // Show modal
    document.getElementById('doctorModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDoctorModal() {
    document.getElementById('doctorModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Close modal on overlay click
document.getElementById('doctorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDoctorModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDoctorModal();
    }
});

// ============================================
// ✅ CHANGE STATUS (Plus Button)
// ============================================
function changeStatus(id, currentStatus) {
    let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    if (!confirm('Change status to ' + newStatus + '?')) return;
    
    fetch('/admin/doctors/' + id + '/status/' + newStatus, {
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