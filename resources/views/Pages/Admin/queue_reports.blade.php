@extends('Layout.admin-layout')

@section('page-title', 'Queue Reports')
@section('breadcrumb', 'Analytics & Reports')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* ============================================
       QUEUE REPORTS - LIGHT THEME
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
        --info: #0ea5e9;
    }

    .reports-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    /* ===== PAGE HEADER ===== */
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

    .btn-export {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.25);
        white-space: nowrap;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.35);
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

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.cyan::before { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }
    .stat-card.cyan .stat-icon { color: #0ea5e9; }

    /* ===== STATUS SUMMARY ===== */
    .status-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .status-item {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .status-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .status-item .status-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .status-item .status-label {
        font-size: 13px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    .status-item .status-icon {
        font-size: 20px;
        display: block;
        margin-bottom: 4px;
    }

    .status-item.waiting { border-left: 4px solid #f59e0b; }
    .status-item.in-progress { border-left: 4px solid #0ea5e9; }
    .status-item.completed { border-left: 4px solid #10b981; }
    .status-item.cancelled { border-left: 4px solid #ef4444; }

    /* ===== CHARTS ===== */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }

    .chart-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        min-width: 0;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .chart-card .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card .chart-title i {
        color: var(--accent-1);
    }

    .chart-card .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }

    .chart-card canvas {
        max-height: 280px;
        width: 100% !important;
    }

    /* ===== FILTERS ===== */
    .filters-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .filter-group label i {
        color: var(--accent-1);
        margin-right: 6px;
    }

    .filter-control {
        width: 100%;
        padding: 10px 14px;
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

    .filter-control:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .btn-filter {
        background: var(--accent-gradient);
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }

    .btn-reset {
        background: #e2e8f0;
        color: var(--text-primary);
        padding: 10px 25px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #cbd5e1;
        color: var(--text-primary);
        text-decoration: none;
    }

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

    .table-wrapper::-webkit-scrollbar {
        height: 6px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: var(--accent-1);
        border-radius: 10px;
    }

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
        padding: 14px 18px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .table-container tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-size: 14px;
        white-space: nowrap;
    }

    .table-container tbody tr {
        transition: all 0.3s ease;
    }

    .table-container tbody tr:hover {
        background: #f8fafc;
    }

    .table-container tbody tr:last-child td {
        border-bottom: none;
    }

    .token-badge {
        font-weight: 700;
        color: var(--accent-1);
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .status-badge.waiting {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-badge.in-progress {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        margin-top: 20px;
    }

    .pagination-wrapper .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
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
        margin-bottom: 0;
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - LARGE TABLET (max 1200px)   */
    /* ============================================ */
    @media (max-width: 1200px) {
        .stats-grid,
        .status-summary-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-card .stat-number {
            font-size: 26px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - TABLET (max 992px)           */
    /* ============================================ */
    @media (max-width: 992px) {
        .reports-wrapper {
            padding: 20px 18px;
        }

        .charts-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-left h1 {
            font-size: 22px;
        }

        .btn-export {
            width: 100%;
            justify-content: center;
        }

        .chart-card .chart-container {
            height: 250px;
        }

        .stat-card .stat-number {
            font-size: 24px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - MOBILE (max 768px)           */
    /* ============================================ */
    @media (max-width: 768px) {
        .reports-wrapper {
            padding: 16px 12px;
        }

        .page-header {
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-header-left h1 {
            font-size: 20px;
            gap: 8px;
        }

        .page-header-left h1 i {
            font-size: 20px;
        }

        .page-header-left p {
            font-size: 13px;
        }

        /* Stats grid - 2 columns on mobile */
        .stats-grid,
        .status-summary-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 14px 16px;
            border-radius: 12px;
        }

        .stat-card .stat-icon {
            font-size: 20px;
        }

        .stat-card .stat-number {
            font-size: 22px;
            margin: 4px 0 2px;
        }

        .stat-card .stat-label {
            font-size: 11px;
        }

        .status-item {
            padding: 12px 14px;
            border-radius: 10px;
        }

        .status-item .status-icon {
            font-size: 18px;
        }

        .status-item .status-number {
            font-size: 22px;
        }

        .status-item .status-label {
            font-size: 11px;
        }

        /* Charts */
        .chart-card {
            padding: 16px 16px;
            border-radius: 12px;
        }

        .chart-card .chart-title {
            font-size: 14px;
            margin-bottom: 12px;
        }

        .chart-card .chart-container {
            height: 220px;
        }

        .chart-card canvas {
            max-height: 220px;
        }

        /* Filters */
        .filters-card {
            padding: 16px 14px;
            border-radius: 12px;
        }

        .filters-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .filter-control {
            padding: 10px 12px;
            font-size: 13.5px;
            border-radius: 9px;
        }

        .filter-actions {
            flex-direction: column;
            gap: 8px;
        }

        .btn-filter,
        .btn-reset {
            width: 100%;
            justify-content: center;
            padding: 11px 20px;
            font-size: 13px;
        }

        /* Swipe hint */
        .table-scroll-hint {
            display: block;
        }

        /* Table */
        .table-wrapper {
            border-radius: 12px;
        }

        .table-container {
            min-width: 850px;
        }

        .table-container thead th {
            padding: 10px 12px;
            font-size: 10px;
        }

        .table-container tbody td {
            padding: 11px 12px;
            font-size: 12.5px;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 10px;
        }

        .empty-state {
            padding: 40px 16px;
        }

        .empty-state i {
            font-size: 42px;
        }

        .empty-state h3 {
            font-size: 17px;
        }

        .empty-state p {
            font-size: 13px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 576px)     */
    /* ============================================ */
    @media (max-width: 576px) {
        .reports-wrapper {
            padding: 12px 10px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .page-header-left p {
            font-size: 12px;
        }

        .btn-export {
            padding: 11px 16px;
            font-size: 12px;
            border-radius: 10px;
        }

        /* Stats grid */
        .stat-card {
            padding: 12px 10px;
        }

        .stat-card .stat-icon {
            font-size: 16px;
        }

        .stat-card .stat-number {
            font-size: 18px;
        }

        .stat-card .stat-label {
            font-size: 10px;
            letter-spacing: 0.2px;
        }

        .status-item {
            padding: 10px 10px;
        }

        .status-item .status-icon {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .status-item .status-number {
            font-size: 18px;
        }

        .status-item .status-label {
            font-size: 10px;
        }

        /* Charts */
        .chart-card {
            padding: 14px 12px;
        }

        .chart-card .chart-title {
            font-size: 13px;
        }

        .chart-card .chart-container {
            height: 200px;
        }

        .chart-card canvas {
            max-height: 200px;
        }

        /* Filters */
        .filters-card {
            padding: 14px 12px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .filter-group label {
            font-size: 12px;
        }

        .filter-control {
            padding: 10px 12px;
            font-size: 13px;
        }

        .btn-filter,
        .btn-reset {
            padding: 10px 16px;
            font-size: 12px;
        }

        /* Table */
        .table-container {
            min-width: 780px;
        }

        .table-container thead th {
            padding: 9px 10px;
            font-size: 9.5px;
        }

        .table-container tbody td {
            padding: 10px 10px;
            font-size: 12px;
        }

        .status-badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .table-scroll-hint {
            font-size: 11px;
            padding: 6px;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 15px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - EXTRA SMALL (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .reports-wrapper {
            padding: 10px 8px;
        }

        .page-header-left h1 {
            font-size: 16px;
        }

        .page-header-left h1 i {
            font-size: 16px;
        }

        .stat-card {
            padding: 10px 8px;
        }

        .stat-card .stat-icon {
            font-size: 14px;
        }

        .stat-card .stat-number {
            font-size: 16px;
        }

        .stat-card .stat-label {
            font-size: 9px;
        }

        .status-item .status-number {
            font-size: 16px;
        }

        .status-item .status-label {
            font-size: 9px;
        }

        .chart-card .chart-container {
            height: 180px;
        }

        .chart-card canvas {
            max-height: 180px;
        }

        .table-container {
            min-width: 720px;
        }

        .table-container thead th {
            padding: 8px 9px;
            font-size: 9px;
        }

        .table-container tbody td {
            padding: 9px 9px;
            font-size: 11.5px;
        }
    }
</style>

<div class="reports-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-chart-line"></i> Queue Reports
            </h1>
            <p><i class="fas fa-arrow-trend-up"></i> Monitor and analyze queue performance metrics</p>
        </div>
        <button onclick="exportReport()" class="btn-export">
            <i class="fas fa-download"></i> Export Report
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $totalPatients ?? 0 }}</div>
            <div class="stat-label">Total Patients</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $completedToday ?? 0 }}</div>
            <div class="stat-label">Completed Today</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ round($avgWaitingTime ?? 0) }} min</div>
            <div class="stat-label">Avg Waiting Time</div>
        </div>
        <div class="stat-card cyan">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-number">{{ round($avgServiceTime ?? 0) }} min</div>
            <div class="stat-label">Avg Service Time</div>
        </div>
    </div>

    <!-- Status Summary -->
    <div class="status-summary-grid">
        <div class="status-item waiting">
            <span class="status-icon">⏳</span>
            <div class="status-number">{{ $statusStats['waiting'] ?? 0 }}</div>
            <div class="status-label">Waiting</div>
        </div>
        <div class="status-item in-progress">
            <span class="status-icon">🔄</span>
            <div class="status-number">{{ $statusStats['in_progress'] ?? 0 }}</div>
            <div class="status-label">In Progress</div>
        </div>
        <div class="status-item completed">
            <span class="status-icon">✅</span>
            <div class="status-number">{{ $statusStats['completed'] ?? 0 }}</div>
            <div class="status-label">Completed</div>
        </div>
        <div class="status-item cancelled">
            <span class="status-icon">❌</span>
            <div class="status-number">{{ $statusStats['cancelled'] ?? 0 }}</div>
            <div class="status-label">Cancelled</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="fas fa-chart-pie"></i> Department Distribution
            </h4>
            <div class="chart-container">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
        
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="fas fa-chart-line"></i> Last 7 Days Trend
            </h4>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.queue-reports.index') }}" id="filterForm">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="from_date"><i class="fas fa-calendar"></i> From Date</label>
                    <input type="date" name="from_date" class="filter-control" 
                           value="{{ request('from_date') }}" id="from_date">
                </div>
                <div class="filter-group">
                    <label for="to_date"><i class="fas fa-calendar"></i> To Date</label>
                    <input type="date" name="to_date" class="filter-control" 
                           value="{{ request('to_date') }}" id="to_date">
                </div>
                <div class="filter-group">
                    <label for="department"><i class="fas fa-building"></i> Department</label>
                    <select name="department" class="filter-control" id="department">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status"><i class="fas fa-tag"></i> Status</label>
                    <select name="status" class="filter-control" id="status">
                        <option value="">All Status</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>⏳ Waiting</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.queue-reports.index') }}" class="btn-reset">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
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
                        <th>Token #</th>
                        <th>Patient Name</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Waiting Time</th>
                        <th>Service Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports ?? [] as $report)
                    <tr>
                        <td><span class="token-badge">#{{ $report->token_number ?? $report->id }}</span></td>
                        <td>{{ $report->patient_name ?? 'N/A' }}</td>
                        <td>{{ $report->doctor_name ?? 'N/A' }}</td>
                        <td>{{ $report->department ?? 'General' }}</td>
                        <td>
                            <span class="status-badge {{ str_replace('_', '-', $report->status ?? 'waiting') }}">
                                @if(($report->status ?? 'waiting') == 'waiting') ⏳ Waiting
                                @elseif(($report->status ?? 'waiting') == 'in_progress') 🔄 In Progress
                                @elseif(($report->status ?? 'waiting') == 'completed') ✅ Completed
                                @else ❌ Cancelled @endif
                            </span>
                        </td>
                        <td>{{ $report->waiting_time ?? 0 }} min</td>
                        <td>{{ $report->service_time ?? 0 }} min</td>
                        <td>{{ isset($report->date) ? \Carbon\Carbon::parse($report->date)->format('d M Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-chart-line"></i>
                                <h3>No Reports Found</h3>
                                <p>No queue data available for the selected filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $reports->links() ?? '' }}
    </div>
</div>

<script>
// ============================================
// EXPORT REPORT
// ============================================
function exportReport() {
    let form = document.getElementById('filterForm');
    let action = '{{ route("admin.queue-reports.export") }}';
    let params = new URLSearchParams(new FormData(form)).toString();
    window.location.href = action + '?' + params;
}

// ============================================
// CHARTS - Responsive Setup
// ============================================
const isMobile = window.innerWidth <= 768;

// ============================================
// DEPARTMENT DISTRIBUTION CHART
// ============================================
const deptCtx = document.getElementById('departmentChart');
const deptData = @json($departmentStats ?? []);

if (deptData.length > 0 && deptCtx) {
    new Chart(deptCtx, {
        type: 'pie',
        data: {
            labels: deptData.map(item => item.department),
            datasets: [{
                data: deptData.map(item => item.total),
                backgroundColor: ['#6366f1', '#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#0ea5e9', '#8b5cf6', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: isMobile ? 'bottom' : 'bottom',
                    labels: {
                        padding: isMobile ? 10 : 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: isMobile ? 10 : 12
                        },
                        boxWidth: isMobile ? 8 : 12
                    }
                }
            }
        }
    });
} else if (deptCtx) {
    deptCtx.parentElement.innerHTML = '<p style="text-align:center;color:#94a3b8;padding:40px 0;">No department data available</p>';
}

// ============================================
// DAILY TREND CHART
// ============================================
const trendCtx = document.getElementById('trendChart');
const trendData = @json($dailyStats ?? []);

if (trendData.length > 0 && trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.date),
            datasets: [{
                label: 'Patients',
                data: trendData.map(item => item.total),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointRadius: isMobile ? 3 : 5,
                pointHoverRadius: isMobile ? 5 : 7,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: isMobile ? 11 : 12
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: { size: isMobile ? 9 : 11 }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        font: { size: isMobile ? 9 : 11 }
                    }
                }
            }
        }
    });
} else if (trendCtx) {
    trendCtx.parentElement.innerHTML = '<p style="text-align:center;color:#94a3b8;padding:40px 0;">No trend data available</p>';
}
</script>
@endsection