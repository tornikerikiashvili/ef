<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Page;
use App\Models\PartnerLogo;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Status;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function home()
    {
        $homePage = Page::homePageContent();

        $featuredServices = Page::orderedServices($homePage['ids']);
        if ($featuredServices->isEmpty()) {
            $featuredServices = Service::featuredInHero()
                ->orderBy('hero_order')
                ->get();
        }

        $featuredProjects = Page::orderedProjects($homePage['project_ids']);
        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::featured()
                ->orderBy('featured_order')
                ->get();
        }

        $featuredNews = Page::orderedNews($homePage['news_ids']);
        if ($featuredNews->isEmpty()) {
            $featuredNews = News::featured()
                ->orderBy('featured_order')
                ->get();
        }

        $partnerLogos = Page::orderedPartnerLogos($homePage['partner_logo_ids']);
        if ($partnerLogos->isEmpty()) {
            $partnerLogos = PartnerLogo::orderBy('id')->get();
        }

        return view('pages.home', compact('featuredServices', 'featuredProjects', 'featuredNews', 'partnerLogos', 'homePage'));
    }

    public function about()
    {
        $aboutPage = Page::aboutPageContent();

        return view('pages.about', compact('aboutPage'));
    }

    public function services()
    {
        $settings = Page::servicesListingPageContent();

        $services = Page::orderedServices($settings['services']);
        if ($services->isEmpty()) {
            $services = Service::orderBy('created_at', 'desc')->get();
        }

        $fallbackCover = SiteSetting::getValue('services_page_cover');
        $fallbackCover = is_array($fallbackCover) ? (reset($fallbackCover) ?: null) : $fallbackCover;

        $headerBg = $settings['cover_image']
            ? Storage::disk('public')->url($settings['cover_image'])
            : ($fallbackCover ? Storage::disk('public')->url($fallbackCover) : asset('assets/img/background/page-header-bg-8.jpg'));

        $serviceCover = fn (Service $service): string => $service->cover_photo
            ? Storage::disk('public')->url($service->cover_photo)
            : asset('assets/img/projects/3/1.jpg');

        $servicesPageTitle = $settings['title'];
        $servicesListTitle = $settings['services_title'];
        $servicesTypewriteText = $settings['typewrite_text'] ?? '';

        $servicesVideoBg = $settings['video_background_image']
            ? Storage::disk('public')->url($settings['video_background_image'])
            : asset('assets/img/background/bg-7.jpg');

        $servicesVideoUrl = filled($settings['video_url'])
            ? $settings['video_url']
            : 'https://www.youtube.com/watch?v=SF4aHwxHtZ0';

        return view('pages.services', compact(
            'services',
            'headerBg',
            'serviceCover',
            'servicesPageTitle',
            'servicesListTitle',
            'servicesTypewriteText',
            'servicesVideoBg',
            'servicesVideoUrl',
        ));
    }

    public function serviceSingle(string $locale, string $slug)
    {
        $service = Service::where('slug', $slug)->first();

        if (! $service && ctype_digit((string) $slug)) {
            $service = Service::find((int) $slug);
        }

        if (! $service) {
            abort(404);
        }

        $prevService = Service::where('id', '<', $service->id)->orderBy('id', 'desc')->first();
        $nextService = Service::where('id', '>', $service->id)->orderBy('id')->first();

        $relatedServices = Service::where('id', '!=', $service->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('pages.service-single', compact('service', 'prevService', 'nextService', 'relatedServices'));
    }

    public function projects()
    {
        $settings = Page::projectsListingPageContent();

        $projects = Page::orderedProjects($settings['projects']);
        if ($projects->isEmpty()) {
            $projects = Project::with(['categories', 'status'])->orderBy('created_at', 'desc')->get();
        } else {
            // Ensure relations exist for filters/classes.
            $projects->load(['categories', 'status']);
        }

        $categories = Category::orderBy('name')->get();
        $statuses = Status::orderBy('name')->get();

        $fallbackCover = SiteSetting::getValue('projects_page_cover');
        $fallbackCover = is_array($fallbackCover) ? (reset($fallbackCover) ?: null) : $fallbackCover;

        $headerBg = $settings['cover_image']
            ? Storage::disk('public')->url($settings['cover_image'])
            : ($fallbackCover ? Storage::disk('public')->url($fallbackCover) : asset('assets/img/background/page-header-bg-8.jpg'));

        $projectsPageTitle = $settings['title'];

        return view('pages.projects', compact('projects', 'categories', 'statuses', 'headerBg', 'projectsPageTitle'));
    }

    public function projectSingle(string $locale, string $slug)
    {
        $project = Project::with(['categories', 'status'])
            ->where('slug', $slug)
            ->first();

        if (! $project && ctype_digit((string) $slug)) {
            $project = Project::with(['categories', 'status'])->find((int) $slug);
        }

        if (! $project) {
            abort(404);
        }

        $prevProject = Project::where('id', '<', $project->id)->orderBy('id', 'desc')->first();
        $nextProject = Project::where('id', '>', $project->id)->orderBy('id')->first();

        $relatedProjects = Project::with('categories')
            ->where('id', '!=', $project->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('pages.project-single', compact('project', 'prevProject', 'nextProject', 'relatedProjects'));
    }

    public function news()
    {
        $settings = Page::newsListingPageContent();

        $q = trim((string) request()->query('q', ''));
        $range = (string) request()->query('range', '');
        $range = in_array($range, ['week', 'month', 'year'], true) ? $range : '';

        $hasFilters = filled($q) || filled($range);
        $perPage = 12;

        if ($hasFilters) {
            $newsQuery = News::query()
                ->with('newsCategory')
                ->orderByRaw('COALESCE(published_at, created_at) DESC');

            if (filled($range)) {
                $from = match ($range) {
                    'week' => now()->subWeek(),
                    'month' => now()->subMonth(),
                    'year' => now()->subYear(),
                    default => null,
                };

                if ($from) {
                    $newsQuery->whereRaw('COALESCE(published_at, created_at) >= ?', [$from]);
                }
            }

            if (filled($q)) {
                $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $q).'%';
                $newsQuery->where(function ($query) use ($like) {
                    $query
                        ->where('title', 'like', $like)
                        ->orWhere('teaser', 'like', $like)
                        ->orWhere('text_content', 'like', $like);
                });
            }

            $news = $newsQuery->paginate($perPage)->withQueryString();
        } else {
            $collection = Page::orderedNews($settings['news']);
            if ($collection->isEmpty()) {
                $collection = News::with('newsCategory')
                    ->orderByRaw('COALESCE(published_at, created_at) DESC')
                    ->get();
            } else {
                $collection->load('newsCategory');
            }

            $page = max(1, (int) request()->query('page', 1));
            $total = $collection->count();
            $items = $collection->forPage($page, $perPage)->values();

            $news = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            $news->withQueryString();
        }

        $fallbackCover = SiteSetting::getValue('news_page_cover');
        $fallbackCover = is_array($fallbackCover) ? (reset($fallbackCover) ?: null) : $fallbackCover;

        $headerBg = $settings['cover_image']
            ? Storage::disk('public')->url($settings['cover_image'])
            : ($fallbackCover ? Storage::disk('public')->url($fallbackCover) : asset('assets/img/background/page-header-bg-8.jpg'));

        $newsPageTitle = $settings['title'];

        $newsSlots = $this->newsListingCardSlots(collect($news->items()));

        return view('pages.news', compact('news', 'newsSlots', 'headerBg', 'newsPageTitle', 'q', 'range'));
    }

    /**
     * Wide cards (width-100) only use {@see News::$is_featured}. If the current page has no featured
     * posts, every card is width-50 — no empty wide slots, no promoting normal posts to full width.
     *
     * @param  Collection<int, News>  $pageItems
     * @return list<array{span: string, news: News}>
     */
    private function newsListingCardSlots(Collection $pageItems): array
    {
        if ($pageItems->isEmpty()) {
            return [];
        }

        $hasFeaturedOnPage = $pageItems->contains(fn (News $n) => $n->is_featured);

        if (! $hasFeaturedOnPage) {
            return $pageItems->values()
                ->map(fn (News $n) => ['span' => 'width-50', 'news' => $n])
                ->all();
        }

        $standard = $pageItems
            ->filter(fn (News $n) => ! $n->is_featured)
            ->sortByDesc(fn (News $n) => ($n->published_at ?? $n->created_at)?->timestamp ?? 0)
            ->values();

        $featured = $pageItems
            ->filter(fn (News $n) => $n->is_featured)
            ->sort(function (News $a, News $b): int {
                $oa = (int) ($a->featured_order ?? 99999);
                $ob = (int) ($b->featured_order ?? 99999);
                if ($oa !== $ob) {
                    return $oa <=> $ob;
                }

                return ($b->published_at ?? $b->created_at) <=> ($a->published_at ?? $a->created_at);
            })
            ->values();

        $standardList = $standard->all();
        $featuredList = $featured->all();
        $sq = 0;
        $fq = 0;
        $pattern = ['width-50', 'width-50', 'width-100', 'width-50', 'width-50', 'width-100'];
        $slots = [];
        $pi = 0;

        while ($sq < count($standardList) || $fq < count($featuredList)) {
            $slotType = $pattern[$pi % count($pattern)];
            $pi++;

            if ($slotType === 'width-100') {
                if ($fq >= count($featuredList)) {
                    continue;
                }
                $slots[] = ['span' => 'width-100', 'news' => $featuredList[$fq++]];

                continue;
            }

            if ($sq < count($standardList)) {
                $slots[] = ['span' => 'width-50', 'news' => $standardList[$sq++]];
            } elseif ($fq < count($featuredList)) {
                $slots[] = ['span' => 'width-50', 'news' => $featuredList[$fq++]];
            } else {
                break;
            }
        }

        return $slots;
    }

    public function newsSingle(string $locale, string $slug)
    {
        $newsItem = News::with('newsCategory')->where('slug', $slug)->first();

        if (! $newsItem && ctype_digit((string) $slug)) {
            $newsItem = News::with('newsCategory')->find((int) $slug);
        }

        if (! $newsItem) {
            abort(404);
        }

        return view('pages.news-single', compact('newsItem'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
