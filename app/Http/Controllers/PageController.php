<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\PartnerLogo;
use App\Models\Project;
use App\Models\Service;

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

        return view('pages.services', compact('services'));
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

        return view('pages.service-single', compact('service'));
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

        return view('pages.project-single', compact('project', 'prevProject', 'nextProject'));
    }

    public function news()
    {
        $news = News::with('newsCategory')->orderBy('published_at', 'desc')->get();

        return view('pages.news', compact('news'));
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
