@extends('layouts.app')

@section('title', 'Ef - Photography Agency')
@section('meta_description', 'Ef - Photography Agency')

@section('content')
    <x-hero :services="$featuredServices" />
    <x-services-section />
    <x-about-section />
    <x-partners :partnerLogos="$partnerLogos" />
    <x-projects-section :projects="$featuredProjects" />
    <x-news-section :news="$featuredNews" />
    <x-cta />
    <x-instagram-gallery />
@endsection
