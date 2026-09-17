@extends('Layout.admin-layout')

@section('page-title', 'Services Management')
@section('breadcrumb', 'Manage Services')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/service_managemnt.css') }}">

<div class="services-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-concierge-bell"></i> Services Management
            </h1>
            <p><i class="fas fa-arrow-trend-up"></i> Manage hospital medical services and procedures</p>
        </div>
        <div>
            <a href="{{ route('admin.services.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New Service
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
        $total = $services->count();
        $active = $services->where('status', 'active')->count();
        $inactive = $services->where('status', 'inactive')->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Services</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active }}</div>
            <div class="stat-label">Active Services</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number">{{ $inactive }}</div>
            <div class="stat-label">Inactive Services</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" placeholder="Search services by name, department...">
        </div>
        <div class="filter-wrapper">
            <select id="filterStatus">
                <option value="">All Status</option>
                <option value="active">🟢 Active</option>
                <option value="inactive">🔴 Inactive</option>
            </select>
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
                        <th>Service</th>
                        <th>Department</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($services as $service)
                    <tr>
                        <td>
                            <span class="service-id">#{{ $service->id }}</span>
                        </td>
                        <td>
                            <div class="service-cell">
                                <div class="service-icon">
                                    <i class="{{ $service->icon ?? 'fas fa-stethoscope' }}"></i>
                                </div>
                                <div>
                                    <div class="service-name">{{ $service->name }}</div>
                                    <div class="service-description">{{ Str::limit($service->description, 60) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="department-badge">{{ $service->department ?? 'General' }}</span>
                        </td>
                        <td>
                            <span class="price-text">PKR {{ number_format($service->price, 0) }}</span>
                        </td>
                        <td>
                            <span class="duration-text">{{ $service->duration ?? '30 mins' }}</span>
                        </td>
                        <td>
                            <span class="status-badge-modern {{ $service->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($service->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="action-btn edit" title="Edit Service">
                                    <i class="fas fa-edit"></i>
                                    <span class="tooltip-text">Edit</span>
                                </a>
                                
                                <button onclick="toggleStatus({{ $service->id }}, '{{ $service->status }}')" 
                                        class="action-btn toggle {{ $service->status == 'active' ? 'active-btn' : '' }}" 
                                        title="{{ $service->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $service->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                    <span class="tooltip-text">{{ $service->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                                </button>
                                
                                <button onclick="deleteService({{ $service->id }})" class="action-btn delete" title="Delete Service">
                                    <i class="fas fa-trash"></i>
                                    <span class="tooltip-text">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-concierge-bell"></i>
                                <h3>No Services Found</h3>
                                <p>Get started by adding your first service to the system.</p>
                                <a href="{{ route('admin.services.create') }}" class="btn-primary-gradient">
                                    <i class="fas fa-plus"></i> Add New Service
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

{{-- ✅ CSRF Token --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ✅ JS File --}}
<script src="{{ asset('js/services_management.js') }}"></script>
@endsection