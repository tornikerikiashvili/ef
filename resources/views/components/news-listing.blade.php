@props([
    'news' => collect(),
    'pageCover' => null,
])
@php
    use Illuminate\Support\Facades\Storage;

    $coverPath = is_array($pageCover) ? (reset($pageCover) ?: null) : $pageCover;
    $headerBg = $coverPath
        ? Storage::disk('public')->url($coverPath)
        : asset('assets/img/background/page-header-bg-8.jpg');
@endphp
<!-- Page Header -->
<div class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ $headerBg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('assets/img/more/circle.png') }}" alt="">
        </div>
        <h2 class="wptb-item--title">{{ __('messages.nav.news') }}</h2>
    </div>
</div>

<section class="wptb-blog-grid-one">
    <div class="container">

        <div class="row">
            @foreach ($news as $item)
            @php
                $itemUrl = route('news.show', ['slug' => $item->slug ?? $item->id]);
                $imgUrl = $item->cover_photo
                    ? Storage::disk('public')->url($item->cover_photo)
                    : asset('assets/img/blog/' . (((int) (($loop->iteration - 1) % 9)) + 1) . '.jpg');
            @endphp
            <div class="col-lg-4 col-sm-6">
                <div class="wptb-blog-grid1 {{ $loop->iteration % 3 === 1 ? 'active highlight' : '' }} wow fadeInLeft">
                    <div class="wptb-item--inner">
                        <div class="wptb-item--image">
                            <a href="{{ $itemUrl }}" class="wptb-item-link"><img src="{{ $imgUrl }}" alt="{{ $item->title }}"></a>
                        </div>
                        <div class="wptb-item--holder">
                            <div class="wptb-item--date">{{ ($item->published_at ?? $item->created_at)->format('d M Y') }}</div>
                            <h4 class="wptb-item--title"><a href="{{ $itemUrl }}">{{ $item->title }}</a></h4>

                            <div class="wptb-item--meta">
                                <div class="wptb-item--author">By <a href="{{ $itemUrl }}">Ef</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
