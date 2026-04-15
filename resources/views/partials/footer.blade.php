<footer class="footer style1 bg-image-2" style="background-image: url('{{ asset('assets/img/background/bg-5.png') }}');">
    <div class="footer-top">
        <div class="container">
            <div class="footer--inner">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget">
                            <div class="footer-nav">
                                <ul>
                                    <li class="menu-item"><a href="{{ route('about') }}">{{ __('messages.nav.about') }}</a></li>
                                    <li class="menu-item"><a href="{{ route('services') }}">{{ __('messages.nav.services') }}</a></li>
                                    <li class="menu-item"><a href="{{ route('projects') }}">{{ __('messages.nav.projects') }}</a></li>
                                    <li class="menu-item"><a href="{{ route('news') }}">{{ __('messages.nav.news') }}</a></li>
                                    <li class="menu-item"><a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 mb-5 mb-md-0 order-1 order-md-0">
                        <div class="footer-widget text-center">
                            <div class="logo mr-bottom-55">
                                <a href="{{ route('home') }}"><img src="{{ asset('assets/img/footerlogo.svg') }}" alt="logo"></a>
                            </div>
                            <h6 class="widget-title">Sign up for all the latest news and offers</h6>
                            <form class="newsletter-form" method="post" action="#">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                </div>
                                <button type="submit" class="btn btn-two">
                                    <span class="btn-wrap">
                                        <span class="text-first">Subscribe</span>
                                        <span class="text-second"><i class="bi bi-arrow-up-right"></i></span>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget text-md-end">
                            <div class="footer-nav">
                                <ul>
                                    <li class="menu-item"><a href="{{ route('news') }}">Recent Posts</a></li>
                                    <li class="menu-item"><a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="copyright">
                    <p>Ef photography, All Rights Reserved</p>
                </div>
                <div class="social-box style-oval">
                    <ul>
                        <li><a href="#" class="bi bi-facebook"></a></li>
                        <li><a href="#" class="bi bi-instagram"></a></li>
                        <li><a href="#" class="bi bi-linkedin"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
