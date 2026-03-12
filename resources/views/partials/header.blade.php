@php
    $pathWithoutLocale = implode('/', array_slice(request()->segments(), 1));
@endphp
<header class="header">
    <div class="header-inner">
        <div class="container-fluid pe-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="header_left_part d-flex align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}" class="light_logo"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo"></a>
                        <a href="{{ route('home') }}" class="dark_logo"><img src="{{ asset('assets/img/logo-dark.svg') }}" alt="logo"></a>
                    </div>
                </div>

                <div class="header_center_part d-none d-xl-block">
                    <div class="mainnav">
                        <ul class="main-menu">
                            <li class="menu-item"><a href="{{ route('home') }}">{{ __('messages.nav.home') }}</a></li>
                            <li class="menu-item"><a href="{{ route('about') }}">{{ __('messages.nav.about') }}</a></li>
                            <li class="menu-item menu-item-has-children"><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a>
                                <ul class="sub-menu" data-lenis-prevent>
                                    <li class="menu-item"><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a></li>
                                </ul>
                            </li>
                            <li class="menu-item"><a href="{{ route('projects') }}">{{ __('messages.nav.projects') }}</a></li>
                            <li class="menu-item"><a href="{{ route('news') }}">{{ __('messages.nav.news') }}</a></li>
                            <li class="menu-item"><a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="header_right_part d-flex align-items-center">
                    <div class="aside_open wptb-element">
                        <div class="aside-open--inner">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <div class="header_search wptb-element">
                        <a href="#" class="modal_search_icon" data-bs-toggle="modal" data-bs-target="#modalLanguage" title="{{ __('messages.language') }}"><i class="bi bi-globe"></i></a>
                    </div>
                    <button type="button" class="mr_menu_toggle wptb-element d-xl-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="modalLanguage" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">{{ __('messages.language') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ url('en' . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '')) }}" class="btn btn-outline-secondary">{{ __('messages.english') }}</a>
                    <a href="{{ url('ka' . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '')) }}" class="btn btn-outline-secondary">{{ __('messages.georgian') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
