@php
    $pathWithoutLocale = implode('/', array_slice(request()->segments(), 1));
    $isEnglish = app()->getLocale() === 'en';
    $targetLocale = $isEnglish ? 'ka' : 'en';
    $langSwitchUrl = url($targetLocale.($pathWithoutLocale ? '/'.$pathWithoutLocale : ''));
    $langSwitchLabel = $isEnglish
        ? __('messages.lang_abbr_georgian')
        : __('messages.lang_abbr_english');
    $langSwitchTitle = $isEnglish ? __('messages.georgian') : __('messages.english');
@endphp
<header class="header">
    <div class="header-inner">
        <div class="container-fluid pe-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="header_left_part d-flex align-items-center">
                    <div class="logo">
                        <a href="{{ route('home') }}" class="light_logo"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo"></a>
                        <a href="{{ route('home') }}" class="dark_logo"><img src="{{ asset('assets/img/logomain.svg') }}" alt="logo"></a>
                    </div>
                </div>

                <div class="header_center_part d-none d-xl-block">
                    <div class="mainnav">
                        <ul class="main-menu">
                            <li class="menu-item"><a href="{{ route('home') }}">{{ __('messages.nav.home') }}</a></li>
                            <li class="menu-item"><a href="{{ route('about') }}">{{ __('messages.nav.about') }}</a></li>
                            <li class="menu-item"><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a>
                                {{-- <ul class="sub-menu" data-lenis-prevent>
                                    <li class="menu-item"><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a></li>
                                </ul> --}}
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
                        <a href="{{ $langSwitchUrl }}" class="modal_search_icon lang-switch-trigger" title="{{ $langSwitchTitle }}" aria-label="{{ $langSwitchTitle }}"><span class="lang-switch-trigger__text">{{ $langSwitchLabel }}</span></a>
                    </div>
                    <button type="button" class="mr_menu_toggle wptb-element d-xl-none">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
