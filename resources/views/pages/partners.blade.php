@extends('layouts.app')

@section('title', 'Partners - Ef')
@section('meta_description', 'Our partners - Ef Photography Agency')

@push('styles')
<style>
    .partners-grid-page .grid-item .wptb-item--image {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.06);
        border-radius: 20px;
        overflow: hidden;
        padding: 28px;
        aspect-ratio: 16 / 9;
    }
    .partners-grid-page .grid-item .wptb-item--image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .partners-grid-page .project_grid_item .wptb-item--meta h4 {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ $headerBg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
        </div>
        <h2 class="wptb-item--title">{{ $pageTitle ?? 'Partners' }}</h2>
    </div>
</div>

<section class="partners-grid-page">
    <div class="container">
        <div class="wptb-project--inner">
            <div class="has-radius effect-tilt">
                <div class="grid grid-3 gutter-30 clearfix">
                    <div class="grid-sizer"></div>

                    @foreach ($partnerLogos as $partner)
                        @php
                            $logo = filled($partner->logo_colorful)
                                ? Storage::disk('public')->url($partner->logo_colorful)
                                : null;

                            $title = $partner->title ?: 'Partner';
                            $href = filled($partner->link) ? $partner->link : null;
                        @endphp

                        @continue(! $logo)

                        <div class="grid-item project_grid_item">
                            @if($href)
                                <a class="wptb-item--inner" href="{{ $href }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $title }}">
                            @else
                                <div class="wptb-item--inner" aria-label="{{ $title }}">
                            @endif
                                    <div class="wptb-item--image">
                                        <img src="{{ $logo }}" alt="{{ $title }}" loading="lazy" decoding="async">
                                    </div>
                                    <div class="wptb-item--holder">
                                        <div class="wptb-item--meta">
                                            <h4>{{ $title }}</h4>
                                        </div>
                                    </div>
                            @if($href)
                                </a>
                            @else
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

