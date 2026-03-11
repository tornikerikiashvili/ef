@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - Ef')
@section('meta_description', 'Our projects - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.projects')" :subtitle="'Portfolio'" />
    <x-projects-section />
    <x-cta />
@endsection
