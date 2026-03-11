<div class="aside_info_wrapper" data-lenis-prevent>
    <button class="aside_close">Close <i class="bi bi-x-lg"></i></button>
    <div class="aside_logo logo">
        <a href="{{ route('home') }}" class="light_logo"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo"></a>
        <a href="{{ route('home') }}" class="dark_logo"><img src="{{ asset('assets/img/logo-dark.svg') }}" alt="logo"></a>
    </div>
    <div class="aside_info_inner">
        <h6>// Instagram</h6>
        <div class="insta-logo"><i class="bi bi-instagram"></i> studio</div>
        <div class="wptb-instagram--gallery">
            <div class="wptb-item--inner d-flex align-items-center justify-content-center flex-wrap">
                @for ($i = 6; $i <= 11; $i++)
                <div class="wptb-item">
                    <div class="wptb-item--image">
                        <img src="{{ asset('assets/img/instagram/' . $i . '.jpg') }}" alt="img">
                    </div>
                </div>
                @endfor
            </div>
        </div>
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
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-telephone"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description"><a href="tel:+995555123456">+995 555 123 456</a></p>
                </div>
            </div>
        </div>
        <h6>// Follow Us</h6>
        <div class="social-box style-square">
            <ul>
                <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                <li><a href="#"><i class="bi bi-instagram"></i></a></li>
                <li><a href="#"><i class="bi bi-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
</div>
