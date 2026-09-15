@extends('Layout.admin-layout')

@section('page-title', 'Services Management')
@section('breadcrumb', 'Manage Services')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       SERVICES MANAGEMENT - LIGHT THEME
       ============================================ */
    
    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.06);
        --accent-1: #3b82f6;
        --accent-2: #6366f1;
        --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
    }

    .services-management-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    /* ===== HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header-left {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .page-header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-left h1 i {
        color: var(--accent-1);
        font-size: 28px;
    }

    .page-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
        margin: 4px 0 0 0;
    }

    .page-header-left p i {
        color: var(--accent-1);
        margin-right: 4px;
    }

    .btn-primary-gradient {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
        white-space: nowrap;
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.35);
        color: white;
        text-decoration: none;
    }

    /* ===== STATISTICS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        border-color: #dbeafe;
    }

    .stat-card .stat-icon {
        font-size: 28px;
        opacity: 0.8;
    }

    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 8px 0 2px;
        line-height: 1.2;
    }

    .stat-card .stat-label {
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
    }

    .stat-card.purple::before { background: var(--accent-gradient); }
    .stat-card.purple .stat-icon { color: #6366f1; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.orange::before { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .stat-card.orange .stat-icon { color: #f59e0b; }

    /* ===== SEARCH & FILTER ===== */
    .search-filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: var(--bg-card);
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .search-wrapper {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .search-wrapper input {
        width: 100%;
        padding: 10px 16px 10px 44px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
    }

    .search-wrapper input::placeholder { color: var(--text-muted); }

    .search-wrapper input:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .filter-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-wrapper select {
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 13px;
        cursor: pointer;
        outline: none;
        min-width: 140px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .filter-wrapper select:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .btn-reset {
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
        font-family: inherit;
        font-size: 13px;
        white-space: nowrap;
    }

    .btn-reset:hover {
        background: #f1f5f9;
        color: var(--text-primary);
    }

    /* ===== ALERTS ===== */
    .alert-modern {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid;
        background: var(--bg-card);
        box-shadow: var(--shadow);
        font-size: 14px;
        line-height: 1.5;
    }

    .alert-modern.success {
        border-color: var(--success);
        color: #065f46;
        background: #f0fdf4;
    }

    .alert-modern.success i { color: var(--success); }

    .alert-modern.error {
        border-color: var(--danger);
        color: #991b1b;
        background: #fef2f2;
    }

    .alert-modern.error i { color: var(--danger); }

    .alert-modern i { font-size: 18px; flex-shrink: 0; }

    /* ===== TABLE SCROLL HINT ===== */
    .table-scroll-hint {
        display: none;
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 10px;
        text-align: center;
        padding: 8px;
        background: rgba(59, 130, 246, 0.06);
        border-radius: 8px;
        border: 1px solid rgba(59, 130, 246, 0.1);
        font-weight: 500;
    }

    .table-scroll-hint i {
        color: var(--accent-1);
        margin-right: 6px;
    }

    /* ===== TABLE WRAPPER ===== */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .table-wrapper::-webkit-scrollbar { height: 6px; }
    .table-wrapper::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .table-wrapper::-webkit-scrollbar-thumb { background: var(--accent-1); border-radius: 10px; }

    /* ===== TABLE ===== */
    .table-container {
        width: 100%;
        min-width: 950px;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container thead {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .table-container thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .table-container tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-size: 14px;
    }

    .table-container tbody tr { transition: all 0.3s ease; }
    .table-container tbody tr:hover { background: #f8fafc; }
    .table-container tbody tr:last-child td { border-bottom: none; }

    .service-id {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 13px;
    }

    /* Service Info Cell */
    .service-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .service-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .service-name {
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
    }

    .service-description {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 2px;
        max-width: 280px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Department Badge */
    .department-badge {
        background: #eef2ff;
        border: 1px solid #e0e7ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4338ca;
        display: inline-block;
        white-space: nowrap;
        font-weight: 500;
    }

    /* Price */
    .price-text {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 14px;
        white-space: nowrap;
    }

    /* Duration */
    .duration-text {
        color: var(--text-secondary);
        font-size: 13px;
        white-space: nowrap;
    }

    /* Status Badges */
    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge-modern.active {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge-modern.inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .status-badge-modern .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-badge-modern.active .status-dot { background: #10b981; }
    .status-badge-modern.inactive .status-dot { background: #ef4444; }

    /* ===== ACTION BUTTONS ===== */
    .action-group {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: visible;
        flex-shrink: 0;
    }

    .action-btn:hover {
        transform: translateY(-2px) scale(1.05);
    }

    .action-btn.edit {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .action-btn.edit:hover {
        background: #1e40af;
        color: white;
        box-shadow: 0 4px 16px rgba(30, 64, 175, 0.3);
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .action-btn.delete:hover {
        background: #991b1b;
        color: white;
        box-shadow: 0 4px 16px rgba(153, 27, 27, 0.3);
    }

    .action-btn.toggle {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .action-btn.toggle:hover {
        background: #d97706;
        color: white;
        box-shadow: 0 4px 16px rgba(217, 119, 6, 0.3);
    }

    .action-btn.toggle.active-btn {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .action-btn.toggle.active-btn:hover {
        background: #10b981;
        color: white;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    /* Tooltip - sirf desktop par dikhe */
    .action-btn .tooltip-text {
        visibility: hidden;
        opacity: 0;
        width: auto;
        background: #1e293b;
        color: white;
        text-align: center;
        border-radius: 6px;
        padding: 4px 10px;
        position: absolute;
        z-index: 10;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
        white-space: nowrap;
        pointer-events: none;
    }

    .action-btn .tooltip-text::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #1e293b transparent transparent transparent;
    }

    .action-btn:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 56px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .empty-state .btn-primary-gradient {
        display: inline-flex;
    }

    /* ===== LOADER ===== */
    .loader {
        display: none;
        text-align: center;
        padding: 40px;
    }

    .loader.show { display: block; }

    .loader i {
        font-size: 32px;
        color: var(--accent-1);
        animation: spin 1s linear infinite;
    }

    .loader p { color: var(--text-secondary); margin-top: 8px; }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - LARGE TABLET (max 1200px)   */
    /* ============================================ */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); }
        .stat-card .stat-number { font-size: 26px; }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - TABLET (max 992px)           */
    /* ============================================ */
    @media (max-width: 992px) {
        .services-management-wrapper { padding: 20px 18px; }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-left h1 { font-size: 22px; }

        .btn-primary-gradient {
            width: 100%;
            justify-content: center;
        }

        .search-filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrapper { max-width: 100%; }

        .filter-wrapper { flex-wrap: wrap; }

        .filter-wrapper select { flex: 1; min-width: 120px; }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - MOBILE (max 768px)           */
    /* ============================================ */
    @media (max-width: 768px) {
        .services-management-wrapper { padding: 16px 12px; }

        .page-header { gap: 12px; margin-bottom: 20px; }

        .page-header-left h1 {
            font-size: 20px;
            gap: 8px;
        }

        .page-header-left h1 i { font-size: 20px; }
        .page-header-left p { font-size: 13px; }

        .btn-primary-gradient {
            padding: 12px 20px;
            font-size: 13px;
        }

        /* Stats grid - 2 columns */
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 14px 16px;
            border-radius: 12px;
        }

        .stat-card .stat-icon { font-size: 20px; }
        .stat-card .stat-number {
            font-size: 20px;
            margin: 4px 0 2px;
        }
        .stat-card .stat-label { font-size: 11px; }

        /* Search & Filter */
        .search-filter-bar { padding: 10px 12px; gap: 10px; }

        .search-wrapper input {
            padding: 11px 14px 11px 40px;
            font-size: 13px;
            border-radius: 10px;
        }

        .search-wrapper i { left: 13px; font-size: 13px; }

        .filter-wrapper { width: 100%; gap: 8px; }

        .filter-wrapper select {
            flex: 1;
            min-width: 0;
            padding: 11px 14px;
            font-size: 13px;
            border-radius: 10px;
        }

        .btn-reset {
            padding: 11px 14px;
            font-size: 12px;
            border-radius: 10px;
        }

        /* Swipe hint */
        .table-scroll-hint { display: block; }

        /* Table */
        .table-wrapper { border-radius: 12px; }
        .table-container { min-width: 850px; }

        .table-container thead th {
            padding: 10px 14px;
            font-size: 10px;
        }

        .table-container tbody td {
            padding: 12px 14px;
            font-size: 13px;
        }

        .service-icon { width: 36px; height: 36px; font-size: 14px; }
        .service-name { font-size: 13px; }
        .service-description { font-size: 11px; max-width: 200px; }

        .department-badge,
        .status-badge-modern {
            font-size: 11px;
            padding: 4px 10px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .action-group { gap: 4px; }

        .alert-modern {
            padding: 12px 16px;
            font-size: 13px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .empty-state { padding: 40px 16px; }
        .empty-state i { font-size: 42px; }
        .empty-state h3 { font-size: 17px; }
        .empty-state p { font-size: 13px; }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 576px)     */
    /* ============================================ */
    @media (max-width: 576px) {
        .services-management-wrapper { padding: 12px 10px; }

        .page-header-left h1 { font-size: 18px; }
        .page-header-left h1 i { font-size: 16px; }
        .page-header-left p { font-size: 12px; }

        .btn-primary-gradient {
            padding: 11px 16px;
            font-size: 12px;
            border-radius: 10px;
        }

        /* Stats */
        .stat-card { padding: 12px 10px; }
        .stat-card .stat-icon { font-size: 16px; }
        .stat-card .stat-number { font-size: 18px; }
        .stat-card .stat-label { font-size: 10px; }

        /* Search */
        .search-wrapper input {
            padding: 10px 12px 10px 36px;
            font-size: 12.5px;
        }

        .search-wrapper i { left: 12px; font-size: 12px; }

        .filter-wrapper select {
            padding: 10px 12px;
            font-size: 12.5px;
        }

        .btn-reset {
            padding: 10px 12px;
            font-size: 11px;
        }

        .btn-reset span { display: none; }

        /* Table */
        .table-container { min-width: 780px; }

        .table-container thead th {
            padding: 9px 12px;
            font-size: 9.5px;
        }

        .table-container tbody td {
            padding: 10px 12px;
            font-size: 12px;
        }

        .service-icon { width: 32px; height: 32px; font-size: 12px; }
        .service-name { font-size: 12px; }
        .service-description { font-size: 10px; max-width: 150px; }

        .department-badge,
        .status-badge-modern {
            font-size: 10px;
            padding: 3px 8px;
        }

        .status-dot {
            width: 5px;
            height: 5px;
            margin-right: 4px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            font-size: 11px;
            border-radius: 7px;
        }

        .action-group { gap: 3px; }

        .table-scroll-hint {
            font-size: 11px;
            padding: 6px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - EXTRA SMALL (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .services-management-wrapper { padding: 10px 8px; }

        .page-header-left h1 { font-size: 16px; }

        .stat-card { padding: 10px 8px; }
        .stat-card .stat-icon { font-size: 14px; }
        .stat-card .stat-number { font-size: 16px; }
        .stat-card .stat-label { font-size: 9px; }

        .search-wrapper input {
            padding: 9px 10px 9px 34px;
            font-size: 12px;
        }

        .filter-wrapper select {
            padding: 9px 10px;
            font-size: 12px;
        }

        .btn-reset { padding: 9px 10px; }

        .table-container { min-width: 720px; }

        .table-container thead th {
            padding: 8px 10px;
            font-size: 9px;
        }

        .table-container tbody td {
            padding: 9px 10px;
            font-size: 11.5px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
    }
</style>

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
        $totalRevenue = $services->sum('price') ?? 0;
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
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-number">PKR {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">Revenue Potential</div>
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

    <!-- Swipe Hint (Sirf mobile par) -->
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

<script>
    // ============================================
    // DELETE SERVICE
    // ============================================
    function deleteService(id) {
        if (confirm('⚠️ Are you sure you want to delete this service?\n\nThis action cannot be undone!')) {
            showLoader();

            fetch('/admin/services/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    showNotification('success', data.message || 'Service deleted');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', data.message || 'Error deleting service');
                }
            })
            .catch(error => {
                hideLoader();
                showNotification('error', 'Network error. Please try again.');
                console.error('Error:', error);
            });
        }
    }

    // ============================================
    // TOGGLE STATUS
    // ============================================
    function toggleStatus(id, currentStatus) {
        let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        let action = newStatus === 'active' ? 'activate' : 'deactivate';

        if (confirm(`Are you sure you want to ${action} this service?`)) {
            showLoader();

            fetch(`/admin/services/${id}/status/${newStatus}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoader();
                if (data.success) {
                    showNotification('success', `Service ${action}d successfully!`);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', data.message || 'Error updating status');
                }
            })
            .catch(error => {
                hideLoader();
                showNotification('error', 'Network error. Please try again.');
                console.error('Error:', error);
            });
        }
    }

    // ============================================
    // SEARCH & FILTER
    // ============================================
    const searchInput = document.getElementById('search');
    const filterStatus = document.getElementById('filterStatus');

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);

    function filterTable() {
        let searchValue = (document.getElementById('search')?.value || '').toLowerCase();
        let statusValue = document.getElementById('filterStatus')?.value || '';
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.id === 'noResultsMsg') return;
            if (row.querySelector('td')) {
                let text = row.textContent.toLowerCase();
                let statusCell = row.querySelector('.status-badge-modern');
                let status = '';

                if (statusCell) {
                    let statusText = statusCell.textContent.trim().toLowerCase();
                    if (statusText.includes('active') && !statusText.includes('inactive')) status = 'active';
                    if (statusText.includes('inactive')) status = 'inactive';
                }

                let matchesSearch = text.includes(searchValue);
                let matchesStatus = !statusValue || status === statusValue;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsMsg) {
                let tbody = document.getElementById('tableBody');
                let msgRow = document.createElement('tr');
                msgRow.id = 'noResultsMsg';
                msgRow.innerHTML = `
                    <td colspan="7" style="padding: 40px; text-align: center;">
                        <div class="empty-state" style="padding: 20px;">
                            <i class="fas fa-search" style="font-size: 40px;"></i>
                            <h3>No Matching Services</h3>
                            <p>Try adjusting your search or filter criteria</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(msgRow);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // ============================================
    // RESET FILTERS
    // ============================================
    function resetFilters() {
        const s = document.getElementById('search');
        const f = document.getElementById('filterStatus');
        if (s) s.value = '';
        if (f) f.value = '';
        filterTable();
    }

    // ============================================
    // NOTIFICATION - Mobile friendly
    // ============================================
    function showNotification(type, message) {
        let notification = document.createElement('div');
        let bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
        let borderColor = type === 'success' ? '#10b981' : '#ef4444';
        let textColor = type === 'success' ? '#065f46' : '#991b1b';
        let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        const isMobile = window.innerWidth <= 576;
        const styles = isMobile
            ? `left: 12px; right: 12px; top: 70px;`
            : `right: 24px; top: 80px; min-width: 280px; max-width: 400px;`;

        notification.style.cssText = `
            position: fixed;
            ${styles}
            padding: 14px 20px;
            background: ${bgColor};
            border-left: 4px solid ${borderColor};
            color: ${textColor};
            border-radius: 12px;
            z-index: 9999;
            animation: svcSlideIn 0.3s ease;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
        `;
        notification.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'svcSlideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // ============================================
    // LOADER
    // ============================================
    function showLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.add('show');
    }

    function hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.remove('show');
    }

    // ============================================
    // STYLES FOR ANIMATIONS
    // ============================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes svcSlideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes svcSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection