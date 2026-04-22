@props([
    'title' => '',
    'teaser' => '',
    'highlights' => [],
])
@php
    $sectionTitle = filled($title) ? $title : 'About Company';
    $sectionTeaser = filled($teaser)
        ? $teaser
        : 'Quality and international standards. Full-service from design to premium fit-out for every project.';

    $fallbackHighlights = [
        [
            'title' => 'Design & Fit-Out',
            'teaser' => 'Full-service from concept to completion: design, interior design, and premium fit-out works.',
            'link' => '',
        ],
        [
            'title' => 'International Standards',
            'teaser' => 'International brand guidelines, local and global standards, and best practices in every project.',
            'link' => '',
        ],
        [
            'title' => 'ELEMENT HOLDING',
            'teaser' => 'Over 10 companies in industrial, hydrotechnical and civil construction since 2007.',
            'link' => '',
        ],
    ];

    $rows = [];
    for ($i = 0; $i < 3; $i++) {
        $h = is_array($highlights[$i] ?? null) ? $highlights[$i] : [];
        $fb = $fallbackHighlights[$i];
        $rows[] = [
            'title' => filled($h['title'] ?? null) ? (string) $h['title'] : $fb['title'],
            'teaser' => filled($h['teaser'] ?? null) ? (string) $h['teaser'] : $fb['teaser'],
            'link' => filled($h['link'] ?? null) ? (string) $h['link'] : '',
        ];
    }
@endphp
<section class="wptb-services">
    <div class="wptb-slider-divider--bg"></div>
    <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div>
    <div class="container">
        <div class="wptb-heading">
            <div class="wptb-item--inner">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="wptb-item--title"><span>{{ $sectionTitle }}</span></h1>
                    </div>
                    <div class="col-lg-6">
                        <p class="wptb-item--description">{{ $sectionTeaser }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($rows as $index => $row)
                @php
                    $href = filled($row['link']) ? $row['link'] : route('services');
                    $boxClass = 'wptb-icon-box7 mb-0'.($index === 1 ? ' active highlight' : '');
                @endphp
                <div class="col-md-4 wow fadeInLeft">
                    <div class="{{ $boxClass }}">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--holder">
                                <h4 class="wptb-item--title"><a href="{{ $href }}">{{ $row['title'] }}</a></h4>
                                <p class="wptb-item--description">{{ $row['teaser'] }}</p>
                                <div class="wptb-item--count-wrap d-flex justify-content-end mt-3" style="margin-top:15px;">
                                    <a href="{{ $href }}" class="wptb-item--count wptb-item--count--arrow" aria-label="{{ $row['title'] }}">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
