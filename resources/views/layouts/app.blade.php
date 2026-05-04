<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="@yield('meta_description', 'Ef - Photography Agency')">
    <meta name="author" content="">

    <link href="{{ asset('assets/img/favicon.png') }}" rel="shortcut icon" type="image/png">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <title>@yield('title', 'Ef - Photography Agency')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
</head>

@php
    $routeName = request()->route()?->getName() ?? '';
    $routeClass = $routeName !== '' ? ('route-'.str_replace(['.', '/'], '-', $routeName)) : '';
@endphp
<body class="{{ $routeClass }} {{ request()->routeIs('home') ? 'theme-style--olive theme-style--light' : 'theme-style--light' }}">

    <div id="preloader">
        <div class="preloader-inner">
            <div class="spinner" >
                <img style="width: 200px;" src="{{ asset(app()->getLocale() === 'ka' ? 'assets/img/footerlogoKA.svg' : 'assets/img/footerlogo.svg') }}" alt="">
                {{-- <img src="{{ asset('assets/img/preloader-wheel.svg') }}" alt="" class="wheel"> --}}
            </div>
        </div>
    </div>

    <div class="pointer bnz-pointer" id="bnz-pointer"></div>

    @include('partials.header')

    <div class="mr_menu" data-lenis-prevent>
        <button type="button" class="mr_menu_close"><i class="bi bi-x-lg"></i></button>
        <div class="logo"></div>
        <h6>Menu</h6>
        <div class="mr_navmenu"></div>
        <h6>Contact Us</h6>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-envelope"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description"><a href="mailto:info@example.com">info@example.com</a></p>
                </div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-geo-alt"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description"><a href="{{ route('contact') }}">Address</a></p>
                </div>
            </div>
        </div>
        <h6>Find Our Page</h6>
        <div class="social-box">
            <ul>
                <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                <li><a href="#"><i class="bi bi-instagram"></i></a></li>
                <li><a href="#"><i class="bi bi-linkedin"></i></a></li>
            </ul>
        </div>
    </div>

    @include('partials.aside')

    <div class="search-modal">
        <div class="modal fade" id="modalSearch">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="search_overlay">
                        <form class="credential-form" method="get" action="{{ route('news') }}">
                            <div class="form-group">
                                <input type="text" name="q" class="keyword form-control" placeholder="Search Here">
                            </div>
                            <button type="submit" class="btn-search">
                                <span class="text-first"><i class="bi bi-arrow-right"></i></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="wrapper">
        @yield('content')
    </main>

    @include('partials.footer')

    <div class="totop">
        <a href="#"><i class="bi bi-chevron-up"></i></a>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/wow/wow.min.js') }}"></script>
    <script src="{{ asset('plugins/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/swiper/swiper-gl.min.js') }}"></script>
    <script src="{{ asset('plugins/odometer/appear.js') }}"></script>
    <script src="{{ asset('plugins/odometer/odometer.js') }}"></script>
    <script src="{{ asset('plugins/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('plugins/isotope/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('plugins/isotope/modernizr-custom.js') }}"></script>
    <script src="{{ asset('plugins/isotope/jquery.hoverdir.js') }}"></script>
    <script src="{{ asset('plugins/isotope/tilt.jquery.js') }}"></script>
    <script src="{{ asset('plugins/isotope/isotope-init.js') }}"></script>
    <script src="{{ asset('plugins/fancybox/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('plugins/nice-select/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('plugins/cursor-effect/cursor-effect.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    @stack('scripts')
</body>
</html>
