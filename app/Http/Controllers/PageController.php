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
use App\Support\RecordSeoForHead;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function search(Request $request, string $locale)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'query' => $q,
                'results' => [],
            ]);
        }

        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q).'%';

        $services = Service::query()
            ->where(fn ($query) => $this->whereTranslatedLike($query, ['title', 'short_teaser', 'text_content'], $locale, $like)
                ->orWhere('slug', 'like', $like))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Service $service): array => $this->searchResult(
                type: __('messages.nav.services'),
                title: $this->localizedField($service, 'title', $locale),
                excerpt: $this->localizedField($service, 'short_teaser', $locale) ?: $this->localizedField($service, 'text_content', $locale),
                url: route('services.show', ['locale' => $locale, 'slug' => $service->slug ?? $service->id]),
                image: $service->cover_photo ? Storage::disk('public')->url($service->cover_photo) : asset('assets/img/projects/3/1.jpg'),
            ));

        $projects = Project::query()
            ->where(fn ($query) => $this->whereTranslatedLike($query, ['title', 'client', 'location', 'status_text', 'text_content'], $locale, $like)
                ->orWhere('slug', 'like', $like))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Project $project): array => $this->searchResult(
                type: __('messages.nav.projects'),
                title: $this->localizedField($project, 'title', $locale),
                excerpt: $this->localizedField($project, 'client', $locale)
                    ?: $this->localizedField($project, 'location', $locale)
                    ?: $this->localizedField($project, 'text_content', $locale),
                url: route('projects.show', ['locale' => $locale, 'slug' => $project->slug ?? $project->id]),
                image: $this->projectSearchImage($project),
            ));

        $news = News::query()
            ->where(fn ($query) => $this->whereTranslatedLike($query, ['title', 'teaser', 'text_content'], $locale, $like)
                ->orWhere('slug', 'like', $like))
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(5)
            ->get()
            ->map(fn (News $newsItem): array => $this->searchResult(
                type: __('messages.nav.news'),
                title: $this->localizedField($newsItem, 'title', $locale),
                excerpt: $this->localizedField($newsItem, 'teaser', $locale) ?: $this->localizedField($newsItem, 'text_content', $locale),
                url: route('news.show', ['locale' => $locale, 'slug' => $newsItem->slug ?? $newsItem->id]),
                image: $newsItem->cover_photo ? Storage::disk('public')->url($newsItem->cover_photo) : asset('assets/img/blog/1.jpg'),
            ));

        return response()->json([
            'query' => $q,
            'results' => $services
                ->concat($projects)
                ->concat($news)
                ->take(12)
                ->values(),
        ]);
    }

    private function whereTranslatedLike($query, array $fields, string $locale, string $like)
    {
        $locales = array_values(array_unique([$locale, 'en', 'ka']));

        foreach ($fields as $field) {
            foreach ($locales as $searchLocale) {
                $query->orWhere($field.'->'.$searchLocale, 'like', $like);
            }
        }

        return $query;
    }

    private function localizedField($model, string $field, string $locale): string
    {
        if (method_exists($model, 'getTranslation')) {
            foreach (array_values(array_unique([$locale, 'en', 'ka'])) as $searchLocale) {
                $value = $model->getTranslation($field, $searchLocale, false);

                if (filled($value)) {
                    return (string) $value;
                }
            }
        }

        $value = $model->{$field} ?? '';

        return is_string($value) ? $value : '';
    }

    private function searchResult(string $type, string $title, string $excerpt, string $url, string $image): array
    {
        $title = html_entity_decode(trim(strip_tags($title)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $excerpt = html_entity_decode(trim(strip_tags($excerpt)), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return [
            'type' => $type,
            'title' => filled($title) ? $title : $type,
            'excerpt' => Str::limit($excerpt, 120),
            'url' => $url,
            'image' => $image,
        ];
    }

    private function projectSearchImage(Project $project): string
    {
        $gallery = is_array($project->gallery ?? null) ? $project->gallery : [];
        $imagePath = $gallery[0] ?? $project->cover_photo;

        return $imagePath
            ? Storage::disk('public')->url($imagePath)
            : asset('assets/img/projects/3/1.jpg');
    }

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

        return view('pages.service-single', [
            'service' => $service,
            'prevService' => $prevService,
            'nextService' => $nextService,
            'relatedServices' => $relatedServices,
            'recordSeoForHead' => RecordSeoForHead::forService($service),
        ]);
    }

    public function projects()
    {
        $settings = Page::projectsListingPageContent();

        $projects = Page::orderedProjects($settings['projects']);
        if ($projects->isEmpty()) {
            $projects = Project::with(['categories', 'status'])
                ->orderByRaw('COALESCE(sort_order, 2147483647) ASC')
                ->orderByDesc('created_at')
                ->get();
        } else {
            // If a curated list exists, show it first but still include the remaining projects.
            $curatedIds = $projects->pluck('id')->all();
            $rest = Project::with(['categories', 'status'])
                ->whereNotIn('id', $curatedIds)
                ->orderByRaw('COALESCE(sort_order, 2147483647) ASC')
                ->orderByDesc('created_at')
                ->get();

            // Ensure relations exist for filters/classes.
            $projects->load(['categories', 'status']);
            $projects = $projects->concat($rest)->values();
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

        $prevProject = Project::query()
            ->whereRaw('COALESCE(sort_order, 2147483647) < COALESCE(?, 2147483647)', [$project->sort_order])
            ->orderByRaw('COALESCE(sort_order, 2147483647) DESC')
            ->orderByDesc('id')
            ->first();
        $nextProject = Project::query()
            ->whereRaw('COALESCE(sort_order, 2147483647) > COALESCE(?, 2147483647)', [$project->sort_order])
            ->orderByRaw('COALESCE(sort_order, 2147483647) ASC')
            ->orderBy('id')
            ->first();

        $relatedProjects = Project::with('categories')
            ->where('id', '!=', $project->id)
            ->orderByRaw('COALESCE(sort_order, 2147483647) ASC')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        return view('pages.project-single', [
            'project' => $project,
            'prevProject' => $prevProject,
            'nextProject' => $nextProject,
            'relatedProjects' => $relatedProjects,
            'recordSeoForHead' => RecordSeoForHead::forProject($project),
        ]);
    }

    public function partners()
    {
        $settings = Page::partnersPageContent();

        $partnerLogos = Page::orderedPartnerLogos($settings['partner_logo_ids']);

        $headerBg = $settings['cover_image']
            ? Storage::disk('public')->url($settings['cover_image'])
            : asset('assets/img/background/page-header-bg-8.jpg');
        $pageTitle = filled($settings['title'] ?? null) ? (string) $settings['title'] : 'Partners';

        return view('pages.partners', compact('partnerLogos', 'headerBg', 'pageTitle'));
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

        return view('pages.news-single', [
            'newsItem' => $newsItem,
            'recordSeoForHead' => RecordSeoForHead::forNews($newsItem),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function legalDocument(string $locale, string $document)
    {
        $allowed = ['terms', 'privacy', 'cookies'];
        if (! in_array($document, $allowed, true)) {
            abort(404);
        }

        $content = Page::legalPagesContent();
        $body = (string) ($content[$document] ?? '');

        $pageTitle = match ($document) {
            'terms' => __('messages.legal.terms_title'),
            'privacy' => __('messages.legal.privacy_title'),
            'cookies' => __('messages.legal.cookies_title'),
            default => '',
        };

        $plain = trim(strip_tags($body));
        $metaDescription = $plain !== ''
            ? Str::limit($plain, 160)
            : __('messages.legal.meta_fallback');

        $recordSeoForHead = [
            'meta_title' => $pageTitle.' - Ef',
            'meta_description' => $metaDescription,
            'og_title' => $pageTitle,
            'og_description' => $metaDescription,
            'og_image' => null,
        ];

        return view('pages.legal-document', [
            'document' => $document,
            'pageTitle' => $pageTitle,
            'body' => $body,
            'recordSeoForHead' => $recordSeoForHead,
        ]);
    }
}
