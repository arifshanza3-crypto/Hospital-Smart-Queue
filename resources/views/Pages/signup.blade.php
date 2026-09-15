@extends('Layout.app')

@section('title', 'Sign Up - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sign.css') }}">

<div class="signup-viewport">
    <div class="mesh-bg"></div>

    <div class="signup-page-wrapper">
        <div class="signup-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="signup-logo-massive mb-3">
                <h2 class="text-white fw-bold">Create Account</h2>
                <p class="text-white-50">Join Smart Queue Management System</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="alert-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('signup.post') }}" id="signupForm">
                @csrf

                {{-- Role Selection --}}
                <div class="mb-3">
                    <label class="form-label">Register As</label>
                    <select name="role" id="roleSelect" class="custom-input" required>
                        <option value="user">User</option>
                        <option value="patient">Patient</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="text" name="name" class="custom-input" placeholder="Full Name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="custom-input" placeholder="Email Address" value="{{ old('email') }}" required>
                </div>

                {{-- Staff Only Fields --}}
                <div class="mb-3 staff-fields d-none" id="employeeField">
                    <input type="text" name="employee_id" class="custom-input" placeholder="Employee ID" value="{{ old('employee_id') }}">
                </div>

                <div class="mb-3 staff-fields d-none" id="departmentField">
                    <input type="text" name="department" class="custom-input" placeholder="Department (e.g., Cardiology)" value="{{ old('department') }}">
                </div>

                <div class="mb-3">
                    <input type="text" name="phone" class="custom-input" placeholder="Phone Number (Optional)" value="{{ old('phone') }}">
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="custom-input" placeholder="Password (min 6 characters)" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="custom-input" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn-signup-submit">Create Account</button>

                <div class="text-center mt-4">
                    <p class="text-white-50 small">Already have an account? <a href="{{ route('login') }}" class="text-accent-cyan">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/signup.js') }}"></script>
@endsection