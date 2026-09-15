<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SMART QUEUE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <i class="fas fa-hospital-user"></i>
        <h2>SMART QUEUE</h2>
        <p>Login - Efficiently managing your time</p>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $error)
                <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small><br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label><i class="fas fa-lock"></i> Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Show password">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <span class="btn-text"><i class="fas fa-sign-in-alt"></i> SIGN IN</span>
            <span class="spinner"></span>
        </button>
    </form>

    <div class="text-center mt-3">
        <p class="text-muted">Don't have an account? <a href="{{ route('signup') }}" class="text-accent">Sign Up Now</a></p>
        <p><a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a></p>
        <p class="role-hint"><i class="fas fa-info-circle"></i> Access based on your role (Admin/Staff/User)</p>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>

</body>
</html>