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

                        <div class="projects-status-filter ms-auto d-inline-flex align-items-center gap-2">
                            <label for="projects-status-filter" class="projects-status-filter__label mb-0">
                                {{ __('messages.filters.status') }}:
                            </label>
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
                                <img src="{{ $coverUrl }}" alt="{{ $project->title }}">
                            </div>
                            <div class="wptb-item--holder">
                                <div class="wptb-item--meta">
                                    <h4><a href="{{ $projectUrl }}">{{ $project->title }}</a></h4>
                                    @if($project->client)
                                        <p>By {{ $project->client }}</p>
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






