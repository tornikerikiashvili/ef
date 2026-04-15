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
                        {{-- <h6 class="wptb-item--subtitle"><span>02 //</span> {{ __('messages.nav.services') }}</h6> --}}
                        <h1 class="wptb-item--title"><span>About Company</span></h1>
                    </div>
                    <div class="col-lg-6">
                        <p class="wptb-item--description">{{ $description ?? "Quality and international standards. Full-service from design to premium fit-out for every project." }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0">
                    <div class="wptb-item--inner">
                        {{-- <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-15.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-15-4.svg') }}" alt="" class="hover-icon">
                        </div> --}}
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">Design & Fit-Out</a></h4>
                            <p class="wptb-item--description">Full-service from concept to completion: design, interior design, and premium fit-out works.</p>
                            <div class="wptb-item--count-wrap d-flex justify-content-end mt-3" style="margin-top:15px;">
                                <a href="{{ route('services') }}" class="wptb-item--count wptb-item--count--arrow" aria-label="View services">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0 active highlight">
                    <div class="wptb-item--inner">
                        {{-- <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-16.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-16-4.svg') }}" alt="" class="hover-icon">
                        </div> --}}
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">International Standards</a></h4>
                            <p class="wptb-item--description">International brand guidelines, local and global standards, and best practices in every project.</p>
                            <div class="wptb-item--count-wrap d-flex justify-content-end mt-3" style="margin-top:15px;">
                                <a href="{{ route('services') }}" class="wptb-item--count wptb-item--count--arrow" aria-label="View services">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 wow fadeInLeft">
                <div class="wptb-icon-box7 mb-0">
                    <div class="wptb-item--inner">
                        {{-- <div class="wptb-item--icon">
                            <img src="{{ asset('assets/img/services/icon-17.svg') }}" alt="" class="default-icon">
                            <img src="{{ asset('assets/img/services/icon-17-4.svg') }}" alt="" class="hover-icon">
                        </div> --}}
                        <div class="wptb-item--holder">
                            <h4 class="wptb-item--title"><a href="{{ route('services') }}">ELEMENT HOLDING</a></h4>
                            <p class="wptb-item--description">Over 10 companies in industrial, hydrotechnical and civil construction since 2007.</p>
                            <div class="wptb-item--count-wrap d-flex justify-content-end mt-3" style="margin-top:15px;">
                                <a href="{{ route('services') }}" class="wptb-item--count wptb-item--count--arrow" aria-label="View services">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
