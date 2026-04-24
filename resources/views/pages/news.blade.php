@extends('layouts.app')

@section('title', __('messages.nav.news') . ' - Ef')
@section('meta_description', 'News and blog - Ef Photography Agency')

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

<section class="wptb-blog-grid-one">
    <div class="container">

        <form method="GET" action="{{ route('news', ['locale' => app()->getLocale()]) }}" class="row g-3 align-items-end mb-4">
            <div class="col-12 col-md-4">
                <label for="news-range" class="form-label mb-1">Date</label>
                <select id="news-range" name="range" class="form-select no-nice-select">
                    <option value="">All time</option>
                    <option value="week" @selected(($range ?? '') === 'week')>Last week</option>
                    <option value="month" @selected(($range ?? '') === 'month')>Last month</option>
                    <option value="year" @selected(($range ?? '') === 'year')>Last year</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="news-q" class="form-label mb-1">Keyword</label>
                <input id="news-q" type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search…">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2 news-filters-actions">
                <button type="submit" class="btn flex-fill" style="min-width: auto;">Filter</button>
                <a href="{{ route('news', ['locale' => app()->getLocale()]) }}" class="btn gray flex-fill" style="min-width: auto;">Clear</a>
            </div>
        </form>

        <div class="row">
            @foreach ($news as $item)
            @php
                $itemUrl = route('news.show', ['locale' => app()->getLocale(), 'slug' => $item->slug ?? $item->id]);
                $imgUrl = $item->cover_photo
                    ? Storage::disk('public')->url($item->cover_photo)
                    : asset('assets/img/blog/' . (((int) (($loop->iteration - 1) % 9)) + 1) . '.jpg');
            @endphp
            <div class="col-lg-4 col-sm-6">
                <div class="wptb-blog-grid1 {{ $loop->iteration % 3 === 1 ? 'active highlight' : '' }} wow fadeInLeft">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--image">
                            <a href="{{ $itemUrl }}" class="wptb-item-link"><img src="{{ $imgUrl }}" alt="{{ $item->title }}"></a>
                        </div>
                        <div class="wptb-item--holder">
                            <div class="wptb-item--date">{{ ($item->published_at ?? $item->created_at)->format('d M Y') }}</div>
                            <h4 class="wptb-item--title"><a href="{{ $itemUrl }}">{{ $item->title }}</a></h4>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(($news ?? collect())->isEmpty())
            <div class="text-center py-5">
                <p class="mb-0">No results found.</p>
            </div>
        @endif

    </div>
</section>

@endsection

