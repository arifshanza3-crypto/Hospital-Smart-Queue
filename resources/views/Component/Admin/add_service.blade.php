@extends('Layout.admin-layout')

@section('page-title', 'Add New Service')
@section('breadcrumb', 'Create Service')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       ADD SERVICE - LIGHT THEME
       ============================================ */
    
    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        --accent-1: #3b82f6;
        --accent-2: #6366f1;
        --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        --success: #10b981;
        --danger: #ef4444;
    }

    * { box-sizing: border-box; }

    .add-service-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
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

    .btn-secondary-gradient {
        background: #e2e8f0;
        color: var(--text-primary);
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
        white-space: nowrap;
    }

    .btn-secondary-gradient:hover {
        background: #cbd5e1;
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-primary-gradient {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.35);
        color: white;
        text-decoration: none;
    }

    /* ===== FORM CARD ===== */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
    }

    .form-card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-header h3 i {
        color: var(--accent-1);
    }

    .form-card-body {
        padding: 24px;
    }

    /* ===== FORM ELEMENTS ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        display: block;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-group label i {
        color: var(--accent-1);
        margin-right: 6px;
        width: 18px;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
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

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-control:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        cursor: pointer;
        padding-right: 40px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-text {
        display: block;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 6px;
    }

    .invalid-feedback {
        font-size: 12px;
        color: var(--danger);
        margin-top: 4px;
        display: block;
    }

    /* ===== ICON PREVIEW ===== */
    .icon-preview-box {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .icon-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--accent-gradient);
        color: white;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        flex-shrink: 0;
    }

    /* ===== TWO COLUMN ROW ===== */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* ===== ALERTS ===== */
    .alert-modern {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-left: 4px solid;
        background: var(--bg-card);
        box-shadow: var(--shadow);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        font-size: 14px;
        line-height: 1.5;
    }

    .alert-modern.success {
        border-color: var(--success);
        color: #065f46;
        background: #f0fdf4;
    }

    .alert-modern.success i { color: var(--success); margin-top: 2px; }

    .alert-modern.error {
        border-color: var(--danger);
        color: #991b1b;
        background: #fef2f2;
    }

    .alert-modern.error i { color: var(--danger); margin-top: 2px; }

    .alert-modern i { font-size: 18px; flex-shrink: 0; }

    .alert-modern ul {
        margin: 4px 0 0 20px;
        padding: 0;
    }

    .alert-modern ul li {
        list-style-type: disc;
        margin-bottom: 2px;
    }

    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
    }

    .form-actions .btn {
        flex: 1;
        justify-content: center;
    }

    .form-actions .btn-secondary-gradient {
        flex: 0.5;
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - TABLET (max 768px)           */
    /* ============================================ */
    @media (max-width: 768px) {
        .add-service-wrapper { padding: 16px 12px; }

        .page-header { gap: 12px; margin-bottom: 20px; }

        .page-header-left h1 { font-size: 20px; gap: 8px; }
        .page-header-left h1 i { font-size: 20px; }
        .page-header-left p { font-size: 13px; }

        .btn-secondary-gradient {
            width: 100%;
            justify-content: center;
            padding: 11px 20px;
            font-size: 13px;
        }

        .form-card { border-radius: 12px; }
        .form-card-header { padding: 16px 18px; }
        .form-card-header h3 { font-size: 16px; }
        .form-card-body { padding: 18px 16px; }

        .form-group { margin-bottom: 18px; }
        .form-group label { font-size: 13px; }

        .form-control {
            padding: 11px 14px;
            font-size: 13.5px;
            border-radius: 10px;
        }

        select.form-control {
            padding-right: 36px;
            background-position: right 12px center;
        }

        .form-row { grid-template-columns: 1fr; gap: 0; }

        .form-actions {
            flex-direction: column-reverse;
            gap: 10px;
            padding-top: 18px;
        }

        .form-actions .btn,
        .form-actions .btn-secondary-gradient {
            width: 100%;
            flex: 1;
            padding: 12px 20px;
            font-size: 13px;
        }

        .alert-modern {
            padding: 12px 16px;
            font-size: 13px;
            border-radius: 10px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 576px)     */
    /* ============================================ */
    @media (max-width: 576px) {
        .add-service-wrapper { padding: 12px 10px; }

        .page-header-left h1 { font-size: 18px; }
        .page-header-left h1 i { font-size: 16px; }
        .page-header-left p { font-size: 12px; }

        .form-card-header { padding: 14px 14px; }
        .form-card-header h3 { font-size: 15px; }
        .form-card-body { padding: 14px 12px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { font-size: 12.5px; }
        .form-group label i { width: 16px; margin-right: 4px; }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
            border-radius: 9px;
        }

        .form-actions .btn,
        .form-actions .btn-secondary-gradient {
            padding: 11px 16px;
            font-size: 12.5px;
            border-radius: 9px;
        }

        .icon-preview { width: 42px; height: 42px; font-size: 18px; }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - EXTRA SMALL (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .add-service-wrapper { padding: 10px 8px; }

        .page-header-left h1 { font-size: 16px; }
        .page-header-left h1 i { font-size: 14px; }

        .form-card { border-radius: 10px; }
        .form-card-header { padding: 12px 12px; }
        .form-card-header h3 { font-size: 14px; }
        .form-card-body { padding: 12px 10px; }

        .form-group { margin-bottom: 14px; }
        .form-group label { font-size: 12px; }

        .form-control {
            padding: 9px 11px;
            font-size: 12.5px;
            border-radius: 8px;
        }
    }
</style>

<div class="add-service-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-concierge-bell"></i> Add New Service
            </h1>
            <p><i class="fas fa-arrow-trend-up"></i> Register a new service in the system</p>
        </div>
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Back to Services
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

    @if($errors->any())
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3>
                <i class="fas fa-concierge-bell"></i> Service Information
            </h3>
        </div>

        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf

                <!-- Service Name -->
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-tag"></i> Service Name <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" placeholder="e.g., Cardiology Consultation" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">
                        <i class="fas fa-align-left"></i> Description <span class="required">*</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" 
                              placeholder="Describe the service in detail..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Icon -->
                <div class="form-group">
                    <label for="icon">
                        <i class="fas fa-icons"></i> Icon (FontAwesome Class)
                    </label>
                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                           id="icon" name="icon" placeholder="fas fa-stethoscope" 
                           value="{{ old('icon', 'fas fa-stethoscope') }}">
                    <small class="form-text">Examples: fas fa-user-md, fas fa-heartbeat, fas fa-pills, fas fa-tooth</small>
                    <div class="icon-preview-box">
                        <div class="icon-preview" id="iconPreview">
                            <i class="{{ old('icon', 'fas fa-stethoscope') }}"></i>
                        </div>
                        <span style="color: var(--text-muted); font-size: 12px;">Live Preview</span>
                    </div>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department & Status -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">
                            <i class="fas fa-building"></i> Department
                        </label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror" 
                               id="department" name="department" placeholder="e.g., Cardiology" 
                               value="{{ old('department') }}">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-toggle-on"></i> Status <span class="required">*</span>
                        </label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary-gradient">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fas fa-save"></i> Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ✅ Icon live preview
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');

    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            iconPreview.innerHTML = `<i class="${this.value}"></i>`;
        });
    }
</script>

@endsection