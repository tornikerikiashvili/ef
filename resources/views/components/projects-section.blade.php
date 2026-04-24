@props([
    'projects' => collect(),
    'sectionTitle' => '',
])
@push('styles')
<style>
    .wptb-project.projects-section--olive .grid_lines { background-color: #C0C6AF; }
    .wptb-project.projects-section--olive .grid_lines .grid_line { mix-blend-mode: normal; }

    .projects-section--card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .projects-section--card-link:hover,
    .projects-section--card-link:focus {
        color: inherit;
        text-decoration: none;
    }

    .projects-section--card-link .wptb-item--image img {
        transform: scale(1);
        transform-origin: center;
        transition: transform 450ms ease;
        will-change: transform;
    }

    .projects-section--card-link:hover .wptb-item--image img,
    .projects-section--card-link:focus-visible .wptb-item--image img {
        transform: scale(0.97);
    }
</style>
@endpush
@php
    $items = $projects->isNotEmpty() ? $projects : null;
    $placeholderItems = [
        ['1', 'Bright Boho Sunshine'], ['2', 'California Fall Collection 2023'], ['4', 'Brown girl next door'],
        ['3', 'Fashion next stage'], ['6', 'Jenifer in green'], ['5', 'Sunflower Boho girl'],
        ['8', 'Iceland girl'], ['7', 'Summer sadness'],
    ];
@endphp
<section class="wptb-project projects-section--olive">
    <div class="grid_lines">
        @for ($i = 0; $i < 7; $i++) <div class="grid_line"></div> @endfor
    </div>
    <div class="container">
        <div class="wptb-project--inner">
            <div class="wptb-heading">
                <div class="wptb-item--inner text-center">
                    @if (filled($sectionTitle))
                        <h1 class="wptb-item--title">{{ $sectionTitle }}</h1>
                    @else
                        <h1 class="wptb-item--title">We capture <span>All of Your</span> <br> beautiful memories</h1>
                    @endif
                </div>
            </div>
            <div class="style-masonry effect-tilt">
                <div class="grid grid-2 gutter-100 clearfix">
                    <div class="grid-sizer"></div>
                    @if($items && $items->isNotEmpty())
                        @foreach ($items as $project)
                        @php
                            $coverUrl = $project->cover_photo
                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_photo)
                                : asset('assets/img/projects/6/1.jpg');
                            $projectUrl = route('projects.show', ['slug' => $project->slug ?? $project->id]);
                            $dir = ($loop->index % 2 === 0) ? -1 : 1;
                        @endphp
                        <div class="grid-item projects-parallax-item" data-parallax-dir="{{ $dir }}">
                            <a class="projects-section--card-link" href="{{ $projectUrl }}">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--image">
                                        <img src="{{ $coverUrl }}" alt="{{ $project->title }}">
                                    </div>
                                    <div class="wptb-item--holder">
                                        <div class="wptb-item--meta">
                                            <h4>{{ $project->title }}</h4>
                                            @if($project->client)
                                                <p>{{ $project->client }}</p>
                                            @else
                                                <p>&nbsp;</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @else
                        @foreach ($placeholderItems as $item)
                        @php $dir = ($loop->index % 2 === 0) ? -1 : 1; @endphp
                        <div class="grid-item projects-parallax-item" data-parallax-dir="{{ $dir }}">
                            <a class="projects-section--card-link" href="{{ route('projects') }}">
                                <div class="wptb-item--inner">
                                    <div class="wptb-item--image">
                                        <img src="{{ asset('assets/img/projects/6/' . $item[0] . '.jpg') }}" alt="">
                                    </div>
                                    <div class="wptb-item--holder">
                                        <div class="wptb-item--meta">
                                            <h4>{{ $item[1] }}</h4>
                                            <p>By Jonathon Willson</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="wptb-item--button text-center mt-5">
                <a class="btn btn-three w-100 text-uppercase" href="{{ route('projects') }}">
                    <span class="btn-wrap">
                        <span class="text-first">{{ __('messages.projects.discover_all') }}</span>
                        <span class="text-second">{{ __('messages.projects.discover_all') }}</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .projects-section--olive .projects-parallax-item {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        transition: transform 120ms linear;
    }
</style>
@endpush

@push('scripts')
<script>
  (function () {
    const root = document.querySelector('.wptb-project.projects-section--olive');
    if (!root) return;

    const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches;
    const isTouchLike = ('ontouchstart' in window) || (navigator.maxTouchPoints && navigator.maxTouchPoints > 0);
    if (prefersReducedMotion || isTouchLike) return;

    const items = Array.from(root.querySelectorAll('.projects-parallax-item'));
    if (!items.length) return;

    let ticking = false;
    const amplitudePx = 48; // amount each column drifts
    const rotateDeg = 1.15; // subtle rotation
    const scaleAmt = 0.015; // subtle scale

    function update() {
      ticking = false;
      const vh = window.innerHeight || 1;
      const sectionRect = root.getBoundingClientRect();

      // progress 0..1 through the section
      const sectionProgress = (vh - sectionRect.top) / (vh + sectionRect.height);
      const p = Math.max(0, Math.min(1, sectionProgress));

      // -0.5..0.5
      const centered = p - 0.5;

      for (let i = 0; i < items.length; i++) {
        const el = items[i];
        const dir = parseFloat(el.dataset.parallaxDir || '0') || 0;
        const phase = (i % 6) * 0.08; // small per-item variance
        const wobble = Math.sin((p + phase) * Math.PI * 2) * 6; // +/- 6px

        const y = (centered * amplitudePx * dir * 2) + (wobble * dir);
        const r = centered * rotateDeg * dir;
        const s = 1 + (Math.abs(centered) * scaleAmt);

        el.style.transform = `translate3d(0, ${y.toFixed(2)}px, 0) rotate(${r.toFixed(2)}deg) scale(${s.toFixed(3)})`;
      }
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(update);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
  })();
</script>
@endpush
