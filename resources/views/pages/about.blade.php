@extends('layouts.app')

@section('title', __('messages.nav.about') . ' - Ef')
@section('meta_description', 'About us - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.about')" :subtitle="'About Agency'" />
    <x-about-section />
    <x-funfacts />
    <x-cta />
@endsection
