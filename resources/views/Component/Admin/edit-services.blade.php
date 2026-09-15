@extends('Layout.admin-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================ */
    /* GLOBAL                                       */
    /* ============================================ */
    * {
        box-sizing: border-box;
    }

    .form-container {
        font-family: 'Poppins', sans-serif;
        padding: 0 10px;
    }

    /* ============================================ */
    /* FORM CARD                                    */
    /* ============================================ */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 20px auto;
        overflow: hidden;
    }

    /* ============================================ */
    /* FORM HEADER                                  */
    /* ============================================ */
    .form-header {
        background: linear-gradient(135deg, #0b2e33 0%, #1a4a50 100%);
        padding: 30px;
        text-align: center;
    }

    .form-header h2 {
        color: #00d4ff;
        margin: 10px 0 0;
        font-size: 28px;
        font-weight: 600;
        line-height: 1.2;
    }

    .form-header i {
        font-size: 60px;
        color: #00d4ff;
    }

    .form-header p {
        color: #a0d4d9;
        margin: 8px 0 0;
        font-size: 14px;
    }

    /* ============================================ */
    /* FORM BODY                                    */
    /* ============================================ */
    .form-body {
        padding: 40px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #0b2e33;
        font-size: 14px;
    }

    .form-group label i {
        margin-right: 8px;
        color: #00d4ff;
    }

    .form-group small {
        color: #666;
        font-size: 12px;
        display: block;
        margin-top: 4px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .required {
        color: #dc3545;
        margin-left: 4px;
    }

    /* ============================================ */
    /* BUTTONS                                      */
    /* ============================================ */
    .btn-submit {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 212, 255, 0.3);
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        margin-top: 15px;
        width: 100%;
        transition: all 0.3s ease;
        font-family: inherit;
        font-size: 14px;
        box-sizing: border-box;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }

    /* ============================================ */
    /* ERROR MESSAGE                                */
    /* ============================================ */
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        font-size: 13px;
        line-height: 1.6;
    }

    .error-message small {
        display: block;
        margin: 3px 0;
    }

    /* ============================================ */
    /* CURRENT IMAGE                                */
    /* ============================================ */
    .current-image {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .current-image small {
        color: #666;
        font-size: 12px;
    }

    .current-image img {
        max-width: 100px;
        border-radius: 8px;
        margin-top: 6px;
        display: block;
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
    /* ✅ RESPONSIVE - TABLET (max 768px)           */
    /* ============================================ */
    @media (max-width: 768px) {
        .form-container {
            padding: 0 6px;
        }

        .form-card {
            border-radius: 16px;
            margin: 15px auto;
        }

        .form-header {
            padding: 25px 20px;
        }

        .form-header i {
            font-size: 45px;
        }

        .form-header h2 {
            font-size: 22px;
            margin-top: 8px;
        }

        .form-header p {
            font-size: 13px;
        }

        .form-body {
            padding: 25px 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .form-control {
            padding: 11px 14px;
            font-size: 13.5px;
            border-radius: 10px;
        }

        /* Row stack */
        .row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .btn-submit {
            padding: 13px 24px;
            font-size: 14px;
            border-radius: 10px;
        }

        .btn-cancel {
            padding: 11px 20px;
            font-size: 13.5px;
            border-radius: 10px;
            margin-top: 12px;
        }

        .error-message {
            padding: 11px 14px;
            font-size: 12.5px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - SMALL MOBILE (max 576px)     */
    /* ============================================ */
    @media (max-width: 576px) {
        .form-container {
            padding: 0 4px;
        }

        .form-card {
            border-radius: 14px;
            margin: 10px auto;
        }

        .form-header {
            padding: 20px 16px;
        }

        .form-header i {
            font-size: 38px;
        }

        .form-header h2 {
            font-size: 19px;
        }

        .form-header p {
            font-size: 12px;
        }

        .form-body {
            padding: 20px 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 12.5px;
        }

        .form-group label i {
            margin-right: 6px;
        }

        .form-group small {
            font-size: 11px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
            border-radius: 9px;
        }

        textarea.form-control {
            min-height: 90px;
        }

        .btn-submit {
            padding: 12px 20px;
            font-size: 13.5px;
            border-radius: 9px;
        }

        .btn-cancel {
            padding: 10px 16px;
            font-size: 13px;
            border-radius: 9px;
            margin-top: 10px;
        }

        .error-message {
            padding: 10px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .current-image {
            padding: 8px;
        }

        .current-image img {
            max-width: 80px;
        }
    }

    /* ============================================ */
    /* ✅ RESPONSIVE - EXTRA SMALL (max 380px)     */
    /* ============================================ */
    @media (max-width: 380px) {
        .form-card {
            border-radius: 12px;
        }

        .form-header {
            padding: 18px 12px;
        }

        .form-header i {
            font-size: 32px;
        }

        .form-header h2 {
            font-size: 17px;
        }

        .form-header p {
            font-size: 11px;
        }

        .form-body {
            padding: 16px 12px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            font-size: 12px;
        }

        .form-control {
            padding: 9px 11px;
            font-size: 12.5px;
            border-radius: 8px;
        }

        textarea.form-control {
            min-height: 80px;
        }

        .btn-submit {
            padding: 11px 16px;
            font-size: 12.5px;
            border-radius: 8px;
        }

        .btn-cancel {
            padding: 9px 14px;
            font-size: 12px;
            border-radius: 8px;
        }

        .error-message {
            padding: 9px 11px;
            font-size: 11.5px;
        }

        .current-image img {
            max-width: 70px;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-concierge-bell"></i>
            <h2>Edit Service</h2>
            <p>Update the service information</p>
        </div>
        
        <div class="form-body">
            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.services.update', $service->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Service Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Cardiology Consultation" value="{{ old('name', $service->name) }}" required>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> Price</label>
                        <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" value="{{ old('price', $service->price) }}">
                        <small>Leave empty for free services or quote</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-hourglass-half"></i> Duration (minutes)</label>
                        <input type="number" name="duration" class="form-control" placeholder="30" value="{{ old('duration', $service->duration) }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" name="department" class="form-control" placeholder="e.g., Cardiology" value="{{ old('department', $service->department) }}">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-chart-line"></i> Display Order</label>
                        <input type="number" name="display_order" class="form-control" placeholder="0" value="{{ old('display_order', $service->display_order) }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description <span class="required">*</span></label>
                    <textarea name="description" class="form-control" placeholder="Describe the service in detail..." required>{{ old('description', $service->description) }}</textarea>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-icons"></i> Icon Class</label>
                        <input type="text" name="icon" class="form-control" placeholder="fas fa-heartbeat" value="{{ old('icon', $service->icon) }}">
                        <small>Font Awesome icon class (e.g., fas fa-heartbeat)</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Service Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($service->image)
                            <div class="current-image">
                                <small>Current Image:</small>
                                <img src="{{ Storage::url($service->image) }}" alt="Service Image">
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Service
                </button>
                
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection