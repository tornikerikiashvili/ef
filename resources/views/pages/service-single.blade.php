@extends('layouts.app')

@section('title', ($service->title ?? __('messages.nav.services')) . ' - Ef')
@section('meta_description', $service->short_teaser ? \Illuminate\Support\Str::limit(strip_tags($service->short_teaser), 160) : 'Service details - Ef Photography Agency')

@section('content')
@php
    $coverUrl = $service->cover_photo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($service->cover_photo)
        : asset('assets/img/services/details.jpg');
@endphp
<section class="blog-details">
    <div class="container">
        <div class="row">

            <!-- Service Navigation List -->
            <div class="col-lg-4 col-md-4 pe-md-5">
                <div class="sidebar">
                    <div class="sidenav">
                        <ul class="side_menu">
                            <li class="menu-item active">
                                <a href="service-details.html" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        Studio Photography
                                    </span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="service-details.html" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        Wedding Photography
                                    </span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="service-details.html" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        Newborn Photography
                                    </span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="service-details.html" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        Indoor Photography
                                    </span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="service-details.html" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        Outdoor Photography
                                    </span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-md-8 mb-5 mb-md-0 ps-md-0">
                <div class="blog-details-inner">
                    <div class="post-content">

                        <!-- Post Image -->
                        <figure class="block-gallery mb-4">
                            <img src="{{ $coverUrl }}" alt="{{ $service->title }}">
                        </figure>

                        <div class="post-header">
                            <h1 class="post-title">{{ $service->title }}</h1>
                        </div>
                        <div class="fulltext">
                            @if($service->text_content)
                                {!! $service->text_content !!}
                            @else
                                @if($service->short_teaser)
                                    <p>{{ $service->short_teaser }}</p>
                                @endif
                            @endif

                            <!-- Start Section -->
                            <h4 class="widget-title">Service Steps</h4>
                            <p>The talent at kimono runs wide and deep. Across many markets, geographies & typologies, our team members are some of the finest professionals in the industry wide and deep. </p>

                            <ul class="point-order">
                                <li><i class="bi bi-check2-all"></i> The talent at Kimono runs wide and deep. Across many markets, geographies</li>
                                <li><i class="bi bi-check2-all"></i> Our team members are some of the finest professionals in the industry</li>
                                <li><i class="bi bi-check2-all"></i> Organized to deliver the most specialized service possible and enriched by the</li>
                            </ul>

                            <p>The talent at kimono runs wide and deep. Across many markets, geographies & typologies, our team members are some of the finest professionals in the industry wide and deep. Across many markets, geographies and typologies, our team members are some of the finest.</p>
                            <p>The talent at kimono runs wide and deep. Across many markets, geographies & typologies, our team members are some of the finest professionals in the industry wide and deep. Across many markets, geographies and typologies, our team members are some of the finest.The talent at kimora runs wide and deep. Across many markets, geographies & typologies, our team members are some of the finest professionals in the industry wide and deep. Across many markets, geographies and typologies, our team members are some of the finest.</p>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
