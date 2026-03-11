@extends('layouts.app')

@section('title', __('messages.nav.services') . ' - Ef')
@section('meta_description', 'Our services - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.services')" :subtitle="'What We Offer'" />
    <x-services-section />
    <x-cta />
@endsection
