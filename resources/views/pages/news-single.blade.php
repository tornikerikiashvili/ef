@extends('layouts.app')

@section('title', __('messages.nav.news') . ' - Ef')
@section('meta_description', 'Article - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.news')" :subtitle="'Article'" />
    <x-blog-section />
    <x-cta />
@endsection
