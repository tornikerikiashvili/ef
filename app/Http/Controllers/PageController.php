<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        return view('pages.home', compact('featuredServices', 'featuredProjects'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function serviceSingle(string $locale, string $slug)
    {
        return view('pages.service-single', compact('slug'));
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
        return view('pages.news');
    }

    public function newsSingle(string $locale, string $slug)
    {
        return view('pages.news-single', compact('slug'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
