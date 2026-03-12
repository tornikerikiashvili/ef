@props([
    'projects' => collect(),
    'categories' => collect(),
])
@php
    $categoryClass = fn ($project) => $project->category
        ? 'category-' . \Illuminate\Support\Str::slug($project->category->slug ?? $project->category->name ?? $project->category->id)
        : 'category-uncategorized';
@endphp
<!-- Page Header -->
<div class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ asset('assets/img/background/page-header-bg-8.jpg') }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
        </div>
        <h2 class="wptb-item--title">{{ __('messages.nav.projects') }}</h2>
    </div>
</div>

<!-- Our Projects -->
<section>
    <div class="container">
        <div class="wptb-project--inner">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h6 class="wptb-item--subtitle"><span>01//</span> {{ __('messages.nav.projects') }}</h6>
                    <h1 class="wptb-item--title">Kimono captures <span>All of Your</span> <br>
                        beautiful memories</h1>
                </div>
            </div>

            <div class="has-radius effect-tilt">
                <div class="portfolio-filters-content">
                    <div class="filters-button-group">
                        <button class="button is-checked" data-filter="*">All</button>
                        @foreach ($categories as $category)
                            <button class="button" data-filter=".category-{{ \Illuminate\Support\Str::slug($category->slug ?? $category->name ?? $category->id) }}">{{ $category->name }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-3 gutter-30 clearfix">
                    <div class="grid-sizer"></div>
                    @foreach ($projects as $project)
                    @php
                        $coverUrl = $project->cover_photo
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_photo)
                            : asset('assets/img/projects/3/1.jpg');
                        $projectUrl = route('projects.show', ['slug' => $project->slug ?? $project->id]);
                    @endphp
                    <div class="grid-item {{ $categoryClass($project) }}">
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
