@props([
    'services' => collect(),
])
@php
    use Illuminate\Support\Facades\Storage;

    $serviceCover = fn ($service) => $service->cover_photo
        ? Storage::disk('public')->url($service->cover_photo)
        : asset('assets/img/projects/3/1.jpg');
@endphp

<!-- Our Services Listing -->
<section>
    <div class="container">
        <div class="wptb-project--inner">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    <h6 class="wptb-item--subtitle"><span>01//</span> {{ __('messages.nav.services') }}</h6>
                    <h1 class="wptb-item--title">Our <span>Services</span></h1>
                </div>
            </div>

            <div class="effect-gradient has-radius">
                <div class="grid grid-3 gutter-10 clearfix">
                    <div class="grid-sizer"></div>
                    @foreach ($services as $service)
                    @php
                        $coverUrl = $serviceCover($service);
                        $serviceUrl = route('services.show', ['slug' => $service->slug ?? $service->id]);
                    @endphp
                    <div class="grid-item">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <img src="{{ $coverUrl }}" alt="{{ $service->title }}">
                                <a class="wptb-item--link" href="{{ $serviceUrl }}"><i class="bi bi-chevron-right"></i></a>
                            </div>

                            <div class="wptb-item--holder">
                                <div class="wptb-item--meta">
                                    <h4><a href="{{ $serviceUrl }}">{{ $service->title }}</a></h4>
                                    @if($service->short_teaser)
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($service->short_teaser), 60) }}</p>
                                    @else
                                        <p>&nbsp;</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
