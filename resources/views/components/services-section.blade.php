<section class="wptb-services">
    <div class="wptb-slider-divider--bg"></div>
    <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div>
    <div class="container">
        <div class="wptb-heading">
            <div class="wptb-item--inner">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h6 class="wptb-item--subtitle"><span>02 //</span> {{ __('messages.nav.services') }}</h6>
                        <h1 class="wptb-item--title">Our Photography <br><span>Services</span></h1>
                    </div>
                    <div class="col-lg-6">
                        <p class="wptb-item--description">{{ $description ?? "We're deeply passionate catch your lovely memories in cameras and convey your love for every moment of life as a whole." }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-15.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-15-4.svg') }}" alt="" class="hover-icon">
                        </div>
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">Wedding Photography</a></h4>
                            <p class="wptb-item--description">The talent at kimono runs wide range of services. Across many markets, geographies.</p>
                            <h6 class="wptb-item--count text-outline">01</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0 active highlight">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-16.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-16-4.svg') }}" alt="" class="hover-icon">
                        </div>
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">Wedding Cinematography</a></h4>
                            <p class="wptb-item--description">The talent at kimono runs wide range of services. Across many markets, geographies.</p>
                            <h6 class="wptb-item--count text-outline">02</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-17.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-17-4.svg') }}" alt="" class="hover-icon">
                        </div>
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">Portfolio Photography</a></h4>
                            <p class="wptb-item--description">The talent at kimono runs wide range of services. Across many markets, geographies.</p>
                            <h6 class="wptb-item--count text-outline">03</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
