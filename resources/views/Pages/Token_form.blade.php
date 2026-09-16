@extends('layout.app')

@section('title', 'Generate Token - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Token_form.css') }}">

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <section class="token-main-wrapper">
        <div class="token-card">
            <div class="token-header">
                <img src="{{ asset('Assert/logo.png') }}" alt="Smart Queue Logo" class="token-logo">
                <h2 class="token-title">Token Form</h2>
                <p class="token-subtitle">Enter details to join the queue</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('token.generate') }}" autocomplete="off" id="tokenRequestForm">
                @csrf

                {{-- Full Name --}}
                <div class="input-container">
                    <label class="input-label">Full Name</label>
                    <input type="text" 
                           name="patient_name" 
                           class="form-control token-input" 
                           placeholder="Enter your full name" 
                           required 
                           value="{{ old('patient_name') }}">
                </div>

                {{-- Email Field (Optional) --}}
                <div class="input-container">
                    <label class="input-label">Email <span class="optional-tag">(Optional)</span></label>
                    <input type="email" 
                           name="email" 
                           class="form-control token-input" 
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                </div>

                {{-- Mobile Number Field --}}
                <div class="input-container">
                    <label class="input-label">Mobile Number</label>
                    <input type="tel" 
                           name="phone" 
                           id="mobileNumber" 
                           class="form-control token-input" 
                           placeholder="03XX-XXXXXXX" 
                           required 
                           maxlength="11"
                           value="{{ old('phone') }}">
                    <div id="mobileError" class="validation-error d-none">
                        Please enter a valid 11-digit number starting with 03
                    </div>
                </div>

                <button type="submit" class="btn-token-generate">Generate Token</button>
            </form>
        </div>
    </section>
</div>

<script src="{{ asset('js/Token_form.js') }}"></script>
@endsection