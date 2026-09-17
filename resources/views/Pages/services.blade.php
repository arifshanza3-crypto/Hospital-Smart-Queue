@extends('Layout.app')

@section('title', 'Our Services - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/services.css') }}" rel="stylesheet">

{{-- Services Hero --}}
<section class="services-hero">
    <div class="s-hero-container">
        <span class="s-badge">🌟 Our Expertise</span>
        <h1>
            Comprehensive <br><span class="text-accent">Smart Solutions</span>
        </h1>
        <p class="s-subtitle">
            Optimizing the journey between patient arrival and medical care with high-precision digital queueing systems.
        </p>
    </div>
</section>

{{-- Services Grid --}}
<section class="services-grid-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">What We Offer</span>
            <h2 class="section-title">Our <span class="text-accent">Healthcare Services</span></h2>
            <p class="section-desc">Comprehensive solutions designed to improve patient experience and hospital efficiency</p>
        </div>

        <div class="services-grid" id="servicesGrid">

            @forelse($services ?? [] as $service)
                <div class="service-card">
                    <div class="service-icon">
                        <i class="{{ $service->icon ?? 'fas fa-stethoscope' }}"></i>
                    </div>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description }}</p>
                </div>
            @empty
                <div class="no-services">
                    <i class="fas fa-concierge-bell"></i>
                    <h3>No Services Available</h3>
                    <p>Services will appear here once added from the admin panel.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>

{{-- Components --}}
@include('Component.features')
@include('Component.process')
@include('Component.Doctors_details')
@include('Component.contact_form')

<script src="{{ asset('js/services.js') }}"></script>
@endsection