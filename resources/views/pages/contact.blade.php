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
        $googleMapsExternalUrl = 'https://www.google.com/maps?q=' . $mapLat . ',' . $mapLng . '&z=15';
    @endphp

    @push('styles')
        <style>
            .contact-map {
                height: 420px;
                margin-top: 150px;
                margin-bottom: 100px;
                border-radius: 20px;
                overflow: hidden;
                border: 1px solid rgba(0, 0, 0, 0.08);
            }

            @media (max-width: 767.9px) {
                .contact-map {
                    height: 320px;
                }
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

    {{-- Layout aligned with Kimono light/contact-1.html (no hero-style bg image; homepage CTA keeps that) --}}
    <section class="wptb-contact-form style1">
        <div class="container">
            <div class="wptb-form--wrapper">
                <div class="wptb-heading">
                    <div class="wptb-item--inner text-center">
                        <h1 class="wptb-item--title">{{ __('messages.cta.title') }}</h1>
                        <div class="wptb-item--description">{{ __('messages.cta.description') }}</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form class="wptb-form" action="#" method="post">
                            @csrf
                            <div class="wptb-form--inner">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control"
                                                placeholder="{{ __('messages.cta.form.name_placeholder') }}"
                                                required
                                                autocomplete="name"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="form-group">
                                            <input
                                                type="email"
                                                name="email"
                                                class="form-control"
                                                placeholder="{{ __('messages.cta.form.email_placeholder') }}"
                                                required
                                                autocomplete="email"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 mb-4">
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                name="subject"
                                                class="form-control"
                                                placeholder="{{ __('messages.cta.form.subject_placeholder') }}"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 mb-4">
                                        <div class="form-group">
                                            <textarea
                                                name="message"
                                                class="form-control"
                                                rows="6"
                                                placeholder="{{ __('messages.cta.form.message_placeholder') }}"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12">
                                        <div class="wptb-item--button text-center">
                                            <button class="btn" type="submit">
                                                <span class="btn-wrap">
                                                    <span class="text-first">{{ __('messages.cta.form.submit') }}</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="wptb-office-address mr-top-100">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="wptb-icon-box1 wow fadeInLeft">
                            <div class="wptb-item--inner flex-start">
                                <div class="wptb-item--icon"><i class="bi bi-globe"></i></div>
                                <div class="wptb-item--holder">
                                    <h3 class="wptb-item--title">{{ __('messages.cta.office.website_title') }}</h3>
                                    <p class="wptb-item--description">www.example.com</p>
                                    <a href="https://www.example.com" class="wptb-item--link" rel="noopener noreferrer">
                                        {{ __('messages.cta.office.visit_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 px-md-5">
                        <div class="wptb-icon-box1 wow fadeInLeft">
                            <div class="wptb-item--inner flex-start">
                                <div class="wptb-item--icon"><i class="bi bi-phone"></i></div>
                                <div class="wptb-item--holder">
                                    <h3 class="wptb-item--title">{{ __('messages.cta.office.book_us') }}</h3>
                                    <p class="wptb-item--description">+995 555 123 456</p>
                                    <a href="tel:+995555123456" class="wptb-item--link">
                                        {{ __('messages.cta.office.call_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="wptb-icon-box1 wow fadeInLeft">
                            <div class="wptb-item--inner flex-start">
                                <div class="wptb-item--icon"><i class="bi bi-geo-alt"></i></div>
                                <div class="wptb-item--holder">
                                    <h3 class="wptb-item--title">{{ __('messages.cta.office.studio_address') }}</h3>
                                    <p class="wptb-item--description">{{ __('messages.cta.office.address_placeholder') }}</p>
                                    <a href="{{ $googleMapsExternalUrl }}" class="wptb-item--link" target="_blank" rel="noopener noreferrer">
                                        {{ __('messages.cta.office.view_map') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
