<section class="wptb-blog-grid-one pb-0">
    <div class="container">
        <div class="wptb-heading">
            <div class="wptb-item--inner">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h6 class="wptb-item--subtitle"><span>05 //</span> {{ __('messages.nav.news') }}</h6>
                        <h1 class="wptb-item--title mb-0">Our Photography<br><span>Related Blog</span></h1>
                    </div>
                    <div class="col-lg-6">
                        <p class="wptb-item--description">We're deeply passionate catch your lovely memories in cameras and convey your love for every moment of life as a whole.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="wptb-blog--inner">
            <div class="row">
                @foreach ([['1', 'Beginners guide to start your photography journey', '25 Sep 2023', 'Ashton William'], ['2', 'Twenty photography tips to make photos amazing', '22 Sep 2023', 'Olivia Rose'], ['3', 'What Norway is best spots For Photography', '22 Sep 2023', 'Justin Burke']] as $post)
                <div class="col-lg-4 col-sm-6">
                    <div class="wptb-blog-grid1 {{ $loop->first ? 'active highlight' : '' }} wow fadeInLeft">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image">
                                <a href="{{ route('news') }}" class="wptb-item-link"><img src="{{ asset('assets/img/blog/' . $post[0] . '.jpg') }}" alt=""></a>
                            </div>
                            <div class="wptb-item--holder">
                                <div class="wptb-item--date">{{ $post[2] }}</div>
                                <h4 class="wptb-item--title"><a href="{{ route('news') }}">{{ $post[1] }}</a></h4>
                                <div class="wptb-item--meta">
                                    <div class="wptb-item--author">By <a href="#">{{ $post[3] }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
