@extends('Layout.admin-layout')

@section('page-title', 'Edit User')
@section('breadcrumb', 'Edit User')

@section('content')
<style>
    /* ============================================ */
    /* GLOBAL                                       */
    /* ============================================ */
    * {
        box-sizing: border-box;
    }

    /* ============================================ */
    /* FORM CONTAINER                               */
    /* ============================================ */
    .form-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 10px;
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    }

    .form-card h2 {
        color: #0b2e33;
        margin: 0 0 5px 0;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card h2 i {
        color: #00d4ff;
    }

    .form-card .subtitle {
        color: #666;
        font-size: 14px;
        margin: 0 0 20px 0;
    }

    /* ============================================ */
    /* FORM GROUPS                                  */
    /* ============================================ */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #0b2e33;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-group label i {
        color: #00d4ff;
        margin-right: 8px;
    }

    .form-group label .required {
        color: #dc3545;
        margin-left: 4px;
    }

    /* ============================================ */
    /* INPUTS                                       */
    /* ============================================ */
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        cursor: pointer;
        padding-right: 40px;
    }

    /* ============================================ */
    /* ROW (2 columns)                              */
    /* ============================================ */
    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* ============================================ */
    /* BUTTON ROW                                   */
    /* ============================================ */
    .btn-row {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-submit {
        flex: 1;
        padding: 12px 30px;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
    }

    .btn-cancel {
        padding: 12px 30px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 0.5;
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    /* ============================================ */
    /* ERROR BOX                                    */
    /* ============================================ */
    .error-box {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        font-size: 14px;
        line-height: 1.6;
    }

    .error-box strong {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .error-box small {
        display: block;
        margin: 3px 0;
        font-size: 13px;
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - TABLET (max 768px)           */
    /* ============================================ */
    @media (max-width: 768px) {
        .form-container {
            padding: 0 6px;
        }

        .form-card {
            padding: 24px 20px;
            border-radius: 14px;
        }

        .form-card h2 {
            font-size: 1.25rem;
            gap: 8px;
        }

        .form-card .subtitle {
            font-size: 13px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 13px;
        }

        .form-control {
            padding: 11px 14px;
            font-size: 13.5px;
            border-radius: 9px;
        }

        select.form-control {
            padding-right: 36px;
            background-position: right 12px center;
        }

        /* Row to single column */
        .row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .btn-row {
            flex-direction: column-reverse;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-cancel,
        .btn-submit {
            flex: 1;
            width: 100%;
            padding: 12px 20px;
            font-size: 14px;
            border-radius: 9px;
        }

        .error-box {
            padding: 12px 14px;
            font-size: 13px;
            border-radius: 9px;
        }

        .error-box strong {
            font-size: 13px;
        }

        .error-box small {
            font-size: 12px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 576px)     */
    /* ============================================ */
    @media (max-width: 576px) {
        .form-card {
            padding: 20px 16px;
            border-radius: 12px;
        }

        .form-card h2 {
            font-size: 1.15rem;
        }

        .form-card .subtitle {
            font-size: 12.5px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            font-size: 12.5px;
            margin-bottom: 5px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
            border-radius: 8px;
        }

        .btn-cancel,
        .btn-submit {
            padding: 11px 16px;
            font-size: 13px;
        }

        .error-box {
            padding: 11px 12px;
            font-size: 12.5px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - EXTRA SMALL (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .form-container {
            padding: 0 4px;
        }

        .form-card {
            padding: 16px 12px;
            border-radius: 10px;
        }

        .form-card h2 {
            font-size: 1.05rem;
        }

        .form-card h2 i {
            font-size: 0.95rem;
        }

        .form-card .subtitle {
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            font-size: 12px;
        }

        .form-group label i {
            margin-right: 6px;
        }

        .form-control {
            padding: 9px 11px;
            font-size: 12.5px;
            border-radius: 7px;
        }

        .btn-cancel,
        .btn-submit {
            padding: 10px 14px;
            font-size: 12px;
            border-radius: 8px;
        }

        .error-box {
            padding: 10px 11px;
            font-size: 12px;
        }

        .error-box strong {
            font-size: 12px;
        }

        .error-box small {
            font-size: 11px;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2><i class="fas fa-user-edit"></i> Edit User</h2>
        <p class="subtitle">Update user information and permissions.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin-bottom: 25px;">

        @if($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
            @foreach($errors->all() as $error)
                <small>• {{ $error }}</small>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fas fa-user-tag"></i> Role <span class="required">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>🟢 Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                    </select>
                </div>
            </div>

            <div class="btn-row">
                <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection