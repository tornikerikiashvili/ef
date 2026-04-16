@props([
    'partnerLogos' => collect(),
])
@php
    use Illuminate\Support\Facades\Storage;

    $logoItems = $partnerLogos
        ->map(function ($partner) {
            $defaultLogoUrl = $partner->logo_white
                ? Storage::disk('public')->url($partner->logo_white)
                : ($partner->logo_colorful ? Storage::disk('public')->url($partner->logo_colorful) : null);

            $hoverLogoUrl = $partner->logo_colorful
                ? Storage::disk('public')->url($partner->logo_colorful)
                : $defaultLogoUrl;

            if (! $defaultLogoUrl) {
                return null;
            }

            return [
                'default_logo_url' => $defaultLogoUrl,
                'hover_logo_url' => $hoverLogoUrl,
                'link' => $partner->link ?? '#',
                'title' => $partner->title ?? 'Partner',
            ];
        })
        ->filter()
        ->values();
@endphp
@if ($logoItems->isNotEmpty())
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/Wrapp-dev/public-scripts@main/dist/marquee.min.js"></script>
    @endpush
    {{-- Mirrors Zoomart about.html: third <section> (Wrapp marquee + class names) --}}
    <section style="background-color: #3A3B3A;" class="zoomart-partners section_default overflow-hidden">
        {{-- <div class="container">
            <div class="wptb-heading">
                <div class="wptb-item--inner">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1 class="wptb-item--title">Partners</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div data-wr-marquee class="partners_wrapper">
            <div class="partners_list">
                @foreach ($logoItems as $item)
                    <a
                        href="{{ $item['link'] }}"
                        class="partners_item w-inline-block"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span class="partners_logo-wrap">
                            <img
                                src="{{ $item['default_logo_url'] }}"
                                width="218"
                                height="89"
                                alt="{{ $item['title'] }}"
                                class="partners_logo partners_logo--default"
                                loading="lazy"
                                decoding="async"
                            >
                            <img
                                src="{{ $item['hover_logo_url'] }}"
                                width="218"
                                height="89"
                                alt=""
                                class="partners_logo partners_logo--hover"
                                loading="lazy"
                                decoding="async"
                            >
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif








