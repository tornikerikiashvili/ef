@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - Ef')
@section('meta_description', 'Project details - Ef Photography Agency')

@section('content')
    <x-hero :title="__('messages.nav.projects')" :subtitle="'Project'" />
    <x-projects-section />
    <x-cta />
@endsection
