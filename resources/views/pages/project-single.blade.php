@extends('layouts.app')

@section('title', ($project->title ?? __('messages.nav.projects')) . ' - Ef')
@section('meta_description', $project->text_content ? \Illuminate\Support\Str::limit(strip_tags($project->text_content), 160) : 'Project details - Ef')

@section('content')
@push('styles')
<style>
    .blog-details .effect-tilt .grid-item > .wptb-item--inner.project-related__card {
        display: block;
        text-decoration: none;
        color: var(--color-two);
        cursor: pointer;
    }

    .blog-details .effect-tilt .grid-item > .wptb-item--inner.project-related__card:focus-visible {
        outline: 2px solid var(--color-one);
        outline-offset: 4px;
    }

    .blog-details .effect-tilt .grid-item > .wptb-item--inner.project-related__card .wptb-item--meta h4,
    .blog-details .effect-tilt .grid-item > .wptb-item--inner.project-related__card .wptb-item--meta p {
        color: inherit;
    }

    @media (min-width: 768px) {
        .project-single__row {
            flex-wrap: nowrap;
        }
        .project-single__row .project-single__main,
        .project-single__row .project-single__sidebar {
            width: auto;
        }
        .project-single__row .project-single__main {
            flex: 0 0 60%;
            max-width: 60%;
        }
        .project-single__row .project-single__sidebar {
            flex: 0 0 40%;
            max-width: 40%;
        }
    }
</style>
@endpush
@php
    $galleryImages = is_array($project->gallery) && count($project->gallery) > 0
        ? collect($project->gallery)
        : collect([null]);
    $youtubeWatch = \App\Models\Project::youtubeWatchUrl($project->video_url);
    $youtubePosterId = \App\Models\Project::youtubeVideoId($project->video_url);
    $projectVideoBgUrl = filled($project->video_poster)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($project->video_poster)
        : ($youtubePosterId
            ? 'https://i.ytimg.com/vi/'.$youtubePosterId.'/hqdefault.jpg'
            : asset('assets/img/background/bg-7.jpg'));
@endphp
<section class="blog-details">
    <div class="container">
        <div class="blog-details-inner">
            <div class="post-content">
                <div class="row project-single__row">
                    <div class="col-12 project-single__main pe-md-5">
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
                            @if ($project->text_content)
                                {!! $project->text_content !!}
                            @elseif ($project->client)
                                <p>{{ __('messages.project.client') }}: {{ $project->client }}</p>
                            @endif

                            @if ($youtubeWatch)
                                <x-wptb-video-section class="mb-4 mt-4" :url="$youtubeWatch" :background-image="$projectVideoBgUrl" />
                            @endif

                            <div class="wptb-page-links">
                                <div class="wptb-pge-link--item previous">
                                    @if ($prevProject)
                                        <a href="{{ route('projects.show', ['slug' => $prevProject->slug ?? $prevProject->id]) }}"><i class="bi bi-arrow-left"></i> <span>{{ __('messages.project.previous') }}</span></a>
                                    @else
                                        <a href="{{ route('projects') }}"><i class="bi bi-arrow-left"></i> <span>{{ __('messages.project.back_to_projects') }}</span></a>
                                    @endif
                                </div>
                                <div class="wptb-pge-link--item next">
                                    @if ($nextProject)
                                        <a href="{{ route('projects.show', ['slug' => $nextProject->slug ?? $nextProject->id]) }}"><span>{{ __('messages.project.next') }}</span> <i class="bi bi-arrow-right"></i></a>
                                    @else
                                        <a href="{{ route('projects') }}"><span>{{ __('messages.project.back_to_projects') }}</span> <i class="bi bi-arrow-right"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 project-single__sidebar p-md-0 mt-5 mt-md-0">
                        <div class="sidebar">
                            <div class="wptb-project-info1">
                                <h5 class="wptb-item--title">{{ __('messages.project.details') }}</h5>
                                <div class="wptb--holder">
                                    @if ($project->client)
                                        <div class="wptb--item">
                                            <div class="wptb--meta"><label>{{ __('messages.project.client') }}:</label> <span>{{ $project->client }}</span></div>
                                        </div>
                                    @endif
                                    @if ($project->location)
                                        <div class="wptb--item">
                                            <div class="wptb--meta"><label>{{ __('messages.project.location') }}:</label> <span>{{ $project->location }}</span></div>
                                        </div>
                                    @endif
                                    @if ($project->area)
                                        <div class="wptb--item">
                                            <div class="wptb--meta"><label>{{ __('messages.project.area') }}:</label> <span>{{ $project->area }}</span></div>
                                        </div>
                                    @endif
                                    @if ($project->categories->isNotEmpty())
                                        <div class="wptb--item">
                                            <div class="wptb--meta"><label>{{ __('messages.project.category') }}:</label> <span>{{ $project->categories->pluck('name')->unique()->join(', ') }}</span></div>
                                        </div>
                                    @endif
                                    @if ($project->status_text || $project->status)
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

        @if ($relatedProjects->isNotEmpty())
            <div class="pd-top-100">
                <div class="effect-tilt">
                    <div class="grid grid-4 gutter-5 clearfix">
                        <div class="grid-sizer"></div>
                        @foreach ($relatedProjects as $related)
                            @php
                                $firstGallery = null;
                                if (is_array($related->gallery ?? null)) {
                                    foreach ($related->gallery as $path) {
                                        if (is_string($path) && $path !== '') {
                                            $firstGallery = $path;
                                            break;
                                        }
                                    }
                                }

                                $coverUrl = $firstGallery
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($firstGallery)
                                    : ($related->cover_photo
                                        ? \Illuminate\Support\Facades\Storage::disk('public')->url($related->cover_photo)
                                        : asset('assets/img/projects/3/1.jpg'));
                                $relatedUrl = route('projects.show', ['slug' => $related->slug ?? $related->id]);
                            @endphp
                            <div class="grid-item">
                                <a class="wptb-item--inner project-related__card" href="{{ $relatedUrl }}">
                                    <div class="wptb-item--image">
                                        <img src="{{ $coverUrl }}" alt="">
                                    </div>
                                    <div class="wptb-item--holder">
                                        <div class="wptb-item--meta">
                                            <h4>{{ $related->title }}</h4>
                                            @if ($related->client)
                                                <p>{{ $related->client }}</p>
                                            @else
                                                <p>&nbsp;</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
