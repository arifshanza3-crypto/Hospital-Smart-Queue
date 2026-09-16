@extends('Layout.admin-layout')

@section('page-title', 'User Management')
@section('breadcrumb', 'Manage Users')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       USER MANAGEMENT - LIGHT THEME
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

    .user-management-wrapper {
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

    .page-header-left { display: flex; flex-direction: column; min-width: 0; }

    .page-header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-left h1 i { color: var(--accent-1); font-size: 28px; }

    .page-header-left p { color: var(--text-secondary); font-size: 14px; margin: 4px 0 0 0; }
    .page-header-left p i { color: var(--accent-1); margin-right: 4px; }

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
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 18px 16px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
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

    .stat-card .stat-icon { font-size: 28px; margin-bottom: 8px; }
    .stat-card .stat-number { font-size: 26px; font-weight: 800; color: var(--text-primary); line-height: 1.2; }
    .stat-card .stat-label { color: var(--text-secondary); font-size: 12px; font-weight: 500; margin-top: 4px; }

    .stat-card.purple::before { background: var(--accent-gradient); }
    .stat-card.purple .stat-icon { color: #6366f1; }

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.red::before { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-card.red .stat-icon { color: #ef4444; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.orange::before { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .stat-card.orange .stat-icon { color: #f59e0b; }

    /* ===== SEARCH ===== */
    .search-box { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
    .search-wrapper { position: relative; flex: 1; min-width: 250px; }
    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .search-input {
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

    .search-input:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .search-input::placeholder { color: var(--text-muted); }

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

    .alert-modern.success { border-color: var(--success); color: #065f46; background: #f0fdf4; }
    .alert-modern.success i { color: var(--success); }
    .alert-modern.error { border-color: var(--danger); color: #991b1b; background: #fef2f2; }
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

    .table-scroll-hint i { color: var(--accent-1); margin-right: 6px; }

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
    .table-container { width: 100%; min-width: 1000px; }
    .table-container table { width: 100%; border-collapse: collapse; }
    .table-container thead { background: #f8fafc; border-bottom: 1px solid var(--border-color); }

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
    }

    .table-container tbody tr { transition: all 0.3s ease; }
    .table-container tbody tr:hover { background: #f8fafc; }
    .table-container tbody tr:last-child td { border-bottom: none; }

    .user-id { color: var(--text-secondary); font-weight: 600; font-size: 13px; }
    .email-cell { color: var(--text-secondary); word-break: break-word; font-size: 13px; }

    /* User Info Cell */
    .user-cell { display: flex; align-items: center; gap: 12px; }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .user-name { font-weight: 600; color: var(--text-primary); white-space: nowrap; }

    /* Role Badges */
    .role-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .role-badge.admin { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .role-badge.staff { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .role-badge.user { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }

    /* Status Badges */
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

    .status-badge.active { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    .status-badge .status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .status-badge.active .status-dot { background: #10b981; }
    .status-badge.inactive .status-dot { background: #ef4444; }

    /* ===== ACTION BUTTONS ===== */
    .action-group {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        font-size: 13px;
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

    .action-btn:hover { transform: translateY(-2px) scale(1.05); }

    .action-btn.view {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .action-btn.view:hover {
        background: #065f46;
        color: white;
        box-shadow: 0 4px 16px rgba(6, 95, 70, 0.3);
    }

    .action-btn.edit { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .action-btn.edit:hover { background: #1e40af; color: white; box-shadow: 0 4px 16px rgba(30, 64, 175, 0.3); }

    .action-btn.delete { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .action-btn.delete:hover { background: #991b1b; color: white; box-shadow: 0 4px 16px rgba(153, 27, 27, 0.3); }

    .action-btn.toggle { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .action-btn.toggle:hover { background: #d97706; color: white; box-shadow: 0 4px 16px rgba(217, 119, 6, 0.3); }

    /* Tooltip */
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

    .action-btn:hover .tooltip-text { visibility: visible; opacity: 1; }

    /* ===== EMPTY STATE ===== */
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 56px; color: var(--text-muted); display: block; margin-bottom: 16px; }
    .empty-state h3 { color: var(--text-primary); font-weight: 600; margin-bottom: 8px; }
    .empty-state p { color: var(--text-secondary); margin-bottom: 20px; }
    .empty-state .btn-primary-gradient { display: inline-flex; }

    /* ============================================ */
    /* ✅ VIEW USER MODAL                          */
    /* ============================================ */
    .user-modal-overlay {
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

    .user-modal-overlay.active {
        display: flex;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .user-modal {
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

    .user-modal-header {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        padding: 24px;
        color: white;
        position: relative;
        text-align: center;
    }

    .user-modal-header .modal-close {
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

    .user-modal-header .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .user-modal-avatar {
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
        font-size: 28px;
        font-weight: 700;
        color: white;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .user-modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }

    .user-modal-header p {
        margin: 4px 0 0 0;
        font-size: 13px;
        opacity: 0.9;
    }

    .user-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .user-detail-row {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .user-detail-row:last-child {
        border-bottom: none;
    }

    .user-detail-icon {
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

    .user-detail-content {
        flex: 1;
        min-width: 0;
    }

    .user-detail-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .user-detail-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 600;
        word-break: break-word;
    }

    .user-detail-value.password-mask {
        font-family: monospace;
        letter-spacing: 2px;
        color: #94a3b8;
    }

    .user-modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .user-modal-footer .btn-close-modal {
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

    .user-modal-footer .btn-close-modal:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    /* ============================================ */
    /* ✅ RESPONSIVE                                */
    /* ============================================ */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .stat-card .stat-number { font-size: 24px; }
    }

    @media (max-width: 992px) {
        .user-management-wrapper { padding: 20px 18px; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .page-header-left h1 { font-size: 22px; }
        .btn-primary-gradient { width: 100%; justify-content: center; }
    }

    @media (max-width: 768px) {
        .user-management-wrapper { padding: 16px 12px; }
        .page-header { gap: 12px; margin-bottom: 20px; }
        .page-header-left h1 { font-size: 20px; gap: 8px; }
        .page-header-left h1 i { font-size: 20px; }
        .page-header-left p { font-size: 13px; }
        .btn-primary-gradient { padding: 12px 20px; font-size: 13px; }

        .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 18px; }
        .stat-card { padding: 12px 8px; border-radius: 12px; }
        .stat-card .stat-icon { font-size: 18px; margin-bottom: 4px; }
        .stat-card .stat-number { font-size: 18px; }
        .stat-card .stat-label { font-size: 10px; margin-top: 2px; }

        .search-box { gap: 10px; margin-bottom: 15px; }
        .search-wrapper { min-width: 100%; }
        .search-input { padding: 11px 14px 11px 40px; font-size: 13px; border-radius: 10px; }
        .search-wrapper i { left: 13px; font-size: 13px; }

        .table-scroll-hint { display: block; }
        .table-wrapper { border-radius: 12px; }
        .table-container { min-width: 900px; }

        .table-container thead th { padding: 10px 12px; font-size: 10px; }
        .table-container tbody td { padding: 12px 12px; font-size: 13px; }

        .user-avatar { width: 32px; height: 32px; font-size: 11px; }
        .user-name { font-size: 13px; }
        .role-badge, .status-badge { font-size: 11px; padding: 4px 10px; }
        .action-btn { width: 32px; height: 32px; font-size: 12px; }
        .action-group { gap: 4px; }

        .alert-modern { padding: 12px 16px; font-size: 13px; border-radius: 10px; margin-bottom: 15px; }
        .empty-state { padding: 40px 16px; }
        .empty-state i { font-size: 42px; }
        .empty-state h3 { font-size: 17px; }
        .empty-state p { font-size: 13px; }

        /* Modal Mobile */
        .user-modal-overlay { padding: 12px; }
        .user-modal { border-radius: 16px; }
        .user-modal-header { padding: 20px 16px; }
        .user-modal-avatar { width: 70px; height: 70px; font-size: 24px; }
        .user-modal-header h3 { font-size: 17px; }
        .user-modal-header p { font-size: 12px; }
        .user-modal-body { padding: 18px; }
        .user-detail-row { padding: 12px 0; }
        .user-detail-icon { width: 36px; height: 36px; font-size: 14px; margin-right: 12px; }
        .user-detail-value { font-size: 13px; }
        .user-modal-footer { padding: 14px 18px; }
        .user-modal-footer .btn-close-modal { padding: 10px 20px; font-size: 13px; }
    }

    @media (max-width: 576px) {
        .user-management-wrapper { padding: 12px 10px; }
        .page-header-left h1 { font-size: 18px; }
        .page-header-left h1 i { font-size: 16px; }
        .page-header-left p { font-size: 12px; }
        .btn-primary-gradient { padding: 11px 16px; font-size: 12px; border-radius: 10px; }

        .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 6px; }
        .stat-card { padding: 10px 6px; }
        .stat-card .stat-icon { font-size: 16px; margin-bottom: 2px; }
        .stat-card .stat-number { font-size: 16px; }
        .stat-card .stat-label { font-size: 9px; }

        .search-input { padding: 10px 12px 10px 36px; font-size: 12.5px; }
        .search-wrapper i { left: 12px; font-size: 12px; }

        .table-container { min-width: 850px; }
        .table-container thead th { padding: 9px 10px; font-size: 9.5px; }
        .table-container tbody td { padding: 10px 10px; font-size: 12px; }

        .user-avatar { width: 28px; height: 28px; font-size: 10px; }
        .user-name { font-size: 12px; }
        .email-cell { font-size: 11px; }
        .role-badge, .status-badge { font-size: 10px; padding: 3px 8px; }
        .status-dot { width: 5px; height: 5px; margin-right: 4px; }
        .action-btn { width: 30px; height: 30px; font-size: 11px; border-radius: 7px; }
        .action-group { gap: 3px; }
        .table-scroll-hint { font-size: 11px; padding: 6px; }

        /* Modal Mobile Small */
        .user-modal-header { padding: 18px 14px; }
        .user-modal-avatar { width: 60px; height: 60px; font-size: 20px; }
        .user-modal-header h3 { font-size: 16px; }
        .user-modal-body { padding: 16px; }
        .user-detail-icon { width: 32px; height: 32px; font-size: 13px; margin-right: 10px; }
        .user-detail-label { font-size: 10px; }
        .user-detail-value { font-size: 12.5px; }
    }

    @media (max-width: 380px) {
        .user-management-wrapper { padding: 10px 8px; }
        .page-header-left h1 { font-size: 16px; }
        .stat-card { padding: 8px 4px; }
        .stat-card .stat-icon { font-size: 14px; }
        .stat-card .stat-number { font-size: 14px; }
        .stat-card .stat-label { font-size: 8px; }
        .search-input { padding: 9px 10px 9px 34px; font-size: 12px; }
        .table-container { min-width: 800px; }
        .table-container thead th { padding: 8px 8px; font-size: 9px; }
        .table-container tbody td { padding: 9px 8px; font-size: 11.5px; }
        .action-btn { width: 28px; height: 28px; font-size: 10px; }
    }
</style>

<div class="user-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-users"></i> User Management
            </h1>
            <p><i class="fas fa-arrow-trend-up"></i> Manage system users and their roles</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-modern success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $total ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active ?? 0 }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number">{{ $inactive ?? 0 }}</div>
            <div class="stat-label">Inactive</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <div class="stat-number">{{ $admins ?? 0 }}</div>
            <div class="stat-label">Admins</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-number">{{ $staff ?? 0 }}</div>
            <div class="stat-label">Staff</div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" class="search-input" placeholder="Search by name, email or phone...">
        </div>
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
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td>
                            <span class="user-id">#{{ $user->id }}</span>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="email-cell">{{ $user->email }}</td>
                        <td class="email-cell">{{ $user->phone ?? '-' }}</td>
                        <td>
                            <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $user->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                {{-- ✅ NEW: View Button --}}
                                <button type="button"
                                        class="action-btn view"
                                        title="View User"
                                        onclick="viewUser(
                                            '{{ addslashes($user->name) }}',
                                            '{{ addslashes($user->email) }}',
                                            '{{ addslashes($user->phone ?? 'N/A') }}',
                                            '{{ addslashes($user->role) }}',
                                            '{{ addslashes($user->status) }}',
                                            '{{ $user->id }}'
                                        )">
                                    <i class="fas fa-eye"></i>
                                    <span class="tooltip-text">View</span>
                                </button>

                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn edit" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                    <span class="tooltip-text">Edit</span>
                                </a>
                                <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')" class="action-btn toggle" title="Toggle Status">
                                    <i class="fas {{ $user->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                    <span class="tooltip-text">{{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                                </button>
                                <button onclick="deleteUser({{ $user->id }})" class="action-btn delete" title="Delete User">
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
                                <i class="fas fa-users"></i>
                                <h3>No Users Found</h3>
                                <p>Get started by creating your first user account.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-primary-gradient">
                                    <i class="fas fa-plus"></i> Add New User
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
{{-- ✅ VIEW USER MODAL                          --}}
{{-- ============================================ --}}
<div class="user-modal-overlay" id="userModal">
    <div class="user-modal">
        {{-- Header --}}
        <div class="user-modal-header">
            <button type="button" class="modal-close" onclick="closeUserModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="user-modal-avatar" id="modalAvatar">U</div>
            <h3 id="modalName">User Name</h3>
            <p id="modalRole">Role</p>
        </div>

        {{-- Body --}}
        <div class="user-modal-body">
            {{-- User ID --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">User ID</div>
                    <div class="user-detail-value" id="modalUserId">#0</div>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Full Name</div>
                    <div class="user-detail-value" id="modalFullName">-</div>
                </div>
            </div>

            {{-- Email --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Email Address</div>
                    <div class="user-detail-value" id="modalEmail">-</div>
                </div>
            </div>

            {{-- Phone --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Phone Number</div>
                    <div class="user-detail-value" id="modalPhone">-</div>
                </div>
            </div>

            {{-- Password --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Password</div>
                    <div class="user-detail-value password-mask" id="modalPassword">••••••••</div>
                </div>
            </div>

            {{-- Role --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Role</div>
                    <div class="user-detail-value" id="modalRoleValue">-</div>
                </div>
            </div>

            {{-- Status --}}
            <div class="user-detail-row">
                <div class="user-detail-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="user-detail-content">
                    <div class="user-detail-label">Status</div>
                    <div class="user-detail-value" id="modalStatus">-</div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="user-modal-footer">
            <button type="button" class="btn-close-modal" onclick="closeUserModal()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
    // ============================================
    // ✅ VIEW USER MODAL
    // ============================================
    function viewUser(name, email, phone, role, status, userId) {
        // Set modal data
        document.getElementById('modalAvatar').textContent = name.substring(0, 2).toUpperCase();
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalRole').textContent = role.charAt(0).toUpperCase() + role.slice(1);
        document.getElementById('modalUserId').textContent = '#' + userId;
        document.getElementById('modalFullName').textContent = name;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalPhone').textContent = phone || 'N/A';
        document.getElementById('modalPassword').textContent = '••••••••'; // Hashed - cannot show real
        document.getElementById('modalRoleValue').textContent = role.charAt(0).toUpperCase() + role.slice(1);
        document.getElementById('modalStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);

        // Show modal
        document.getElementById('userModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close modal on overlay click
    document.getElementById('userModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUserModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUserModal();
        }
    });

    // ============================================
    // DELETE USER
    // ============================================
    function deleteUser(id) {
        if (confirm('⚠️ Are you sure you want to delete this user?\n\nThis action cannot be undone!')) {
            fetch('/admin/users/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('✅ User deleted successfully!', 'success');
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert('❌ Error deleting user');
                }
            })
            .catch(() => alert('❌ Network error. Please try again.'));
        }
    }

    // ============================================
    // TOGGLE STATUS
    // ============================================
    function toggleStatus(id, currentStatus) {
        let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        let action = newStatus === 'active' ? 'activate' : 'deactivate';

        if (confirm(`Are you sure you want to ${action} this user?`)) {
            fetch('/admin/users/' + id + '/status/' + newStatus, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`✅ User ${action}d successfully!`, 'success');
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert('❌ Error updating status');
                }
            })
            .catch(() => alert('❌ Network error. Please try again.'));
        }
    }

    // ============================================
    // SEARCH
    // ============================================
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let v = this.value.toLowerCase();
            document.querySelectorAll('#tableBody tr').forEach(row => {
                if (row.querySelector('td')) {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(v) ? '' : 'none';
                }
            });
        });
    }

    // ============================================
    // NOTIFICATION - Mobile friendly
    // ============================================
    function showNotification(message, type) {
        const div = document.createElement('div');
        const isMobile = window.innerWidth <= 576;
        const styles = isMobile
            ? `left: 12px; right: 12px; top: 70px;`
            : `right: 24px; top: 80px; min-width: 280px; max-width: 400px;`;

        div.style.cssText = `
            position: fixed;
            ${styles}
            padding: 14px 20px;
            background: #1e293b;
            color: white;
            border-radius: 12px;
            z-index: 9999;
            font-family: inherit;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            animation: umSlideIn 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            border-left: 4px solid #10b981;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 10px;
        `;
        div.textContent = message;
        document.body.appendChild(div);
        setTimeout(() => {
            div.style.animation = 'umSlideOut 0.3s ease';
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }

    // ============================================
    // ANIMATION STYLES
    // ============================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes umSlideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes umSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection