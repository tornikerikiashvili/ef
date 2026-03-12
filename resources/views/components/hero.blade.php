@props([
    'services' => collect(),
])
@php
    $slides = $services->isNotEmpty() ? $services : null;
@endphp
<section class="wptb-slider style16">
    <div class="container">
        <div class="swiper-container wptb-swiper-slider-sixteen">
            <div class="swiper-wrapper">
                @if($slides && $slides->isNotEmpty())
                    @foreach ($slides as $service)
                    @php
                        $coverUrl = $service->cover_photo
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($service->cover_photo)
                            : asset('assets/img/slider/45.jpg');
                        $serviceUrl = route('services.show', ['slug' => $service->id]);
                    @endphp
                    <div class="swiper-slide">
                        <div class="wptb-slider--item" style="background-image: url('{{ $coverUrl }}');">
                            <div class="wptb-slider--inner">
                                <div class="wptb-heading">
                                    <h6 class="wptb-item--subtitle">{{ __('messages.nav.services') }}</h6>
                                    <h1 class="wptb-item--title">{{ $service->title }}</h1>
                                    @if($service->short_teaser)
                                        <p class="wptb-item--description mt-3">{{ \Illuminate\Support\Str::limit(strip_tags($service->short_teaser), 120) }}</p>
                                    @endif
                                    <div class="wptb-item--button">
                                        <a class="btn" href="{{ $serviceUrl }}">
                                            <span class="btn-wrap">
                                                <span class="text-first">{{ __('messages.hero.learn_details') }}</span>
                                                <span class="text-second"><span class="line"></span><span class="circle"></span><span class="dot"></span></span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if($service->cover_photo)
                                <a class="wptb-image-popup" href="{{ $coverUrl }}" data-fancybox="gallery">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="swiper-slide">
                        <div class="wptb-slider--item" style="background-image: url('{{ asset('assets/img/slider/45.jpg') }}');">
                            <div class="wptb-slider--inner">
                                <div class="wptb-heading">
                                    <h6 class="wptb-item--subtitle">{{ __('messages.nav.services') }}</h6>
                                    <h1 class="wptb-item--title">Welcome</h1>
                                    <div class="wptb-item--button">
                                        <a class="btn" href="{{ route('services') }}">
                                            <span class="btn-wrap">
                                                <span class="text-first">{{ __('messages.hero.view_services') }}</span>
                                                <span class="text-second"><span class="line"></span><span class="circle"></span><span class="dot"></span></span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <a class="wptb-image-popup" href="{{ asset('assets/img/slider/45.jpg') }}" data-fancybox="gallery">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="wptb-left-pane justify-content-center">
        <div class="logo"><h6>Our Works</h6></div>
    </div>
    <div class="wptb-right-pane">
        <div class="social-box style-oval">
            <ul>
                <li><a href="#">FB</a></li>
                <li><a href="#">IG</a></li>
                <li><a href="#">TW</a></li>
                <li><a href="#">YT</a></li>
            </ul>
        </div>
    </div>
    <div class="wptb-bottom-pane justify-content-center">
        <div class="wptb-swiper-dots style2"><div class="swiper-pagination"></div></div>
        <div class="wptb-swiper-navigation style3">
            <div class="wptb-swiper-arrow swiper-button-prev"></div>
            <div class="wptb-swiper-arrow swiper-button-next"></div>
        </div>
    </div>
    <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div>
</section>
