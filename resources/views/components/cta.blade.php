@php
    $ctaTextureUrl = asset('assets/img/more/texture-2.svg');
    $ctaTextureMobileUrl = asset('assets/img/more/texture-cta-mobile.svg');
@endphp
<section class="wptb-contact-form style1">
    <div
        class="wptb-item-layer both-version d-none d-md-block"
        style="background-image: url({{ json_encode($ctaTextureUrl) }}); background-repeat: no-repeat; background-position: center; background-size: cover; width: 100%; height: 100%;"
        aria-hidden="true"
    ></div>
    <div
        class="wptb-item-layer both-version d-block d-md-none"
        style="background-image: url({{ json_encode($ctaTextureMobileUrl) }}); background-repeat: no-repeat; background-position: center; width: 100%; height: 100%;"
        aria-hidden="true"
    ></div>
    <div class="container">
        <x-contact-office-cards />
    </div>
</section>
