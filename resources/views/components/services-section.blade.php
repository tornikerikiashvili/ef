@props([
    'title' => '',
    'text' => '',
    'image' => null,
    'link' => '',
])
@php
    $resolveHref = static function (?string $url, string $fallback): string {
        $url = trim((string) $url);
        if ($url === '') {
            return $fallback;
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        if (\Illuminate\Support\Str::startsWith($url, '/')) {
            return url($url);
        }

        return $fallback;
    };

    $headlineTitle = filled($title)
        ? $title
        : __('messages.cta.title');

    $topCtaHref = $resolveHref($link, route('contact'));
    $exploreHref = $resolveHref($link, route('about'));

    $imageUrl = filled($image)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($image)
        : asset('assets/img/more/7.png');
@endphp


<section class="wptb-services">
    <div class="wptb-slider-divider--bg"></div>
    <section class="wptb-about-two">
        <div class="container">

            <div class="row">
                <div class="col-md-6">
                    <div class="wptb-image-single wow fadeInUp">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image position-relative">
                                <img src="{{ $imageUrl }}" alt="{{ strip_tags($headlineTitle) }}">

                                <div class="wptb-item--button round-button">
                                    <a class="btn btn-two" href="{{ $exploreHref }}">
                                        <span class="btn-wrap">
                                            <span class="text-first">{{ __('messages.nav.explore_us') }}</span>
                                            <span class="text-second"> <i class="bi bi-arrow-up-right"></i> <i class="bi bi-arrow-up-right"></i> </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
                    <div class="wptb-about--text ps-md-5">
                        @if (filled($text))
                            {{-- Same HTML as Home about / Filament RichEditor; do not use e() here or tags show as text --}}
                            <div class="wptb-about--text-one">{!! $text !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
