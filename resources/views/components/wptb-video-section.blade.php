@props([
    'url',
    'backgroundImage',
])
{{-- Same block as About page “BG Video” (wptb-video-player1) --}}
<div {{ $attributes->class(['container', 'mr-top-100']) }}>
    <div class="wptb-video-player1 wow zoomIn" style="background-image: url('{{ $backgroundImage }}');">
        <div class="wptb-item--inner">
            <div class="wptb-item--holder">
                <div class="wptb-item--video-button">
                    <a class="btn" data-fancybox href="{{ $url }}">
                        <span class="text-second"> <i class="bi bi-play-fill"></i> </span>
                        <span class="line-video-animation line-video-1"></span>
                        <span class="line-video-animation line-video-2"></span>
                        <span class="line-video-animation line-video-3"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/light-3.png') }}" alt="">
        </div>
    </div>
</div>
