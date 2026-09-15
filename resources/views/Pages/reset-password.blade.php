@extends('layout.app')

@section('title', 'Reset Password - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <div class="login-page-wrapper">
        <div class="login-card reset-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="login-logo-massive mb-3">
                <h2 class="text-white fw-bold tracking-tight">🔑 Set New Password</h2>
                <p class="text-white-50">Create a strong password for your account</p>
            </div>

            {{-- ❌ Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ❌ Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul class="mb-0 mt-1" style="padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                @csrf
                
                {{-- Hidden token --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Field --}}
                <div class="mb-3">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control custom-input @error('email') is-invalid @enderror" 
                        placeholder="Enter your email" 
                        value="{{ old('email', request()->email) }}" 
                        required
                    >
                    @error('email')
                        <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-3">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">New Password</label>
                    <div class="input-group-custom">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control custom-input @error('password') is-invalid @enderror" 
                            placeholder="Enter new password (min 8 characters)" 
                            required
                            minlength="8"
                        >
                        <button type="button" class="toggle-password-btn" id="togglePassword" aria-label="Show password">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    
                    {{-- Password Strength Indicator --}}
                    <div class="password-strength-wrapper">
                        <div class="strength-bar">
                            <div class="fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Enter a password</span>
                    </div>

                    {{-- Password Requirements --}}
                    <div class="password-requirements">
                        <span class="requirement" id="reqLength">❌ Min 8 characters</span>
                        <span class="requirement" id="reqNumber">❌ Contains number</span>
                        <span class="requirement" id="reqUpper">❌ Uppercase letter</span>
                        <span class="requirement" id="reqSpecial">❌ Special character</span>
                    </div>

                    @error('password')
                        <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Confirm Password</label>
                    <div class="input-group-custom">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control custom-input" 
                            placeholder="Confirm your new password" 
                            required
                        >
                        <button type="button" class="toggle-password-btn" id="toggleConfirmPassword" aria-label="Show password">
                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                    <div class="password-match-indicator" id="matchIndicator"></div>
                </div>

                <button type="submit" class="btn btn-login-submit w-100 mt-2" id="submitBtn">
                    <i class="fas fa-sync-alt me-2"></i>Update Password
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <i class="fas fa-arrow-left me-1"></i>
                        <a href="{{ route('login') }}" class="text-accent-cyan text-decoration-none fw-bold">Back to Login</a>
                    </p>
                </div>
            </form>

            {{-- Security Notice --}}
            <div class="mt-4 text-center">
                <p class="text-white-50 small security-note">
                    <i class="fas fa-shield-alt"></i> 
                    Your password is securely encrypted. We never store it in plain text.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/reset-password.js') }}"></script>
@endsection