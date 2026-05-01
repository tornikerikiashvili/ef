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
            $projects = Project::with(['category', 'status'])->orderBy('created_at', 'desc')->get();
        } else {
            // Ensure relations exist for filters/classes.
            $projects->load(['category', 'status']);
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
        $project = Project::with(['category', 'status'])
            ->where('slug', $slug)
            ->first();

        if (! $project && ctype_digit((string) $slug)) {
            $project = Project::with(['category', 'status'])->find((int) $slug);
        }

        if (! $project) {
            abort(404);
        }

        $prevProject = Project::where('id', '<', $project->id)->orderBy('id', 'desc')->first();
        $nextProject = Project::where('id', '>', $project->id)->orderBy('id')->first();

        $relatedProjects = Project::with('category')
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

            $news = $newsQuery->get();
        } else {
            $news = Page::orderedNews($settings['news']);
            if ($news->isEmpty()) {
                $news = News::with('newsCategory')->orderBy('published_at', 'desc')->get();
            } else {
                $news->load('newsCategory');
            }
        }

        $fallbackCover = SiteSetting::getValue('news_page_cover');
        $fallbackCover = is_array($fallbackCover) ? (reset($fallbackCover) ?: null) : $fallbackCover;

        $headerBg = $settings['cover_image']
            ? Storage::disk('public')->url($settings['cover_image'])
            : ($fallbackCover ? Storage::disk('public')->url($fallbackCover) : asset('assets/img/background/page-header-bg-8.jpg'));

        $newsPageTitle = $settings['title'];

        return view('pages.news', compact('news', 'headerBg', 'newsPageTitle', 'q', 'range'));
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
