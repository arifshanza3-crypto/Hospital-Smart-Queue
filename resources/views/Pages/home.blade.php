@extends('Layout.app')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">

{{-- ✅ HERO SLIDER SECTION --}}
<section class="hero-slider-section">
    <div class="hero-slider">
        <div class="hero-slide active">
            <div class="hero-content-container">
                <div class="hero-left">
                    <span class="hero-tag">Smart Solutions</span>
                    <h1 class="hero-title">Smart Queue <br>Management System</h1>
                    <p class="hero-description">Experience smarter healthcare with our real-time queue management system – reducing wait times, improving patient care, and optimizing hospital workflow.</p>
                    <div class="hero-btn">
                        <a href="{{ route('services') }}" class="btn-hero">Our Services</a>
                    </div>
                </div>
                <div class="hero-right">
                    <img src="https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?auto=format&fit=crop&q=80&w=1000" alt="Smart Queue Management">
                </div>
            </div>
        </div>
        
        <!-- Agar future mein dosra slider add karna ho toh yahan karein -->
    </div>

    {{-- ✅ TICKER BAR - Smart Queue Benefits --}}
    <div class="hero-bottom-bar">
        <div class="ticker-wrapper">
            <div class="ticker-content">
                <span> Real-Time Queue Updates</span> <span>•</span> 
                <span> Reduce Wait Times</span> <span>•</span> 
                <span> Instant Notifications</span> <span>•</span> 
                <span> Smart Patient Flow</span> <span>•</span> 
                <span> Live Analytics</span> <span>•</span> 
                <span> Real-Time Queue Updates</span> <span>•</span> 
                <span> Instant Notifications</span> <span>•</span> 
                <span> Smart Patient Flow</span> <span>•</span> 
                <span> Seamless Experience</span> <span>•</span> 
                <span> Live Analytics</span> <span>•</span> 
                <span> Efficient Care</span> <span>•</span>
            </div>
        </div>
    </div>
</section>

{{-- ✅ COMPONENTS --}}
@include("component/Doctors_details")

@include("component/contact_form")

<script src="{{ asset('js/home.js') }}"></script>
@endsection