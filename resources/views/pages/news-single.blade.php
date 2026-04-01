@extends('layouts.app')

@section('title', ($newsItem->title ?? __('messages.nav.news')) . ' - Ef')
@section('meta_description', $newsItem->teaser ? \Illuminate\Support\Str::limit(strip_tags($newsItem->teaser), 160) : 'Article - Ef Photography Agency')

@section('content')
@php
    $coverUrl = $newsItem->cover_photo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($newsItem->cover_photo)
        : asset('assets/img/blog/details.jpg');
@endphp
<section class="blog-details">
    <div class="container">
        <div class="row">

            <div class="col-lg-9 col-md-8 pe-md-5">
                <div class="blog-details-inner">
                    <div class="post-content">
                        <div class="post-header">
                            <h2 class="post-title">{{ $newsItem->title }}</h2>
                            <div class="wptb-item--meta d-flex align-items-center gap-4">
                                <div class="wptb-item wptb-item--author"><a href="#"><i class="bi bi-pencil-square"></i> <span>Ef</span></a></div>
                                <div class="wptb-item wptb-item--date"><a href="#"><i class="bi bi-calendar3"></i> <span>{{ ($newsItem->published_at ?? $newsItem->created_at)->format('F d, Y') }}</span></a></div>
                                <div class="wptb-item wptb-item--comments"><a href="#comments"><i class="bi bi-chat-square-text"></i> <span>2k</span></a></div>
                                <div class="wptb-item wptb-item--hits"><a href="#"><i class="bi bi-eye"></i> <span>1.38k</span></a></div>
                            </div>
                        </div>

                        @if($newsItem->teaser)
                        <div class="intro">
                            <p>{{ $newsItem->teaser }}</p>
                        </div>
                        @endif

                        <!-- Post Image -->
                        <figure class="block-gallery mt-4">
                            <img src="{{ $coverUrl }}" alt="{{ $newsItem->title }}">
                        </figure>

                        <div class="fulltext">
                            @if($newsItem->text_content)
                                {!! $newsItem->text_content !!}
                            @else
                                <p>{{ $newsItem->teaser }}</p>
                            @endif

                            <h4 class="widget-title">Project Concepts</h4>
                            <p> Kimono Photography is a full-service photography company providing wedding, newborn, fashion & portfolio
                                grapy. Our portfolio of completed work includes highly acclaimed and award-winning projects</p>

                            <ul class="point-order">
                                <li><i class="bi bi-check2-all"></i> The talent at Kimono runs wide and deep. Across many markets, geographies</li>
                                <li><i class="bi bi-check2-all"></i> Our team members are some of the finest professionals in the industry</li>
                                <li><i class="bi bi-check2-all"></i> Organized to deliver the most specialized service possible and enriched by the</li>
                            </ul>

                            <p>
                                Kimono Photography is a full-service photography compa providing wedding, newborn, fashion & portfolio
                                photograpy. Our portfolio of completed work include highly acclaimed and award-winning projects for clients
                                around the country & globally also.
                            </p>

                            <p>
                                Kimono Photography is a full-service photography compa providing wedding, newborn, fashion & portfolio
                                photograpy. Our portfolio of completed work include highly acclaimed and award-winning projects for clients
                                around the country & globally also.
                            </p>

                            <figure class="block-gallery mt-4">
                                <a href="#"><img src="{{ asset('assets/img/blog/details2.jpg') }}" alt="img" class="block-image"></a>
                            </figure>

                            <p>
                                Kimono Photography is a full-service photography compa providing wedding, newborn, fashion & portfolio
                                photograpy. Our portfolio of completed work include highly acclaimed and award-winning projects for clients
                                around the country & globally also.
                            </p>

                            <p>
                                Kimono Photography is a full-service photography compa providing wedding, newborn, fashion & portfolio
                                photograpy. Our portfolio of completed work include highly acclaimed and award-winning projects for clients
                                around the country & globally also.
                            </p>

                            <div class="post-footer">
                                <div class="post-share">
                                    <ul class="share-list">
                                        <li>Share:</li>
                                        <li class="facebook"><a href="#">Facebook</a></li>
                                        <li class="twitter"><a href="#">Twitter</a></li>
                                        <li class="pinterest"><a href="#">Pinterest</a></li>
                                        <li class="instagram"><a href="#">Instagram</a></li>
                                        <li class="linkedin"><a href="#">Linkedin</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Comment List -->
                            <div class="comments-area">
                                <h3 class="comments-title">Comments</h3>
                                <ul class="comment-list">
                                    <li class="comment even thread-even depth-1">
                                        <div class="commenter-block">
                                            <div class="comment-avatar">
                                                <img alt="img" src="../assets/img/blog/commenter-1.jpg" class="avatar">
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-author-name">Barret Simpson <span class="comment-date">January 29, 2023</span></div>
                                                <div class="comment-author-comment">
                                                    <p>Lorem ipsum dolor sit amet, consectetur. Ut enim ad minima veniam quis nostrum exercitationem mosequatu autem.</p>
                                                    <span class="comment-reply"><a href="#" class="comment-reply-link">Reply</a></span>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="children">
                                            <li class="comment even thread-even depth-2">
                                                <div class="commenter-block">
                                                    <div class="comment-avatar">
                                                        <img alt="img" src="../assets/img/blog/commenter-2.jpg" class="avatar">
                                                    </div>
                                                    <div class="comment-content">
                                                        <div class="comment-author-name">Parker Ballinger <span class="comment-date">January 22, 2023</span></div>
                                                        <div class="comment-author-comment">
                                                            <p>Lorem ipsum dolor sit amet, consectetur. Ut enim ad minima veniam quis nostrum exercitationem mosequatu autem.</p>
                                                            <span class="comment-reply"><a href="#" class="comment-reply-link">Reply</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li><!-- #comment-## -->
                                        </ul><!-- .children -->
                                    </li><!-- #comment-## -->
                                    <li class="comment odd thread-odd depth-1">
                                        <div class="commenter-block">
                                            <div class="comment-avatar">
                                                <img alt="img" src="../assets/img/blog/commenter-1.jpg" class="avatar">
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-author-name">Barret Simpson <span class="comment-date">January 01, 2023</span></div>
                                                <div class="comment-author-comment">
                                                    <p>Lorem ipsum dolor sit amet, consectetur. Ut enim ad minima veniam quis nostrum exercitationem mosequatu autem.</p>
                                                    <span class="comment-reply"><a href="#" class="comment-reply-link">Reply</a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </li><!-- #comment-## -->
                                </ul>
                                <div class="wptb-pagination-wrap">
                                    <ul class="pagination mt-0 justify-content-start">
                                        <li><span class="page-number current">1</span></li>
                                        <li><a class="page-number" href="#">2</a></li>
                                        <li>.....</li>
                                        <li><a class="page-number" href="#">5</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="comment-respond">
                                <h3 class="comment-reply-title">Make A Comment</h3>
                                <form class="comment-form" action="register.php" method="post">
                                    <p class="logged-in-as">Your email address will not be published. Required fields are marked *</p>
                                    <div class="form-container">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" name="name" class="form-control" placeholder="Name*" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-control" placeholder="E-mail*" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <textarea name="message" class="form-control" placeholder="Text Here*" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="wptb-item--button">
                                                    <button type="submit" class="btn">
                                                        <span class="btn-wrap">
                                                            <span class="text-first">Make Comment</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar  -->
            <div class="col-lg-3 col-md-4 p-md-0 mt-5 mt-md-0">

                <div class="sidebar">

                    <div class="widget widget_block widget_search">
                        <form method="get" class="wp-block-search">
                            <div class="wp-block-search__inside-wrapper ">
                                <input type="search" class="wp-block-search__input" name="search" value="" placeholder="Search" required="">
                                <button type="submit" class="wp-block-search__button"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <!-- end widget -->

                    <div class="widget widget_block">
                        <div class="wp-block-group__inner-container">
                            <h2 class="widget-title">Categories</h2>
                            <ul class="wp-block-categories-list wp-block-categories">
                                <li class="cat-item"><a href="#">Album</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Nature</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Decorative</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Newborn</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Wildlife</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Portfolio</a> <i class="bi bi-chevron-right"></i></li>
                                <li class="cat-item"><a href="#">Abstract</a> <i class="bi bi-chevron-right"></i></li>
                            </ul>
                        </div>
                    </div>
                    <!-- end widget -->

                    <div class="widget widget_block">
                        <div class="wp-block-group__inner-container">
                            <h2 class="widget-title">Recent Posts</h2>
                            <ul class="wp-block-latest-posts__list wp-block-latest-posts">
                                <li>
                                    <div class="latest-posts-content">
                                        <h5><a href="blog.html">California Mansion Residence</a></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="latest-posts-content">
                                        <h5><a href="blog.html">Well decor house in Sydney</a></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="latest-posts-content">
                                        <h5><a href="blog.html">Huge large area Bedroom</a></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="latest-posts-content">
                                        <h5><a href="blog.html">Recent trends in designing space</a></h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end widget -->

                    <div class="widget widget_block">
                        <h2 class="widget-title">
                            Archive
                        </h2>
                        <ul class="wp-block-archive">
                            <li><a href="blog.html">September 2023</a></li>
                            <li><a href="blog.html">June 2023</a></li>
                            <li><a href="blog.html">November 2022</a></li>
                            <li><a href="blog.html">July 2022</a></li>
                            <li><a href="blog.html">December 2021</a></li>
                        </ul>
                    </div>
                    <!-- end widget -->

                    <div class="widget widget_block">
                        <h2 class="widget-title">
                            Product Tag
                        </h2>
                        <div class="wp-block-tag-list wp-block-tag">
                            <a href="#" class="tag-cloud-link">Photography</a>
                            <a href="#" class="tag-cloud-link">Indoor</a>
                            <a href="#" class="tag-cloud-link">Outdoor</a>
                            <a href="#" class="tag-cloud-link">Fashion</a>
                            <a href="#" class="tag-cloud-link">Studio</a>
                            <a href="#" class="tag-cloud-link">Wedding</a>
                            <a href="#" class="tag-cloud-link">Newborn</a>
                        </div>
                    </div>
                    <!-- end widget -->
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
