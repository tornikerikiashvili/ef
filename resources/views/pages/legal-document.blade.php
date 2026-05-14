@extends('layouts.app')

@section('title', $pageTitle.' - Ef')

@section('meta_description')
{{ \Illuminate\Support\Str::limit(strip_tags($body ?? ''), 160) ?: __('messages.legal.meta_fallback') }}
@endsection

@section('content')
    <section class="blog-details pd-top-100 pd-bottom-100">
        <div class="container">
            <div class="post-header">
                <h1 class="post-title">{{ $pageTitle }}</h1>
            </div>
            <div class="fulltext legal-document__body">
                @if (filled(trim(strip_tags($body))))
                    {!! $body !!}
                @else
                    <p>{{ __('messages.legal.empty') }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
