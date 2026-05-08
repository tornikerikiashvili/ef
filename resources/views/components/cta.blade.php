@php
    $ctaTextureUrl = asset('assets/img/more/texture-2.svg');
    $homePage = \App\Models\Page::homePageContent();
    $cards = is_array($homePage['contact_cards'] ?? null) ? $homePage['contact_cards'] : [];
    $card0 = is_array($cards[0] ?? null) ? $cards[0] : [];
    $card1 = is_array($cards[1] ?? null) ? $cards[1] : [];
    $card2 = is_array($cards[2] ?? null) ? $cards[2] : [];
@endphp
<section class="wptb-contact-form style1">
    <div
        class="wptb-item-layer both-version"
        style="background-image: url({{ json_encode($ctaTextureUrl) }}); background-repeat: no-repeat; background-position: center; width: 100%; height: 100%;"
        aria-hidden="true"
    ></div>
    <div class="container">
        {{-- <div class="wptb-form--wrapper">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h1 class="wptb-item--title">{{ __('messages.cta.title') }}</h1>
                    <div class="wptb-item--description">{{ __('messages.cta.description') }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <form class="wptb-form" action="#" method="post">
                        @csrf
                        <div class="wptb-form--inner">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 mb-4">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="{{ __('messages.cta.form.name_placeholder') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-4">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="{{ __('messages.cta.form.email_placeholder') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-4">
                                    <div class="form-group">
                                        <input type="text" name="subject" class="form-control" placeholder="{{ __('messages.cta.form.subject_placeholder') }}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 mb-4">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" placeholder="{{ __('messages.cta.form.message_placeholder') }}"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="wptb-item--button text-center">
                                        <button class="btn white-opacity creative text-uppercase" type="submit">
                                            <span class="btn-wrap"><span class="text-first">{{ __('messages.cta.form.submit') }}</span></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        <div class="wptb-office-address mr-top-100">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="wptb-icon-box1 wow fadeInLeft">
                        <div class="wptb-item--inner flex-start">
                            <div class="wptb-item--icon"><i class="bi bi-envelope"></i></div>
                            <div class="wptb-item--holder">
                                <h3 class="wptb-item--title">{{ filled($card0['title'] ?? null) ? $card0['title'] : __('messages.cta.office.website_title') }}</h3>
                                <p class="wptb-item--description">{{ filled($card0['value'] ?? null) ? $card0['value'] : 'www.example.com' }}</p>
                                @php
                                    $href0 = filled($card0['button_link'] ?? null) ? (string) $card0['button_link'] : '#';
                                    $btn0 = filled($card0['button_title'] ?? null) ? (string) $card0['button_title'] : __('messages.cta.office.visit_now');
                                @endphp
                                <a href="{{ $href0 }}" class="wptb-item--link">{{ $btn0 }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 px-md-5">
                    <div class="wptb-icon-box1 wow fadeInLeft">
                        <div class="wptb-item--inner flex-start">
                            <div class="wptb-item--icon"><i class="bi bi-phone"></i></div>
                            <div class="wptb-item--holder">
                                <h3 class="wptb-item--title">{{ filled($card1['title'] ?? null) ? $card1['title'] : __('messages.cta.office.book_us') }}</h3>
                                <p class="wptb-item--description">{{ filled($card1['value'] ?? null) ? $card1['value'] : '+995 555 123 456' }}</p>
                                @php
                                    $href1 = filled($card1['button_link'] ?? null) ? (string) $card1['button_link'] : 'tel:+995555123456';
                                    $btn1 = filled($card1['button_title'] ?? null) ? (string) $card1['button_title'] : __('messages.cta.office.call_now');
                                @endphp
                                <a href="{{ $href1 }}" class="wptb-item--link">{{ $btn1 }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="wptb-icon-box1 wow fadeInLeft">
                        <div class="wptb-item--inner flex-start">
                            <div class="wptb-item--icon"><i class="bi bi-geo-alt"></i></div>
                            <div class="wptb-item--holder">
                                <h3 class="wptb-item--title">{{ filled($card2['title'] ?? null) ? $card2['title'] : __('messages.cta.office.studio_address') }}</h3>
                                <p class="wptb-item--description">{{ filled($card2['value'] ?? null) ? $card2['value'] : __('messages.cta.office.address_placeholder') }}</p>
                                @php
                                    $href2 = filled($card2['button_link'] ?? null) ? (string) $card2['button_link'] : route('contact');
                                    $btn2 = filled($card2['button_title'] ?? null) ? (string) $card2['button_title'] : __('messages.cta.office.view_map');
                                @endphp
                                <a href="{{ $href2 }}" class="wptb-item--link">{{ $btn2 }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
