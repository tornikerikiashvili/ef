@extends('layouts.app')

@section('title', 'Ef - Photography Agency')
@section('meta_description', 'Ef - Photography Agency')

@section('content')
    <x-hero :services="$featuredServices" />
    <x-services-section />
    <x-about-section />

    <x-projects-section :projects="$featuredProjects" />
    <x-partners :partnerLogos="$partnerLogos" />
    {{-- <x-funfacts /> --}}
    <x-news-section :news="$featuredNews" />
    <x-cta />
    <x-instagram-gallery />
@endsection
