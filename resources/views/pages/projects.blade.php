@extends('layouts.app')

@section('title', __('messages.nav.projects') . ' - Ef')
@section('meta_description', 'Our projects - Ef Photography Agency')

@section('content')
    <x-projects-listing :projects="$projects" :categories="$categories" />
    <x-cta />
@endsection
