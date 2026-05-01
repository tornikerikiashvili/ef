@extends('layouts.app')

@section('title', __('messages.nav.about') . ' - Ef')
@section('meta_description', 'About us - Ef Photography Agency')

@section('content')

    <!-- Page Header -->
    @php
        $coverTitle = data_get($aboutPage ?? [], 'cover.title');
        $coverTitle = filled($coverTitle) ? (string) $coverTitle : 'About Us';

        $coverImage = data_get($aboutPage ?? [], 'cover.background_image');
        $coverImageUrl = filled($coverImage)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($coverImage)
            : asset('assets/img/background/page-header-bg-4.jpg');
    @endphp
    <div class="wptb-page-heading">
        <div class="wptb-item--inner" style="background-image: url('{{ $coverImageUrl }}');">
            <div class="wptb-item-layer wptb-item-layer-one">
                <img src="../assets/img/more/circle.png" alt="img">
            </div>
            <h2 class="wptb-item--title">{{ $coverTitle }}</h2>
        </div>
    </div>

    <!-- About Kimono -->
    @php
        $aboutTeaser = data_get($aboutPage ?? [], 'about.teaser');
        $aboutTeaser = filled($aboutTeaser)
            ? (string) $aboutTeaser
            : 'Kimono photography Agency runs wide and deep. Across many markets, geographies & typologies, our team members';

        $aboutDescription = data_get($aboutPage ?? [], 'about.description');
        $aboutDescription = filled($aboutDescription)
            ? (string) $aboutDescription
            : 'The talent at kimono runs wide range of services. Across many markets, geographies & typologies, our team members are some of the finest people of  photographers in the industry wide and deep. From Across many markets, geographies & boundaries. Hire Kimono in your event.';

        $aboutImageLeft = data_get($aboutPage ?? [], 'about.image_left');
        $aboutImageLeftUrl = filled($aboutImageLeft)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($aboutImageLeft)
            : asset('assets/img/more/1.jpg');

        $aboutImageRight = data_get($aboutPage ?? [], 'about.image_right');
        $aboutImageRightUrl = filled($aboutImageRight)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($aboutImageRight)
            : asset('assets/img/more/2.jpg');

        $funfactLabel1 = data_get($aboutPage ?? [], 'funfacts.label_1');
        $funfactLabel1 = filled($funfactLabel1) ? (string) $funfactLabel1 : 'Customer Satisfaction';

        $funfactValue1 = data_get($aboutPage ?? [], 'funfacts.value_1');
        $funfactValue1 = is_numeric($funfactValue1) ? (int) $funfactValue1 : 100;

        $funfactLabel2 = data_get($aboutPage ?? [], 'funfacts.label_2');
        $funfactLabel2 = filled($funfactLabel2) ? (string) $funfactLabel2 : 'Projects Done';

        $funfactValue2 = data_get($aboutPage ?? [], 'funfacts.value_2');
        $funfactValue2 = is_numeric($funfactValue2) ? (int) $funfactValue2 : 350;

        $presidentTitle = data_get($aboutPage ?? [], 'president.title');
        $presidentTitle = filled($presidentTitle) ? (string) $presidentTitle : 'Why Choose Us';

        $presidentYears = data_get($aboutPage ?? [], 'president.years_experience');
        $presidentYears = is_numeric($presidentYears) ? max(0, (int) $presidentYears) : 15;

        $presidentImage = data_get($aboutPage ?? [], 'president.image');
        $presidentImageUrl = filled($presidentImage)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($presidentImage)
            : asset('assets/img/more/3.png');

        $presidentContent = data_get($aboutPage ?? [], 'president.content', '');
        $presidentContent = is_string($presidentContent) ? $presidentContent : '';

        $aboutVideoUrl = data_get($aboutPage ?? [], 'video.url');
        $aboutVideoUrl = filled($aboutVideoUrl) ? (string) $aboutVideoUrl : 'https://www.youtube.com/watch?v=SF4aHwxHtZ0';

        $aboutVideoBg = data_get($aboutPage ?? [], 'video_background_image');
        $aboutVideoBgUrl = filled($aboutVideoBg)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($aboutVideoBg)
            : asset('assets/img/background/bg-7.jpg');
    @endphp
    <section class="wptb-about-one bg-image-2" style="background-image: url('../assets/img/more/texture.png');">
        <div class="container">

            <div class="row">
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="wptb-image-single wow fadeInUp">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--image">
                                        <img src="{{ $aboutImageLeftUrl }}" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-0 mt-5">
                            <div class="wptb-about--text">
                                <p class="about_teaser wptb-about--text-one mb-4">{{ $aboutTeaser }}</p>
                                <div class="about_description">{!! $aboutDescription !!}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row wptb-about-funfact">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="wptb-counter1 style1 pd-right-60 wow skewIn">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--holder d-flex align-items-center">
                                        <div class="wptb-item--value"><span class="odometer" data-count="{{ $funfactValue1 }}"></span><span class="suffix">%</span></div>
                                        <div class="wptb-item--text">{{ $funfactLabel1 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wptb-counter1 style1 pd-right-60 wow skewIn">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--holder d-flex align-items-center">
                                        <div class="wptb-item--value flex-shrink-0"><span class="odometer" data-count="{{ $funfactValue2 }}"></span><span class="suffix">+</span></div>
                                        <div class="wptb-item--text">{{ $funfactLabel2 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 ps-xl-5 mt-5 mt-xl-0 d-none d-xl-block">
                    <div class="wptb-image-single wow fadeInUp">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <img src="{{ $aboutImageRightUrl }}" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wptb-item-layer wptb-item-layer-one">
                <img src="../assets/img/more/light-1.png" alt="img">
            </div>
        </div>
    </section>

    <!-- President -->
    <section class="wptb-faq-one bg-image pb-0" style="background-image: url('../assets/img/background/bg-8.jpg');">
        <div class="container">

            <div class="row">
                <div class="col-lg-6">
                    <div class="wptb-heading">
                        <div class="wptb-item--inner">
                            <h1 class="wptb-item--title mb-lg-0">{{ $presidentTitle }}</h1>
                        </div>
                    </div>

                    @if (filled(trim(strip_tags($presidentContent))))
                        <div class="wptb-president-copy wow fadeInUp text-white president-rich-text">
                            {!! $presidentContent !!}
                        </div>
                    @endif

                    <div class="wptb-agency-experience--item text-white">
                        <span>{{ $presidentYears }}+</span> {{ __('messages.years_experience') }}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="wptb-image-single wow fadeInUp">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <img src="{{ $presidentImageUrl }}" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- BG Video -->
    <div class="container mr-top-100" >
        <div class="wptb-video-player1 wow zoomIn" style="background-image: url('{{ $aboutVideoBgUrl }}');">
            <div class="wptb-item--inner">
                <div class="wptb-item--holder">
                    <div class="wptb-item--video-button">
                        <a class="btn" data-fancybox href="{{ $aboutVideoUrl }}">
                            <span class="text-second"> <i class="bi bi-play-fill"></i> </span>
                            <span class="line-video-animation line-video-1"></span>
                            <span class="line-video-animation line-video-2"></span>
                            <span class="line-video-animation line-video-3"></span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="wptb-item-layer wptb-item-layer-one">
                <img src="../assets/img/more/light-3.png" alt="img">
            </div>
        </div>
    </div>

    <div class="divider-line-hr mr-top-100"></div>


    <!-- Awards -->
    {{-- <section class="bg-dark-200 pd-bottom-80">
        <div class="container">
            <div class="wptb-heading">
                <div class="wptb-item--inner">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="wptb-item--subtitle"><span>02 //</span> Our Awards</h6>
                            <h1 class="wptb-item--title mb-0">Our Photography<br>
                                <span>Awards</span></h1>
                        </div>

                        <div class="col-lg-6">
                            <p class="wptb-item--description">we’re deeply passionate <span>catch your lovely memories in cameras</span>
                                and Convey your love for every moment of life as a whole.</p>
                        </div>
                    </div>
                </div>
            </div>

            <ol class="wptb-award-list">
                <li class="wptb-item">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--holder">
                            <a href="project-details.html">Photography Team of the Year 2023</a>
                        </div>
                        <div class="wptb-item--image">
                            <img src="../assets/img/more/4.jpg" alt="img">
                            <div class="wptb-item--button">
                                <a href="project-details.html" class="btn">View</a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="wptb-item active highlight">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--holder">
                            <a href="project-details.html">Best Wedding Photographer 2022</a>
                        </div>
                        <div class="wptb-item--image">
                            <img src="../assets/img/more/5.jpg" alt="img">
                            <div class="wptb-item--button">
                                <a href="project-details.html" class="btn">View</a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="wptb-item">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--holder">
                            <a href="project-details.html">Photography Team of the Year 2019</a>
                        </div>
                        <div class="wptb-item--image">
                            <img src="../assets/img/more/6.jpg" alt="img">
                            <div class="wptb-item--button">
                                <a href="project-details.html" class="btn">View</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ol>
        </div>
    </section> --}}

@endsection
