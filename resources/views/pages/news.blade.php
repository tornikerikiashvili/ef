@extends('layouts.app')

@section('title', __('messages.nav.news') . ' - Ef')
@section('meta_description', 'News and blog - Ef Photography Agency')

@push('styles')
<style>
    /* Small cards: design target 612×366 (ratio preserved; scales down in narrow columns) */
    .news-listing-mosaic .grid-item.width-50 .wptb-item--image {
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 612px;
        margin-left: auto;
        margin-right: auto;
        aspect-ratio: 612 / 366;
        height: auto;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .news-listing-mosaic .grid-item.width-100 .wptb-item--image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    @media screen and (max-width: 500px) {
        .news-listing-mosaic .grid-item.width-50 {
            width: 50% !important;
        }
        .news-listing-mosaic .grid-item.width-100 {
            width: 100% !important;
        }
        .news-listing-mosaic.gutter-30 {
            margin-left: -8px;
            margin-right: -8px;
        }
        .news-listing-mosaic.gutter-30 .grid-item {
            padding: 8px;
        }
    }
</style>
@endpush

@section('content')

@php
    use Illuminate\Support\Facades\Storage;

    $pageTitle = filled($newsPageTitle ?? null) ? (string) $newsPageTitle : __('messages.nav.news');
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

<section>
    <div class="container">

        <form method="GET" action="{{ route('news', ['locale' => app()->getLocale()]) }}" class="row g-3 align-items-end mb-4">
            <div class="col-12 col-md-3">
                <label for="news-range" class="form-label mb-1">{{ __('messages.news.filters.date') }}</label>
                <select id="news-range" name="range" class="form-select no-nice-select">
                    <option value="">{{ __('messages.news.filters.all_time') }}</option>
                    <option value="week" @selected(($range ?? '') === 'week')>{{ __('messages.news.filters.last_week') }}</option>
                    <option value="month" @selected(($range ?? '') === 'month')>{{ __('messages.news.filters.last_month') }}</option>
                    <option value="year" @selected(($range ?? '') === 'year')>{{ __('messages.news.filters.last_year') }}</option>
                </select>
            </div>
            <div class="col-12 col-md-5">
                <label for="news-q" class="form-label mb-1">{{ __('messages.news.filters.keyword') }}</label>
                <input id="news-q" type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="{{ __('messages.news.filters.search_placeholder') }}">
            </div>
            <div class="col-12 col-md-4 news-filters-actions">
                <div class="news-filters-actions__buttons">
                    <button type="submit" class="btn">{{ __('messages.news.filters.filter') }}</button>
                    <a href="{{ route('news', ['locale' => app()->getLocale()]) }}" class="btn gray">{{ __('messages.news.filters.clear') }}</a>
                </div>
            </div>
        </form>

        <div class="wptb-project--inner">


            <div class="has-radius effect-tilt">
                <div class="grid gutter-30 clearfix news-listing-mosaic">
                    <div class="grid-sizer"></div>
                    @foreach ($newsSlots ?? [] as $slot)
                        @php
                            $item = $slot['news'];
                            $span = $slot['span'];
                            $itemUrl = route('news.show', ['locale' => app()->getLocale(), 'slug' => $item->slug ?? $item->id]);
                            $imgUrl = $item->cover_photo
                                ? Storage::disk('public')->url($item->cover_photo)
                                : asset('assets/img/blog/' . (((int) (($loop->iteration - 1) % 9)) + 1) . '.jpg');
                            $metaLine = ($item->published_at ?? $item->created_at)->format('d M Y');
                            $coverStyle = 'background-image: url('.json_encode($imgUrl).');';
                        @endphp
                        <div class="grid-item {{ $span }}">
                            <a href="{{ $itemUrl }}" class="wptb-item--inner" aria-label="{{ $item->title }}">
                                <div class="wptb-item--image" style="{{ $coverStyle }}" role="img" aria-label="{{ $item->title }}"></div>

                                <div class="wptb-item--holder">
                                    <div class="wptb-item--meta">
                                        <h4>{{ $item->title }}</h4>
                                        <p>{{ $metaLine }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($news->isEmpty())
            <div class="text-center py-5">
                <p class="mb-0">No results found.</p>
            </div>
        @endif

        {{-- @if($news->hasPages())
            <div class="wptb-pagination-wrap text-center">
                {{ $news->onEachSide(1)->links() }}
            </div>
        @endif --}}

    </div>
</section>

@endsection
