<footer class="footer style1 bg-image-2" style="background-image: url('{{ asset('assets/img/background/bg-5.png') }}');">
    @php
        $homeSettings = \App\Models\Page::homePageContent();
        $homeMenuTitle = (string) ($homeSettings['menu_title'] ?? '');
        $homePageTitle = (string) ($homeSettings['title'] ?? '');

        $aboutSettings = \App\Models\Page::aboutPageContent();
        $aboutMenuTitle = (string) ($aboutSettings['menu']['title'] ?? '');
        $aboutPageTitle = (string) ($aboutSettings['cover']['title'] ?? '');

        $servicesSettings = \App\Models\Page::servicesListingPageContent();
        $servicesMenuTitle = (string) ($servicesSettings['menu_title'] ?? '');
        $servicesPageTitle = (string) ($servicesSettings['title'] ?? '');

        $projectsSettings = \App\Models\Page::projectsListingPageContent();
        $projectsMenuTitle = (string) ($projectsSettings['menu_title'] ?? '');
        $projectsPageTitle = (string) ($projectsSettings['title'] ?? '');

        $newsSettings = \App\Models\Page::newsListingPageContent();
        $newsMenuTitle = (string) ($newsSettings['menu_title'] ?? '');
        $newsPageTitle = (string) ($newsSettings['title'] ?? '');

        $contactSettings = \App\Models\Page::payloadFor(\App\Models\Page::KEY_CONTACT_PAGE);
        $contactMenuTitle = (string) ($contactSettings['menu_title'] ?? '');
        $contactPageTitle = (string) ($contactSettings['title'] ?? '');
    @endphp
    <div class="footer-top">
        <div class="container">
            <div class="footer--inner">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget">
                            <div class="footer-nav">
                                <ul>
                                    <li class="menu-item"><a href="{{ route('home') }}">{{ filled($homeMenuTitle) ? $homeMenuTitle : (filled($homePageTitle) ? $homePageTitle : __('messages.nav.home')) }}</a></li>
                                    <li class="menu-item"><a href="{{ route('about') }}">{{ filled($aboutMenuTitle) ? $aboutMenuTitle : (filled($aboutPageTitle) ? $aboutPageTitle : __('messages.nav.about')) }}</a></li>
                                    <li class="menu-item"><a href="{{ route('services') }}">{{ filled($servicesMenuTitle) ? $servicesMenuTitle : (filled($servicesPageTitle) ? $servicesPageTitle : __('messages.nav.services')) }}</a></li>
                                    <li class="menu-item"><a href="{{ route('projects') }}">{{ filled($projectsMenuTitle) ? $projectsMenuTitle : (filled($projectsPageTitle) ? $projectsPageTitle : __('messages.nav.projects')) }}</a></li>
                                    <li class="menu-item"><a href="{{ route('news') }}">{{ filled($newsMenuTitle) ? $newsMenuTitle : (filled($newsPageTitle) ? $newsPageTitle : __('messages.nav.news')) }}</a></li>
                                    <li class="menu-item"><a href="{{ route('contact') }}">{{ filled($contactMenuTitle) ? $contactMenuTitle : (filled($contactPageTitle) ? $contactPageTitle : __('messages.nav.contact')) }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 mb-5 mb-md-0 order-1 order-md-0">
                        <div class="footer-widget text-center">
                            <div class="logo mr-bottom-55">
                                <a href="{{ route('home') }}"><img src="{{ asset(app()->getLocale() === 'ka' ? 'assets/img/footerlogoKA.svg' : 'assets/img/footerlogo.svg') }}" alt="logo"></a>
                            </div>
                            <h6 class="widget-title">{{ __('messages.footer.newsletter_title') }}</h6>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget text-md-end">
                            <div class="footer-nav">
                                <ul>
                                    <li class="menu-item"><a href="{{ route('news') }}">{{ __('messages.footer.recent_posts') }}</a></li>
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
                    <p>{{ __('messages.footer.copyright') }}</p>
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
