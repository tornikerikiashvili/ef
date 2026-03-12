@extends('layouts.app')

@section('title', 'Ef - Photography Agency')
@section('meta_description', 'Ef - Photography Agency')

@section('content')
    <x-hero :services="$featuredServices" />
    <x-services-section />
    <x-about-section />
    <x-partners />
    <x-projects-section :projects="$featuredProjects" />
    <x-blog-section />
    <x-cta />
    <x-instagram-gallery />
@endsection
