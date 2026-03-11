@props([
    'title' => 'California Fall Fashion Week',
    'subtitle' => 'Fashion Photography',
    'ctaText' => 'Learn Details',
    'ctaUrl' => null,
])
<section class="wptb-slider style16">
    <div class="container">
        <div class="swiper-container wptb-swiper-slider-sixteen">
            <div class="swiper-wrapper">
                @foreach ([45, 46, 47] as $num)
                <div class="swiper-slide">
                    <div class="wptb-slider--item" style="background-image: url('{{ asset('assets/img/slider/' . $num . '.jpg') }}');">
                        <div class="wptb-slider--inner">
                            <div class="wptb-heading">
                                <h6 class="wptb-item--subtitle">{{ $subtitle }}</h6>
                                <h1 class="wptb-item--title">{{ $title }}</h1>
                                <div class="wptb-item--button">
                                    <a class="btn" href="{{ $ctaUrl ?? route('projects') }}">
                                        <span class="btn-wrap">
                                            <span class="text-first">{{ $ctaText }}</span>
                                            <span class="text-second"><span class="line"></span><span class="circle"></span><span class="dot"></span></span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a class="wptb-image-popup" href="{{ asset('assets/img/slider/' . $num . '.jpg') }}" data-fancybox="gallery">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="wptb-left-pane justify-content-center">
        <div class="logo"><h6>Our Works</h6></div>
    </div>
    <div class="wptb-right-pane">
        <div class="social-box style-oval">
            <ul>
                <li><a href="#">FB</a></li>
                <li><a href="#">IG</a></li>
                <li><a href="#">TW</a></li>
                <li><a href="#">YT</a></li>
            </ul>
        </div>
    </div>
    <div class="wptb-bottom-pane justify-content-center">
        <div class="wptb-swiper-dots style2"><div class="swiper-pagination"></div></div>
        <div class="wptb-swiper-navigation style3">
            <div class="wptb-swiper-arrow swiper-button-prev"></div>
            <div class="wptb-swiper-arrow swiper-button-next"></div>
        </div>
    </div>
    <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div>
</section>
