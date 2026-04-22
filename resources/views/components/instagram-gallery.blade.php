@props([
    'images' => [],
    'instagramUrl' => '',
])
@php
    use Illuminate\Support\Facades\Storage;

    $paths = collect(is_array($images) ? $images : [])
        ->filter(fn ($p): bool => is_string($p) && $p !== '')
        ->values();

    $followHref = filled($instagramUrl) ? $instagramUrl : '#';
@endphp
@once
    @push('styles')
        <style>
            /* Tint sits above image tiles, below the centered Instagram button (theme positions button absolute). */
            .wptb-instagram--gallery .wptb-item--inner {
                position: relative;
            }

            .wptb-instagram--gallery .wptb-item--inner::after {
                content: '';
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 1;
                pointer-events: none;
            }

            .wptb-instagram--gallery .wptb-item--inner > .wptb-item {
                position: relative;
                z-index: 0;
            }

            .wptb-instagram--gallery .wptb-item--button {
                z-index: 2;
            }

            .wptb-instagram--gallery .instagram-gallery__image-bg {
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
                width: 100%;
                min-height: clamp(10rem, 28vw, 22rem);
                aspect-ratio: 1 / 1;
            }

            @supports not (aspect-ratio: 1 / 1) {
                .wptb-instagram--gallery .instagram-gallery__image-bg {
                    min-height: 16rem;
                }
            }
        </style>
    @endpush
@endonce
<div class="wptb-instagram--gallery">
    <div class="wptb-item--inner d-flex align-items-center justify-content-center flex-wrap flex-md-nowrap">
        @if ($paths->isNotEmpty())
            @foreach ($paths as $path)
                @php
                    $bgUrl = Storage::disk('public')->url($path);
                @endphp
                <div class="wptb-item">
                    <div
                        class="wptb-item--image instagram-gallery__image-bg"
                        style="background-image: url({{ json_encode($bgUrl) }});"
                        aria-hidden="true"
                    ></div>
                </div>
            @endforeach
        @else
            @foreach (range(1, 5) as $i)
                @php
                    $bgUrl = asset('assets/img/instagram/' . $i . '.jpg');
                @endphp
                <div class="wptb-item">
                    <div
                        class="wptb-item--image instagram-gallery__image-bg"
                        style="background-image: url({{ json_encode($bgUrl) }});"
                        aria-hidden="true"
                    ></div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="wptb-item--button">
        <a class="btn btn-two" href="{{ $followHref }}" @if(filled($instagramUrl)) target="_blank" rel="noopener noreferrer" @endif>
            <span class="btn-wrap">
                <span class="text-first">{{ __('messages.instagram.follow_us') }}</span>
                <span class="text-second"><i class="bi bi-instagram"></i></span>
            </span>
        </a>
    </div>
</div>
