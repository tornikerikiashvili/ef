<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\PartnerLogo;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;

class PageController extends Controller
{
    public function home()
    {
        $featuredServices = Service::featuredInHero()
            ->orderBy('hero_order')
            ->get();

        $featuredProjects = Project::featured()
            ->orderBy('featured_order')
            ->get();

        $featuredNews = News::featured()
            ->orderBy('featured_order')
            ->get();

        $partnerLogos = PartnerLogo::orderBy('id')->get();

        return view('pages.home', compact('featuredServices', 'featuredProjects', 'featuredNews', 'partnerLogos'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        $services = Service::orderBy('created_at', 'desc')->get();
        $servicesPageCover = SiteSetting::getValue('services_page_cover');

        return view('pages.services', compact('services', 'servicesPageCover'));
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
        $projects = Project::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('name')->get();

        return view('pages.projects', compact('projects', 'categories'));
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
        $news = News::with('newsCategory')->orderBy('published_at', 'desc')->get();
        $newsPageCover = SiteSetting::getValue('news_page_cover');

        return view('pages.news', compact('news', 'newsPageCover'));
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
