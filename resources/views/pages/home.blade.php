@extends('layouts.app')

@section('title', 'Ef - Photography Agency')
@section('meta_description', 'Ef - Photography Agency')

@section('content')
    <x-hero :services="$featuredServices" />
    <x-services-section
        :title="$homePage['about']['title']"
        :text="$homePage['about']['text']"
        :image="$homePage['about']['image']"
        :link="$homePage['about']['link']"
    />
    {{-- <x-about-section
        :title="$homePage['about']['title']"
        :text="$homePage['about']['text']"
        :image="$homePage['about']['image']"
        :link="$homePage['about']['link']"
    /> --}}
    <x-partners :partnerLogos="$partnerLogos" />
    <x-projects-section
        :projects="$featuredProjects"
        :section-title="$homePage['projects_section']['title']"
    />

    {{-- <x-funfacts /> --}}
    {{-- <x-news-section
        :news="$featuredNews"
        :section-title="$homePage['news_section']['title']"
        :section-teaser="$homePage['news_section']['teaser']"
    /> --}}
    @if ($homePage['show_contact_form'])
        <x-cta />
    @endif
    <x-instagram-gallery
        :images="data_get($homePage, 'gallery.images', [])"
        :instagram-url="$homePage['gallery_instagram_link'] ?? ''"
    />
@endsection
