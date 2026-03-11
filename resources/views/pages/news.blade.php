@extends('layouts.app')

@section('title', __('messages.nav.news') . ' - Ef')
@section('meta_description', 'News and blog - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.news')" :subtitle="'Latest News'" />
    <x-blog-section />
    <x-cta />
@endsection
