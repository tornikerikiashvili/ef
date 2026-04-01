@props([
    'partnerLogos' => collect(),
])
@php
    use Illuminate\Support\Facades\Storage;

    $logos = $partnerLogos->isNotEmpty() ? $partnerLogos : collect();
@endphp
@if($logos->isNotEmpty())
<div class="wptb-marquee pd-top-80">
    <div class="wptb-text-marquee1 wptb-slide-to-left">
        <div class="wptb-item--container">
            <div class="wptb-item--inner d-flex align-items-center gap-5" style="gap: 3rem;">
                @foreach ($logos as $partner)
                @php
                    $logoUrl = $partner->logo_white
                        ? Storage::disk('public')->url($partner->logo_white)
                        : ($partner->logo_colorful ? Storage::disk('public')->url($partner->logo_colorful) : null);
                    $link = $partner->link ?? '#';
                @endphp
                @if($logoUrl)
                <a href="{{ $link }}" class="d-block" target="_blank" rel="noopener noreferrer" style="height: 2.5rem; display: flex; align-items: center;">
                    <img src="{{ $logoUrl }}" alt="{{ $partner->title ?? 'Partner' }}" style="max-height: 2.5rem; width: auto; object-fit: contain;">
                </a>
                @endif
                @endforeach
            </div>
            <div class="wptb-item--inner d-flex align-items-center gap-5" style="gap: 3rem;" aria-hidden="true">
                @foreach ($logos as $partner)
                @php
                    $logoUrl = $partner->logo_white
                        ? Storage::disk('public')->url($partner->logo_white)
                        : ($partner->logo_colorful ? Storage::disk('public')->url($partner->logo_colorful) : null);
                    $link = $partner->link ?? '#';
                @endphp
                @if($logoUrl)
                <a href="{{ $link }}" class="d-block" target="_blank" rel="noopener noreferrer" style="height: 2.5rem; display: flex; align-items: center;">
                    <img src="{{ $logoUrl }}" alt="" style="max-height: 2.5rem; width: auto; object-fit: contain;">
                </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
