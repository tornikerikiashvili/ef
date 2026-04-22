@props([
    'services' => collect(),
    'pageCover' => null,
])
@php
    use Illuminate\Support\Facades\Storage;

    $coverPath = is_array($pageCover) ? (reset($pageCover) ?: null) : $pageCover;
    $headerBg = $coverPath
        ? Storage::disk('public')->url($coverPath)
        : asset('assets/img/background/page-header-bg-8.jpg');

    $serviceCover = fn ($service) => $service->cover_photo
        ? Storage::disk('public')->url($service->cover_photo)
        : asset('assets/img/projects/3/1.jpg');
@endphp

<!-- Page Header -->
<div class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ $headerBg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
        </div>
        <h2 class="wptb-item--title">{{ __('messages.nav.services') }}</h2>
    </div>
</div>

<!-- Our Services Listing -->
<section class="services-listing">
    <div class="container">
        <div class="wptb-project--inner">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h1 class="wptb-item--title">Our <span>Services</span></h1>
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
    <div class="wptb-video-player1 wow zoomIn" style="background-image: url('../assets/img/background/bg-7.jpg');">
        <div class="wptb-item--inner">
            <div class="wptb-item--holder">
                <div class="wptb-item--video-button">
                    <a class="btn" data-fancybox href="https://www.youtube.com/watch?v=SF4aHwxHtZ0">
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
