@extends('layouts.app')

@section('title', __('messages.nav.news') . ' - Ef')
@section('meta_description', 'News and blog - Ef Photography Agency')

@section('content')
    <x-blog-section />
    <x-cta />
@endsection
