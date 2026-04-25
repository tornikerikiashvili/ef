@php
    $contact = \App\Models\Page::payloadFor(\App\Models\Page::KEY_CONTACT_PAGE);
    $social = is_array($contact['social'] ?? null) ? $contact['social'] : [];
    $ig = is_array($social['instagram'] ?? null) ? $social['instagram'] : [];

    $igName = (string) ($ig['name'] ?? '');
    $igUrl = (string) ($ig['url'] ?? '');
    $facebookUrl = (string) ($social['facebook_url'] ?? '');
    $linkedinUrl = (string) ($social['linkedin_url'] ?? '');

    $email = (string) ($contact['email'] ?? '');
    $phone = (string) ($contact['phone'] ?? '');
    $address = (string) ($contact['address'] ?? '');

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
    <button class="aside_close">Close <i class="bi bi-x-lg"></i></button>
    <div class="aside_logo logo">
        <a href="{{ route('home') }}" class="light_logo"><img src="{{ asset('assets/img/logo.svg') }}" alt="logo"></a>
        <a href="{{ route('home') }}" class="dark_logo"><img src="{{ asset('assets/img/logo-dark.svg') }}" alt="logo"></a>
    </div>
    <div class="aside_info_inner">
        <h6>// Instagram</h6>
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
                        @if(filled($email))
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
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
                        <a href="{{ route('contact') }}">{{ filled($address) ? $address : 'Address' }}</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-telephone"></i></div>
                <div class="wptb-item--holder">
                    <p class="wptb-item--description">
                        @if(filled($phone))
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>
                        @else
                            <a href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <h6>// Follow Us</h6>
        <div class="social-box style-square">
            <ul>
                @if(filled($facebookUrl))
                    <li><a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a></li>
                @endif
                @if(filled($igUrl))
                    <li><a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a></li>
                @endif
                @if(filled($linkedinUrl))
                    <li><a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
