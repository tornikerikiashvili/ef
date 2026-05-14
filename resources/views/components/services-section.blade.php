@props([
    'title' => '',
    'text' => '',
    'image' => null,
    'link' => '',
    'videoUrl' => '',
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
            $path = '/'.ltrim($url, '/');
            $locales = config('cms.supported_locales', ['en', 'ka']);

            foreach ($locales as $locale) {
                if ($path === '/'.$locale || \Illuminate\Support\Str::startsWith($path, '/'.$locale.'/')) {
                    return url($path);
                }
            }

            return url('/'.app()->getLocale().$path);
        }

        return $fallback;
    };

    $headlineTitle = filled($title)
        ? $title
        : __('messages.cta.title');

    $topCtaHref = $resolveHref($link, route('contact'));
    $exploreHref = $resolveHref($link, route('about'));

    $youtubeWatch = \App\Models\Project::youtubeWatchUrl(trim((string) $videoUrl));

    $imageUrl = filled($image)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($image)
        : asset('assets/img/more/7.png');
@endphp


<section class="wptb-services">
    <div class="wptb-slider-divider--bg"></div>
    <section class="wptb-about-two">
        <div class="container">

            <div class="wptb-heading">
                <div class="wptb-item--inner">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1 class="wptb-item--title">{{ strip_tags($headlineTitle) }}</h1>
                        </div>
                        <div class="col-lg-5 text-lg-end">
                            <div class="wptb-item--button">
                                @if ($youtubeWatch)
                                    <a href="{{ $youtubeWatch }}" class="btn btn-two creative text-uppercase cursor-no-grow" data-fancybox aria-label="{{ __('messages.project.watch_video') }}">
                                        <span class="btn-wrap">
                                            <span class="text-first">{{ __('messages.project.watch_video') }}</span>
                                            <span class="text-second"><i class="bi bi-play-circle" aria-hidden="true"></i></span>
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ $topCtaHref }}" class="btn btn-two creative text-uppercase">
                                        <span class="btn-wrap">
                                            <span class="text-first">{{ __('messages.project.watch_video') }}</span>
                                            <span class="text-second"><i class="bi bi-play-circle" aria-hidden="true"></i></span>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
