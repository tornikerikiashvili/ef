@props([
    'title' => '',
    'text' => '',
    'image' => null,
    'link' => '',
])
@php
    $aboutTitle = filled($title) ? $title : 'Quality & Vision';
    $aboutText = filled($text)
        ? $text
        : 'Our approach is built on quality, international standards, and vision. We deliver a full-service package from concept to completion: design, interior design, and premium fit-out works.';
    $imageUrl = filled($image)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($image)
        : asset('assets/img/more/7.png');
    $buttonHref = filled($link) ? $link : route('about');
@endphp
<section class="wptb-about-two">
    <div class="container">
        <div class="wptb-heading">
            <div class="wptb-item--inner">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="wptb-item--title"><span>{{ $aboutTitle }}</span></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="wptb-image-single wow fadeInUp">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--image position-relative">
                            <img src="{{ $imageUrl }}" alt="{{ $aboutTitle }}">

                            <div class="wptb-item--button round-button">
                                <a class="btn btn-two" href="{{ $buttonHref }}">
                                    <span class="btn-wrap">
                                        <span class="text-first">{{__('messages.nav.explore_us')}}</span>
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
                    <p class="wptb-about--text-one">{!! nl2br(e($aboutText)) !!}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{--
            <section class="bg-dark-200 pd-bottom-80">
                <div class="container">
                    <div class="wptb-heading">
                        <div class="wptb-item--inner">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6 class="wptb-item--subtitle"><span>02 //</span> Our Awards</h6>
                                    <h1 class="wptb-item--title mb-0">Our Photography<br>
                                        <span>Awards</span></h1>
                                </div>


                            </div>
                        </div>
                    </div>

                    <ol class="wptb-award-list">
                        <li class="wptb-item">
                            <div class="wptb-item--inner">
                                <div class="wptb-item--holder">
                                    <a href="project-details.html">Photography Team of the Year 2023</a>
                                </div>
                                <div class="wptb-item--image">
                                    <img src="../assets/img/more/4.jpg" alt="img">
                                    <div class="wptb-item--button">
                                        <a href="project-details.html" class="btn">View</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="wptb-item active highlight">
                            <div class="wptb-item--inner">
                                <div class="wptb-item--holder">
                                    <a href="project-details.html">Best Wedding Photographer 2022</a>
                                </div>
                                <div class="wptb-item--image">
                                    <img src="../assets/img/more/5.jpg" alt="img">
                                    <div class="wptb-item--button">
                                        <a href="project-details.html" class="btn">View</a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="wptb-item">
                            <div class="wptb-item--inner">
                                <div class="wptb-item--holder">
                                    <a href="project-details.html">Photography Team of the Year 2019</a>
                                </div>
                                <div class="wptb-item--image">
                                    <img src="../assets/img/more/6.jpg" alt="img">
                                    <div class="wptb-item--button">
                                        <a href="project-details.html" class="btn">View</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>
    --}}
