@extends('layouts.app')

@section('title', __('messages.nav.services') . ' - Ef')
@section('meta_description', 'Our services - Ef Photography Agency')

@php
    $pageTitle = filled($servicesPageTitle ?? null) ? (string) $servicesPageTitle : __('messages.nav.services');
    $listTitle = filled($servicesListTitle ?? null) ? (string) $servicesListTitle : 'Our Services';
@endphp

<!-- Page Header -->
<div class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ $headerBg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
        </div>
        <h2 class="wptb-item--title">{{ $pageTitle }}</h2>
    </div>
</div>

<!-- Our Services Listing -->
<section class="services-listing">
    <div class="container">
        <div class="wptb-project--inner">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h1 class="wptb-item--title">{{ $listTitle }}</h1>
                </div>
            </div>

            <div class="effect-gradient has-radius">
                <div class="grid grid-3 gutter-10 clearfix">
                    <div class="grid-sizer"></div>
                    @foreach ($services as $service)
                    @php
                        $coverUrl = $serviceCover($service);
                        $serviceUrl = route('services.show', ['slug' => $service->slug ?? $service->id]);
                    @endphp
                    <div class="grid-item">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <img src="{{ $coverUrl }}" alt="{{ $service->title }}">
                                <a class="wptb-item--link" href="{{ $serviceUrl }}"><i class="bi bi-chevron-right"></i></a>
                            </div>

                            <div class="wptb-item--holder">
                                <div class="wptb-item--meta">
                                    <h4 class="service_item_title"><a href="{{ $serviceUrl }}">{{ $service->title }}</a></h4>
                                    @if($service->short_teaser)
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($service->short_teaser), 60) }}</p>
                                    @else
                                        <p>&nbsp;</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


 <!-- BG Video -->
 <div class="container">
    <div class="wptb-video-player1 wow zoomIn" style="background-image: url('{{ $servicesVideoBg }}');">
        <div class="wptb-item--inner">
            <div class="wptb-item--holder">
                <div class="wptb-item--video-button">
                    <a class="btn" data-fancybox href="{{ $servicesVideoUrl }}">
                        <span class="text-second"> <i class="bi bi-play-fill"></i> </span>
                        <span class="line-video-animation line-video-1"></span>
                        <span class="line-video-animation line-video-2"></span>
                        <span class="line-video-animation line-video-3"></span>
                    </a>

                </div>
            </div>
        </div>
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="../assets/img/more/light-3.png" alt="img">
        </div>
    </div>
</div>




<section class="wptb-contact-form style1">
    <div class="wptb-item-layer both-version">
        <img src="{{ asset('assets/img/more/texture-2.png') }}" alt="">
        <img src="{{ asset('assets/img/more/texture-2-light.png') }}" alt="">
    </div>
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
                                        <input type="text" name="name" class="form-control" placeholder="{{ __('messages.cta.form.name_placeholder') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-4">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="{{ __('messages.cta.form.email_placeholder') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <input type="text" name="subject" class="form-control" placeholder="{{ __('messages.cta.form.subject_placeholder') }}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 mb-4">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" placeholder="{{ __('messages.cta.form.message_placeholder') }}"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="wptb-item--button text-center">
                                        <button class="btn white-opacity creative text-uppercase" type="submit">
                                            <span class="btn-wrap"><span class="text-first">{{ __('messages.cta.form.submit') }}</span></span>
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
                                <a href="#" class="wptb-item--link">{{ __('messages.cta.office.visit_now') }}</a>
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
                                <a href="tel:+995555123456" class="wptb-item--link">{{ __('messages.cta.office.call_now') }}</a>
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
                                <a href="{{ route('contact') }}" class="wptb-item--link">{{ __('messages.cta.office.view_map') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


