@extends('layouts.app')

@section('title', ($newsItem->title ?? __('messages.nav.news')) . ' - Ef')
@section('meta_description', $newsItem->teaser ? \Illuminate\Support\Str::limit(strip_tags($newsItem->teaser), 160) : 'Article - Ef Photography Agency')

@section('content')
@php
    $coverUrl = $newsItem->cover_photo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($newsItem->cover_photo)
        : asset('assets/img/blog/details.jpg');
@endphp
<section class="blog-details">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="blog-details-inner">
                    <div class="post-content">
                        <div class="post-header">
                            <h2 class="post-title mb-2">{{ $newsItem->title }}</h2>
                            <div class="wptb-item--meta d-flex align-items-center gap-4">
                                <div class="wptb-item">
                                    <a href="{{ route('news', ['locale' => app()->getLocale()]) }}">
                                        <i class="bi bi-arrow-left"></i> <span>{{ __('messages.back') ?? 'Back' }}</span>
                                    </a>
                                </div>
                                <div class="wptb-item wptb-item--date"><a href="#"><i class="bi bi-calendar3"></i> <span>{{ ($newsItem->published_at ?? $newsItem->created_at)->format('F d, Y') }}</span></a></div>
                            </div>
                        </div>

                        @if($newsItem->teaser)
                        <div class="intro">
                            <p>{{ $newsItem->teaser }}</p>
                        </div>
                        @endif

                        <!-- Post Image -->
                        <figure
                            class="block-gallery mt-4 blog-details__cover"
                            role="img"
                            aria-label="{{ $newsItem->title }}"
                            style="background-image: url('{{ $coverUrl }}');"
                        ></figure>

                        <div class="fulltext">
                            @if($newsItem->text_content)
                                {!! $newsItem->text_content !!}
                            @elseif($newsItem->teaser)
                                <p>{{ $newsItem->teaser }}</p>
                            @endif
                        </div>

                        <div class="post-footer">
                            <div class="post-share">
                                @php
                                    $shareUrl = urlencode(request()->fullUrl());
                                    $shareTitle = urlencode((string) ($newsItem->title ?? ''));
                                    $facebookShare = "https://www.facebook.com/sharer/sharer.php?u={$shareUrl}&quote={$shareTitle}";
                                @endphp
                                <ul class="share-list share-list--icon-only">
                                    <li class="share-label">Share:</li>
                                    <li class="share-item share-item--facebook">
                                        <a href="{{ $facebookShare }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
                                            <i class="bi bi-facebook"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
