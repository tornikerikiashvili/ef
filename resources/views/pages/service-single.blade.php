@extends('layouts.app')

@section('title', ($service->title ?? __('messages.nav.services')) . ' - Ef')
@section('meta_description', $service->short_teaser ? \Illuminate\Support\Str::limit(strip_tags($service->short_teaser), 160) : 'Service details - Ef Photography Agency')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;

    $galleryImages = is_array($service->gallery) && count($service->gallery) > 0
        ? collect($service->gallery)
        : collect();

    $infoCol1 = collect([
        ['label' => __('messages.service.summary'), 'value' => $service->short_teaser],
    ])->filter(fn ($row) => filled($row['value']) && filled($service->text_content));
@endphp

<section class="blog-details blog-details-box">
    <div class="container-fluid">
        <div class="blog-details-inner">
            <div class="post-content">
                <div class="row">
                    <div class="col-xl-7 col-md-7 pe-xl-5 pe-md-4">
                        <div class="fulltext">
                            @forelse ($galleryImages as $image)
                                @php
                                    $src = $image
                                        ? Storage::disk('public')->url($image)
                                        : asset('assets/img/services/details.jpg');
                                @endphp
                                <figure class="block-gallery mb-3">
                                    <img src="{{ $src }}" alt="{{ $service->title }}">
                                    <a class="wptb-image-popup" href="{{ $src }}" data-fancybox="service-gallery">
                                        <i class="bi bi-arrows-fullscreen"></i>
                                    </a>
                                </figure>
                            @empty
                                @php
                                    $fallback = $service->cover_photo
                                        ? Storage::disk('public')->url($service->cover_photo)
                                        : asset('assets/img/services/details.jpg');
                                @endphp
                                <figure class="block-gallery mb-3">
                                    <img src="{{ $fallback }}" alt="{{ $service->title }}">
                                    <a class="wptb-image-popup" href="{{ $fallback }}" data-fancybox="service-gallery">
                                        <i class="bi bi-arrows-fullscreen"></i>
                                    </a>
                                </figure>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-xl-5 col-md-5 pt-4 ps-xl-5 ps-md-4">
                        <div class="sidebar">
                            <div class="post-header">
                                <h1 class="post-title fw-normal">{{ $service->title }}</h1>
                            </div>
                            <div class="fulltext">
                                @if ($service->text_content)
                                    {!! $service->text_content !!}
                                @elseif ($service->short_teaser)
                                    <p>{{ $service->short_teaser }}</p>
                                @endif
                            </div>

                            @if ($infoCol1->isNotEmpty())
                                <div class="divider-line-hr my-4"></div>

                                <div class="wptb-project-info1 border-0 bg-transparent">
                                    <div class="wptb--holder p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                @foreach ($infoCol1 as $row)
                                                    <div class="wptb--item border-0">
                                                        <div class="wptb--meta">
                                                            <label>{{ $row['label'] }}:</label>
                                                            <span>{{ $row['value'] }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="wptb-page-links">
                <div class="wptb-pge-link--item previous">
                    @if ($prevService)
                        <a href="{{ route('services.show', ['slug' => $prevService->slug ?? $prevService->id]) }}"><i class="bi bi-arrow-left"></i> <span>Previous</span></a>
                    @else
                        <a href="{{ route('services') }}"><i class="bi bi-arrow-left"></i> <span>Back to Services</span></a>
                    @endif
                </div>
                <div class="wptb-pge-link--item next">
                    @if ($nextService)
                        <a href="{{ route('services.show', ['slug' => $nextService->slug ?? $nextService->id]) }}"><span>Next</span> <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="{{ route('services') }}"><span>Back to Services</span> <i class="bi bi-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        </div>

        @if ($relatedServices->isNotEmpty())
            <div class="pd-top-100">
                <div class="effect-tilt">
                    <div class="grid grid-4 gutter-5 clearfix">
                        <div class="grid-sizer"></div>
                        @foreach ($relatedServices as $related)
                            @php
                                $coverUrl = $related->cover_photo
                                    ? Storage::disk('public')->url($related->cover_photo)
                                    : asset('assets/img/projects/3/1.jpg');
                                $relatedUrl = route('services.show', ['slug' => $related->slug ?? $related->id]);
                            @endphp
                            <div class="grid-item">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--image">
                                        <img src="{{ $coverUrl }}" alt="{{ $related->title }}">
                                    </div>
                                    <div class="wptb-item--holder">
                                        <div class="wptb-item--meta">
                                            <h4><a href="{{ $relatedUrl }}">{{ $related->title }}</a></h4>
                                            @if ($related->short_teaser)
                                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($related->short_teaser), 60) }}</p>
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
        @endif
    </div>
</section>
@endsection
