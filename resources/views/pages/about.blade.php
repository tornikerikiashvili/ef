@extends('layouts.app')

@section('title', __('messages.nav.about') . ' - Ef')
@section('meta_description', 'About us - Ef Photography Agency')

@section('content')
    <x-about-section />
    <x-funfacts />
    <x-cta />
@endsection
