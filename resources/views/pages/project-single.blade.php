@extends('layouts.app')

@section('title', ($project->title ?? __('messages.nav.projects')) . ' - Ef')
@section('meta_description', $project->text_content ? \Illuminate\Support\Str::limit(strip_tags($project->text_content), 160) : 'Project details - Ef')

@section('content')
@php
    $galleryImages = is_array($project->gallery) && count($project->gallery) > 0
        ? collect($project->gallery)
        : collect([null]); // placeholder if no gallery
@endphp
<section class="blog-details">
    <div class="container">
        <div class="blog-details-inner">
            <div class="post-content">
                <div class="row">
                    <div class="col-lg-9 col-md-8 pe-md-5">
                        <!-- Gallery -->
                        <div class="swiper-container swiper-gallery mb-4">
                            <div class="swiper-wrapper">
                                @foreach ($galleryImages as $image)
                                <div class="swiper-slide">
                                    <figure class="block-gallery">
                                        <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image) : asset('assets/img/projects/gallery/3.jpg') }}" alt="{{ $project->title }}">
                                    </figure>
                                </div>
                                @endforeach
                            </div>
                            <div class="wptb-swiper-navigation style2">
                                <div class="wptb-swiper-arrow swiper-button-prev"></div>
                                <div class="wptb-swiper-arrow swiper-button-next"></div>
                            </div>
                        </div>

                        <div class="post-header">
                            <h1 class="post-title">{{ $project->title }}</h1>
                        </div>
                        <div class="fulltext">
                            @if($project->text_content)
                                {!! $project->text_content !!}
                            @else
                                @if($project->client)
                                    <p>{{ __('messages.project.client') }}: {{ $project->client }}</p>
                                @endif
                            @endif

                            <div class="swiper-container swiper-testimonial mt-5">
                                <!-- swiper slides -->
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="wptb-testimonial1">
                                            <div class="wptb-item--inner">
                                                <div class="wptb-item--holder">
                                                    <div class="d-flex align-items-center justify-content-between mr-bottom-25">
                                                        <div class="wptb-item--meta-rating">
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                        </div>

                                                        <div class="wptb-item--icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="57" height="45" viewBox="0 0 57 45" fill="none">
                                                                <path d="M51.5137 38.5537C56.8209 32.7938 56.2866 25.3969 56.2697 25.3125V2.8125C56.2697 2.06658 55.9734 1.35121 55.4459 0.823763C54.9185 0.296317 54.2031 0 53.4572 0H36.5822C33.48 0 30.9572 2.52281 30.9572 5.625V25.3125C30.9572 26.0584 31.2535 26.7738 31.781 27.3012C32.3084 27.8287 33.0238 28.125 33.7697 28.125H42.4266C42.3671 29.5155 41.9517 30.8674 41.22 32.0513C39.7913 34.3041 37.0997 35.8425 33.2156 36.6188L30.9572 37.0688V45H33.7697C41.5969 45 47.5678 42.8316 51.5137 38.5537ZM20.5566 38.5537C25.8666 32.7938 25.3294 25.3969 25.3125 25.3125V2.8125C25.3125 2.06658 25.0162 1.35121 24.4887 0.823763C23.9613 0.296317 23.2459 0 22.5 0H5.625C2.52281 0 0 2.52281 0 5.625V25.3125C0 26.0584 0.296316 26.7738 0.823762 27.3012C1.35121 27.8287 2.06658 28.125 2.8125 28.125H11.4694C11.41 29.5155 10.9945 30.8674 10.2628 32.0513C8.83406 34.3041 6.1425 35.8425 2.25844 36.6188L0 37.0688V45H2.8125C10.6397 45 16.6106 42.8316 20.5566 38.5537Z" fill="#D70006"/>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <p class="wptb-item--description"> “I have an amazing photography session with team
                                                        kimono photography agency, highly recommended.
                                                        They have amazing atmosphere in their studio. Iw’d
                                                        love to visit again”</p>
                                                    <div class="wptb-item--meta">
                                                        <div class="wptb-item--image">
                                                            <img src="../assets/img/testimonial/1.jpg" alt="img">
                                                        </div>
                                                        <div class="wptb-item--meta-left">
                                                            <h4 class="wptb-item--title">Rachel Jackson</h4>
                                                            <h6 class="wptb-item--designation">New York</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="swiper-slide">
                                        <div class="wptb-testimonial1">
                                            <div class="wptb-item--inner">
                                                <div class="wptb-item--holder">
                                                    <div class="d-flex align-items-center justify-content-between mr-bottom-25">
                                                        <div class="wptb-item--meta-rating">
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                        </div>

                                                        <div class="wptb-item--icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="57" height="45" viewBox="0 0 57 45" fill="none">
                                                                <path d="M51.5137 38.5537C56.8209 32.7938 56.2866 25.3969 56.2697 25.3125V2.8125C56.2697 2.06658 55.9734 1.35121 55.4459 0.823763C54.9185 0.296317 54.2031 0 53.4572 0H36.5822C33.48 0 30.9572 2.52281 30.9572 5.625V25.3125C30.9572 26.0584 31.2535 26.7738 31.781 27.3012C32.3084 27.8287 33.0238 28.125 33.7697 28.125H42.4266C42.3671 29.5155 41.9517 30.8674 41.22 32.0513C39.7913 34.3041 37.0997 35.8425 33.2156 36.6188L30.9572 37.0688V45H33.7697C41.5969 45 47.5678 42.8316 51.5137 38.5537ZM20.5566 38.5537C25.8666 32.7938 25.3294 25.3969 25.3125 25.3125V2.8125C25.3125 2.06658 25.0162 1.35121 24.4887 0.823763C23.9613 0.296317 23.2459 0 22.5 0H5.625C2.52281 0 0 2.52281 0 5.625V25.3125C0 26.0584 0.296316 26.7738 0.823762 27.3012C1.35121 27.8287 2.06658 28.125 2.8125 28.125H11.4694C11.41 29.5155 10.9945 30.8674 10.2628 32.0513C8.83406 34.3041 6.1425 35.8425 2.25844 36.6188L0 37.0688V45H2.8125C10.6397 45 16.6106 42.8316 20.5566 38.5537Z" fill="#D70006"/>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <p class="wptb-item--description"> “I have an amazing photography session with team
                                                        kimono photography agency, highly recommended.
                                                        They have amazing atmosphere in their studio. Iw’d
                                                        love to visit again”</p>
                                                    <div class="wptb-item--meta">
                                                        <div class="wptb-item--image">
                                                            <img src="../assets/img/testimonial/2.jpg" alt="img">
                                                        </div>
                                                        <div class="wptb-item--meta-left">
                                                            <h4 class="wptb-item--title">Helen Jordan</h4>
                                                            <h6 class="wptb-item--designation">Chicago</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="swiper-slide">
                                        <div class="wptb-testimonial1">
                                            <div class="wptb-item--inner">
                                                <div class="wptb-item--holder">
                                                    <div class="d-flex align-items-center justify-content-between mr-bottom-25">
                                                        <div class="wptb-item--meta-rating">
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                            <i class="bi bi-star-fill"></i>
                                                        </div>

                                                        <div class="wptb-item--icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="57" height="45" viewBox="0 0 57 45" fill="none">
                                                                <path d="M51.5137 38.5537C56.8209 32.7938 56.2866 25.3969 56.2697 25.3125V2.8125C56.2697 2.06658 55.9734 1.35121 55.4459 0.823763C54.9185 0.296317 54.2031 0 53.4572 0H36.5822C33.48 0 30.9572 2.52281 30.9572 5.625V25.3125C30.9572 26.0584 31.2535 26.7738 31.781 27.3012C32.3084 27.8287 33.0238 28.125 33.7697 28.125H42.4266C42.3671 29.5155 41.9517 30.8674 41.22 32.0513C39.7913 34.3041 37.0997 35.8425 33.2156 36.6188L30.9572 37.0688V45H33.7697C41.5969 45 47.5678 42.8316 51.5137 38.5537ZM20.5566 38.5537C25.8666 32.7938 25.3294 25.3969 25.3125 25.3125V2.8125C25.3125 2.06658 25.0162 1.35121 24.4887 0.823763C23.9613 0.296317 23.2459 0 22.5 0H5.625C2.52281 0 0 2.52281 0 5.625V25.3125C0 26.0584 0.296316 26.7738 0.823762 27.3012C1.35121 27.8287 2.06658 28.125 2.8125 28.125H11.4694C11.41 29.5155 10.9945 30.8674 10.2628 32.0513C8.83406 34.3041 6.1425 35.8425 2.25844 36.6188L0 37.0688V45H2.8125C10.6397 45 16.6106 42.8316 20.5566 38.5537Z" fill="#D70006"/>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <p class="wptb-item--description"> “I have an amazing photography session with team
                                                        kimono photography agency, highly recommended.
                                                        They have amazing atmosphere in their studio. Iw’d
                                                        love to visit again”</p>
                                                    <div class="wptb-item--meta">
                                                        <div class="wptb-item--image">
                                                            <img src="../assets/img/testimonial/3.jpg" alt="img">
                                                        </div>
                                                        <div class="wptb-item--meta-left">
                                                            <h4 class="wptb-item--title">Helen Jordan</h4>
                                                            <h6 class="wptb-item--designation">New York</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Swiper Navigation -->
                                <div class="wptb-swiper-navigation style1">
                                    <div class="wptb-swiper-arrow swiper-button-prev"></div>
                                    <div class="wptb-swiper-arrow swiper-button-next"></div>
                                </div>
                            </div>

                            <div class="wptb-page-links">
                                <div class="wptb-pge-link--item previous">
                                    @if($prevProject)
                                        <a href="{{ route('projects.show', ['slug' => $prevProject->slug ?? $prevProject->id]) }}"><i class="bi bi-arrow-left"></i> <span>Previous</span></a>
                                    @else
                                        <a href="{{ route('projects') }}"><i class="bi bi-arrow-left"></i> <span>Back to Projects</span></a>
                                    @endif
                                </div>
                                <div class="wptb-pge-link--item next">
                                    @if($nextProject)
                                        <a href="{{ route('projects.show', ['slug' => $nextProject->slug ?? $nextProject->id]) }}"><span>Next</span> <i class="bi bi-arrow-right"></i></a>
                                    @else
                                        <a href="{{ route('projects') }}"><span>Back to Projects</span> <i class="bi bi-arrow-right"></i></a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Project Side Info -->
                    <div class="col-lg-3 col-md-4 p-md-0 mt-5 mt-md-0">
                        <div class="sidebar">
                            <div class="wptb-project-info1">
                                <h5 class="wptb-item--title">{{ __('messages.project.details') }}</h5>
                                <div class="wptb--holder">
                                    @if($project->client)
                                    <div class="wptb--item">
                                        <div class="wptb--meta"><label>{{ __('messages.project.client') }}:</label> <span>{{ $project->client }}</span></div>
                                    </div>
                                    @endif
                                    @if($project->location)
                                    <div class="wptb--item">
                                        <div class="wptb--meta"><label>{{ __('messages.project.location') }}:</label> <span>{{ $project->location }}</span></div>
                                    </div>
                                    @endif
                                    @if($project->area)
                                    <div class="wptb--item">
                                        <div class="wptb--meta"><label>{{ __('messages.project.area') }}:</label> <span>{{ $project->area }}</span></div>
                                    </div>
                                    @endif
                                    @if($project->category)
                                    <div class="wptb--item">
                                        <div class="wptb--meta"><label>{{ __('messages.project.category') }}:</label> <span>{{ $project->category->name }}</span></div>
                                    </div>
                                    @endif
                                    @if($project->status_text || $project->status)
                                    <div class="wptb--item">
                                        <div class="wptb--meta"><label>{{ __('messages.project.status') }}:</label> <span>{{ $project->status_text ?? $project->status?->name ?? '-' }}</span></div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
