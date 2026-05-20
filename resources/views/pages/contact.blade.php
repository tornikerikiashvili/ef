@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - Ef')
@section('meta_description', 'Contact us - Ef Photography Agency')

@section('content')
    @php
        $contactPage = \App\Models\Page::payloadFor(\App\Models\Page::KEY_CONTACT_PAGE);
        $googleMapEmbed = trim((string) ($contactPage['google_map_embed'] ?? ''));
        if ($googleMapEmbed !== '') {
            $googleMapEmbed = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $googleMapEmbed) ?? $googleMapEmbed;
        }

        // Default pin (Tbilisi) when no CMS embed is saved.
        $mapLat = 41.7151;
        $mapLng = 44.8271;
        $googleMapsEmbedSrc = 'https://www.google.com/maps?q=' . $mapLat . ',' . $mapLng . '&z=15&output=embed';
        $googleMapsExternalUrl = 'https://www.google.com/maps?q=' . $mapLat . ',' . $mapLng . '&z=15';

        if ($googleMapEmbed !== '' && preg_match('/src=["\']([^"\']+)["\']/', $googleMapEmbed, $m)) {
            $googleMapsExternalUrl = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
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
            @if(filled($googleMapEmbed))
                {!! $googleMapEmbed !!}
            @else
                <iframe
                    src="{{ $googleMapsEmbedSrc }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                    title="Map"
                ></iframe>
            @endif
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

            <x-contact-office-cards :map-fallback-href="$googleMapsExternalUrl" />
        </div>
    </section>
@endsection
