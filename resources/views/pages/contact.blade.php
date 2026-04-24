@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - Ef')
@section('meta_description', 'Contact us - Ef Photography Agency')

@section('content')
    @php
        // Default pin (Tbilisi). Change later to your real coordinates.
        $mapLat = 41.7151;
        $mapLng = 44.8271;

        // Google Maps embed (no API key required).
        $googleMapsEmbedSrc = 'https://www.google.com/maps?q=' . $mapLat . ',' . $mapLng . '&z=15&output=embed';
    @endphp

    @push('styles')
        <style>
            .contact-map {
                height: 420px;
                margin-top: 150px;
                margin-bottom: 100px;
                border-radius: 20px;
                overflow: hidden;
                border: 1px solid rgba(0,0,0,0.08);
            }
            @media (max-width: 767.9px) {
                .contact-map { height: 320px; }
            }

            .contact-map iframe {
                width: 100%;
                height: 100%;
                border: 0;
                display: block;
                filter: grayscale(0.1) contrast(1.05);
            }
        </style>
    @endpush

    <div class="container">
        <div class="contact-map" aria-label="Map">
            <iframe
                src="{{ $googleMapsEmbedSrc }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
                title="Map"
            ></iframe>
        </div>
    </div>

    <x-cta />
@endsection
