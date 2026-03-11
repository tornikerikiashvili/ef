<div class="wptb-instagram--gallery">
    <div class="wptb-item--inner d-flex align-items-center justify-content-center flex-wrap flex-md-nowrap">
        @foreach (range(1, 5) as $i)
        <div class="wptb-item">
            <div class="wptb-item--image">
                <img src="{{ asset('assets/img/instagram/' . $i . '.jpg') }}" alt="">
            </div>
        </div>
        @endforeach
    </div>
    <div class="wptb-item--button">
        <a class="btn btn-two" href="#">
            <span class="btn-wrap">
                <span class="text-first">Follow Us on Instagram</span>
                <span class="text-second"><i class="bi bi-instagram"></i></span>
            </span>
        </a>
    </div>
</div>
