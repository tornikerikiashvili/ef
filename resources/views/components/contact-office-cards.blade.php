@props([
    'cards' => null,
    'mapFallbackHref' => null,
])
@php
    if ($cards === null) {
        $homePage = \App\Models\Page::homePageContent();
        $cards = is_array($homePage['contact_cards'] ?? null) ? $homePage['contact_cards'] : [];
    }

    $card0 = is_array($cards[0] ?? null) ? $cards[0] : [];
    $card1 = is_array($cards[1] ?? null) ? $cards[1] : [];
    $card2 = is_array($cards[2] ?? null) ? $cards[2] : [];

    $mapFallbackHref = filled($mapFallbackHref) ? (string) $mapFallbackHref : route('contact');
@endphp
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
                            $href2 = filled($card2['button_link'] ?? null) ? (string) $card2['button_link'] : $mapFallbackHref;
                            $btn2 = filled($card2['button_title'] ?? null) ? (string) $card2['button_title'] : __('messages.cta.office.view_map');
                        @endphp
                        <a href="{{ $href2 }}" class="wptb-item--link" @if(str_starts_with($href2, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $btn2 }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
