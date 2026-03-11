@extends('layouts.app')

@section('title', __('messages.nav.contact') . ' - Ef')
@section('meta_description', 'Contact us - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.contact')" :subtitle="'Get In Touch'" />
    <x-cta />
@endsection
