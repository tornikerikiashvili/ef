@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - Ef')
@section('meta_description', 'Our projects - Ef Photography Agency')

@push('styles')
<style>
    .projects-listing--olive { position: relative; }
    .projects-listing--olive .grid_lines { background-color: #C0C6AF; }
    .projects-listing--olive .grid_lines .grid_line { background-color: rgba(255, 255, 255, 0.6) !important; mix-blend-mode: normal; }

    .projects-listing--olive .projects-status-filter select.no-nice-select {
        height: 38px;
        padding: 6px 10px;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.85);
        color: #111;
        outline: none;
    }
</style>
@endpush
@php
    $pageTitle = filled($projectsPageTitle ?? null) ? (string) $projectsPageTitle : __('messages.nav.projects');

    $categoryClass = fn ($project) => $project->category
        ? 'category-' . \Illuminate\Support\Str::slug($project->category->slug ?? $project->category->name ?? $project->category->id)
        : 'category-uncategorized';
    $statusClass = fn ($project) => filled($project->status_id ?? null)
        ? 'status-' . ((int) $project->status_id)
        : 'status-unknown';
@endphp
<!-- Page Header -->
<div class="wptb-page-heading" style="background-color: #C0C6AF;">
    <div class="wptb-item--inner" style="background-image: url('{{ $headerBg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            @if(!filled($headerBg) || str_contains($headerBg, 'page-header-bg-8.jpg'))
              <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
            @endif
        </div>
        <h2 class="wptb-item--title">{{ $pageTitle }}</h2>
    </div>
</div>

<!-- Our Projects -->
<section class="projects-listing--olive" style="background-color: #C0C6AF;">
    {{-- <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div> --}}
    <div class="container">
        <div class="wptb-project--inner">
            {{-- <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h1 class="wptb-item--title">{{ $pageTitle }}</h1>
                </div>
            </div> --}}

            <div class="has-radius effect-tilt">
                <div class="portfolio-filters-content">
                    <div class="projects-filters d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="filters-button-group">
                            <button class="button is-checked" data-filter="*">{{ __('messages.filters.all') }}</button>
                            @foreach ($categories as $category)
                                @php
                                    $catKey = \Illuminate\Support\Str::slug($category->slug ?? $category->name ?? $category->id);
                                    $catLabel = method_exists($category, 'getTranslation')
                                        ? ($category->getTranslation('name', app()->getLocale(), false) ?: $category->getTranslation('name', 'en', false) ?: $category->name)
                                        : $category->name;
                                @endphp
                                <button class="button" data-filter=".category-{{ $catKey }}">{{ $catLabel }}</button>
                            @endforeach
                        </div>

                        <div class="projects-status-filter ms-auto">
                            <select id="projects-status-filter" class="no-nice-select" style="width: auto;">
                                <option value="*">{{ __('messages.filters.all') }}</option>
                                @foreach ($statuses as $status)
                                    @php
                                        $sId = (int) $status->id;
                                        $sLabel = method_exists($status, 'getTranslation')
                                            ? ($status->getTranslation('name', app()->getLocale(), false) ?: $status->getTranslation('name', 'en', false) ?: $status->name)
                                            : $status->name;
                                    @endphp
                                    <option value=".status-{{ $sId }}">{{ $sLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-3 gutter-30 clearfix">
                    <div class="grid-sizer"></div>
                    @foreach ($projects as $project)
                    @php
                        $gallery = is_array($project->gallery ?? null) ? $project->gallery : [];
                        $firstGalleryImage = $gallery[0] ?? null;
                        $imagePath = filled($firstGalleryImage) ? $firstGalleryImage : ($project->cover_photo ?: null);

                        $coverUrl = $imagePath
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath)
                            : asset('assets/img/projects/3/1.jpg');
                        $projectUrl = route('projects.show', ['slug' => $project->slug ?? $project->id]);
                    @endphp
                    <div class="grid-item {{ $categoryClass($project) }} {{ $statusClass($project) }}">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <a href="{{ $projectUrl }}">
                                    <img src="{{ $coverUrl }}" alt="{{ $project->title }}">
                                </a>
                            </div>
                            <div class="wptb-item--holder">
                                <div class="wptb-item--meta">
                                    <h4><a href="{{ $projectUrl }}">{{ $project->title }}</a></h4>
                                    @if($project->client)
                                        <p>{{ $project->client }}</p>
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

@push('scripts')
<script>
  (function () {
    var categoryFilter = '*';
    var statusFilter = '*';

    function combinedFilter() {
      if (categoryFilter === '*' && statusFilter === '*') return '*';
      if (categoryFilter === '*') return statusFilter;
      if (statusFilter === '*') return categoryFilter;
      return categoryFilter + statusFilter;
    }

    // Override theme's default filter handler to allow combining.
    jQuery(document).on('click', '.projects-listing--olive .filters-button-group .button', function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      categoryFilter = jQuery(this).attr('data-filter') || '*';
      window.$grid && window.$grid.isotope({ filter: combinedFilter() });
    });

    jQuery(document).on('change', '#projects-status-filter', function () {
      statusFilter = jQuery(this).val() || '*';
      window.$grid && window.$grid.isotope({ filter: combinedFilter() });
    });
  })();
</script>
@endpush



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


