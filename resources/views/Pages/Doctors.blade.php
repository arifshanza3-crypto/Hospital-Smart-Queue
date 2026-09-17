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

    <!-- Swipe Hint -->
    <div class="table-scroll-hint">
        <i class="fas fa-arrows-left-right"></i> Swipe to see more
    </div>

    <!-- Table Wrapper -->
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
                                {{-- ✅ View Button --}}
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

                                {{-- ✅ Plus Button (Change Status) --}}
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
{{-- ✅ VIEW DOCTOR MODAL - TEAL THEME           --}}
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
                    <div class="doctor-detail-label">DOCTOR ID</div>
                    <div class="doctor-detail-value" id="modalDoctorId">#0</div>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">FULL NAME</div>
                    <div class="doctor-detail-value" id="modalFullName">-</div>
                </div>
            </div>

            {{-- Specialization --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">SPECIALIZATION</div>
                    <div class="doctor-detail-value" id="modalSpecializationValue">-</div>
                </div>
            </div>

            {{-- Qualification --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">QUALIFICATION</div>
                    <div class="doctor-detail-value" id="modalQualification">-</div>
                </div>
            </div>

            {{-- Email --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">EMAIL ADDRESS</div>
                    <div class="doctor-detail-value" id="modalEmail">-</div>
                </div>
            </div>

            {{-- Phone --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">PHONE NUMBER</div>
                    <div class="doctor-detail-value" id="modalPhone">-</div>
                </div>
            </div>

            {{-- Status --}}
            <div class="doctor-detail-row">
                <div class="doctor-detail-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="doctor-detail-content">
                    <div class="doctor-detail-label">STATUS</div>
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
/* ✅ VIEW DOCTOR MODAL - TEAL THEME            */
/* ============================================ */
.doctor-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(11, 46, 51, 0.7);
    backdrop-filter: blur(6px);
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
    max-width: 480px;
    width: 100%;
    box-shadow: 0 25px 80px rgba(11, 46, 51, 0.4);
    animation: modalSlideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

@keyframes modalSlideIn {
    from { transform: translateY(-40px) scale(0.92); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

/* ✅ Header - Dark Teal Gradient (Website Theme) */
.doctor-modal-header {
    background: linear-gradient(135deg, #071a1c 0%, #1a4a50 50%, #0b2e33 100%);
    padding: 32px 24px 28px;
    color: white;
    position: relative;
    text-align: center;
    border-radius: 20px 20px 0 0;
    border-bottom: 3px solid #00d4ff;
}

.doctor-modal-header .modal-close {
    position: absolute;
    top: 18px;
    right: 20px;
    background: rgba(0, 212, 255, 0.15);
    border: 1px solid rgba(0, 212, 255, 0.3);
    color: #00d4ff;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.doctor-modal-header .modal-close:hover {
    background: #00d4ff;
    color: #0b2e33;
    transform: rotate(90deg);
    box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
}

/* ✅ Avatar - Teal Ring */
.doctor-modal-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(0, 212, 255, 0.12);
    backdrop-filter: blur(10px);
    border: 3px solid #00d4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 38px;
    color: #00d4ff;
    box-shadow: 0 8px 32px rgba(0, 212, 255, 0.35);
    transition: all 0.4s ease;
}

.doctor-modal-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(0, 212, 255, 0.55);
}

.doctor-modal-header h3 {
    margin: 0 0 6px 0;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 0.3px;
    color: #ffffff;
}

.doctor-modal-header p {
    margin: 0;
    font-size: 13px;
    opacity: 0.95;
    font-weight: 500;
    letter-spacing: 0.3px;
    color: #00d4ff;
}

.doctor-modal-body {
    padding: 24px 24px 20px;
    overflow-y: auto;
    flex: 1;
    background: #ffffff;
}

.doctor-modal-body::-webkit-scrollbar {
    width: 6px;
}

.doctor-modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.doctor-modal-body::-webkit-scrollbar-thumb {
    background: #00d4ff;
    border-radius: 10px;
}

.doctor-modal-body::-webkit-scrollbar-thumb:hover {
    background: #0088b3;
}

/* ✅ Detail Rows */
.doctor-detail-row {
    display: flex;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid rgba(0, 212, 255, 0.08);
}

.doctor-detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.doctor-detail-row:first-child {
    padding-top: 0;
}

/* ✅ Icon - Teal Theme */
.doctor-detail-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(0, 212, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #00d4ff;
    font-size: 17px;
    margin-right: 14px;
    flex-shrink: 0;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 212, 255, 0.1);
}

.doctor-detail-row:hover .doctor-detail-icon {
    background: rgba(0, 212, 255, 0.2);
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 212, 255, 0.2);
}

.doctor-detail-content {
    flex: 1;
    min-width: 0;
}

.doctor-detail-label {
    font-size: 10px;
    color: #7a8a8e;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    margin-bottom: 4px;
}

.doctor-detail-value {
    font-size: 14px;
    color: #0b2e33;
    font-weight: 600;
    word-break: break-word;
    line-height: 1.4;
}

/* ✅ Footer - Teal Theme */
.doctor-modal-footer {
    padding: 16px 24px 20px;
    background: #ffffff;
    border-top: 1px solid rgba(0, 212, 255, 0.1);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.doctor-modal-footer .btn-close-modal {
    padding: 11px 28px;
    background: rgba(0, 212, 255, 0.08);
    color: #0b2e33;
    border: 1px solid rgba(0, 212, 255, 0.2);
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.doctor-modal-footer .btn-close-modal:hover {
    background: #00d4ff;
    color: white;
    border-color: #00d4ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0, 212, 255, 0.3);
}

/* ✅ Plus Button - Teal Theme */
.action-btn.plus {
    background: rgba(0, 212, 255, 0.1);
    color: #0088b3;
    border: 1px solid rgba(0, 212, 255, 0.2);
}

.action-btn.plus:hover {
    background: #00d4ff;
    color: white;
    box-shadow: 0 4px 16px rgba(0, 212, 255, 0.3);
}

/* ✅ View Button - Teal Theme */
.action-btn.view {
    background: rgba(0, 212, 255, 0.08);
    color: #0b2e33;
    border: 1px solid rgba(0, 212, 255, 0.15);
}

.action-btn.view:hover {
    background: #0b2e33;
    color: #00d4ff;
    box-shadow: 0 4px 16px rgba(11, 46, 51, 0.3);
}

/* ============================================ */
/* ✅ RESPONSIVE MODAL                          */
/* ============================================ */
@media (max-width: 768px) {
    .doctor-modal-overlay { padding: 12px; }
    .doctor-modal { border-radius: 18px; }
    .doctor-modal-header { padding: 26px 20px 22px; }
    .doctor-modal-avatar { width: 76px; height: 76px; font-size: 30px; }
    .doctor-modal-header h3 { font-size: 18px; }
    .doctor-modal-header p { font-size: 12px; }
    .doctor-modal-body { padding: 20px; }
    .doctor-detail-row { padding: 12px 0; }
    .doctor-detail-icon { width: 40px; height: 40px; font-size: 15px; margin-right: 12px; }
    .doctor-detail-value { font-size: 13px; }
    .doctor-detail-label { font-size: 9.5px; }
    .doctor-modal-footer { padding: 14px 20px 18px; }
    .doctor-modal-footer .btn-close-modal { padding: 10px 22px; font-size: 13px; }
}

@media (max-width: 576px) {
    .doctor-modal-header { padding: 22px 16px 18px; }
    .doctor-modal-avatar { width: 68px; height: 68px; font-size: 26px; }
    .doctor-modal-header h3 { font-size: 17px; }
    .doctor-modal-header p { font-size: 11.5px; }
    .doctor-modal-body { padding: 16px; }
    .doctor-detail-row { padding: 10px 0; }
    .doctor-detail-icon { width: 36px; height: 36px; font-size: 14px; margin-right: 10px; border-radius: 10px; }
    .doctor-detail-label { font-size: 9px; letter-spacing: 0.8px; }
    .doctor-detail-value { font-size: 12.5px; }
    .doctor-modal-footer { padding: 12px 16px 16px; }
    .doctor-modal-footer .btn-close-modal { padding: 9px 20px; font-size: 12.5px; width: 100%; justify-content: center; }
}

@media (max-width: 380px) {
    .doctor-modal-header { padding: 18px 14px 16px; }
    .doctor-modal-avatar { width: 60px; height: 60px; font-size: 22px; border-width: 2px; }
    .doctor-modal-header h3 { font-size: 15px; }
    .doctor-detail-icon { width: 34px; height: 34px; font-size: 13px; margin-right: 10px; }
    .doctor-detail-value { font-size: 12px; }
}
</style>

<script>
// ============================================
// ✅ VIEW DOCTOR MODAL
// ============================================
function viewDoctor(name, email, phone, specialization, qualification, status, doctorId) {
    document.getElementById('modalName').textContent = 'Dr. ' + name;
    document.getElementById('modalSpecialization').textContent = specialization;
    document.getElementById('modalDoctorId').textContent = '#' + doctorId;
    document.getElementById('modalFullName').textContent = 'Dr. ' + name;
    document.getElementById('modalSpecializationValue').textContent = specialization;
    document.getElementById('modalQualification').textContent = qualification || 'N/A';
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalPhone').textContent = phone || 'N/A';
    document.getElementById('modalStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);

    document.getElementById('doctorModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDoctorModal() {
    document.getElementById('doctorModal').classList.remove('active');
    document.body.style.overflow = '';
}

document.getElementById('doctorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDoctorModal();
    }
});

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