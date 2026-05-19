@php
    $contact = \App\Models\Page::payloadFor(\App\Models\Page::KEY_CONTACT_PAGE);
    $social = is_array($contact['social'] ?? null) ? $contact['social'] : [];
    $ig = is_array($social['instagram'] ?? null) ? $social['instagram'] : [];

    $igName = (string) ($ig['name'] ?? '');
    $igUrl = (string) ($ig['url'] ?? '');

    $email = (string) ($contact['email'] ?? '');
    $address = (string) ($contact['address'] ?? '');
    $emailHref = filled($email) ? 'mailto:'.$email : '';
    $addressHref = route('contact');

    $homeContact = \App\Models\Page::homeContactCardDetails();
    $phone = $homeContact['phone'];
    $phoneHref = $homeContact['phone_href'];

    $galleryId = $contact['gallery_id'] ?? null;
    $galleryId = ($galleryId !== null && $galleryId !== '' && (int) $galleryId > 0) ? (int) $galleryId : null;
    $galleryImages = [];
    if ($galleryId !== null) {
        $gallery = \App\Models\Gallery::query()->find($galleryId);
        if ($gallery instanceof \App\Models\Gallery) {
            $galleryImages = $gallery->imagePaths();
        }
    }
@endphp

<div class="aside_info_wrapper" data-lenis-prevent>
    <button class="aside_close">{{ __('messages.nav.close') }}<i class="bi bi-x-lg"></i></button>
    <div class="aside_logo logo">
        <a href="{{ route('home') }}" class="light_logo"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo"></a>
        <a href="{{ route('home') }}" class="dark_logo"><img src="{{ asset(app()->getLocale() === 'ka' ? 'assets/img/logomainKA.svg' : 'assets/img/logomain.svg') }}" alt="logo"></a>
    </div>
    <div class="aside_info_inner">
        <h6>{{ __('messages.nav.instagram') }}</h6>
        <div class="insta-logo">
            <i class="bi bi-instagram"></i>
            @if(filled($igUrl))
                <a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer">{{ filled($igName) ? $igName : '@instagram' }}</a>
            @else
                {{ filled($igName) ? $igName : '@instagram' }}
            @endif
        </div>
        <div class="wptb-instagram--gallery">
            <div class="wptb-item--inner d-flex align-items-center justify-content-center flex-wrap">
                @php
                    $images = array_slice(array_reverse($galleryImages), 0, 6);
                @endphp

                @if(! empty($images))
                    @foreach ($images as $path)
                        <div class="wptb-item">
                            <div class="wptb-item--image">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($path) }}" alt="img">
                            </div>
                        </div>
                    @endforeach
                @else
                    @for ($i = 6; $i <= 11; $i++)
                        <div class="wptb-item">
                            <div class="wptb-item--image">
                                <img src="{{ asset('assets/img/instagram/' . $i . '.jpg') }}" alt="img">
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-envelope"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description">
                        @if(filled($email) && filled($emailHref))
                            <a href="{{ $emailHref }}">{{ $email }}</a>
                        @else
                            <a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-geo-alt"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description">
                        @if(filled($address) && filled($addressHref))
                            <a href="{{ $addressHref }}" @if(str_starts_with($addressHref, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $address }}</a>
                        @else
                            <a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-telephone"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description">
                        @if(filled($phone) && filled($phoneHref))
                            <a href="{{ $phoneHref }}">{{ $phone }}</a>
                        @else
                            <a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <h6>{{__('messages.nav.follow_us')}}</h6>
        <div class="social-box style-square">
            <ul>
                @include('partials.social-link-items', ['anchorIcons' => false])
            </ul>
        </div>
    </div>
</div>
