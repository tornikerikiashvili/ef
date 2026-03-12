@extends('layouts.app')

@section('title', __('messages.nav.services') . ' - Ef')
@section('meta_description', 'Service details - Ef Photography Agency')

@section('content')
    <x-services-section />
    <x-cta />
@endsection
