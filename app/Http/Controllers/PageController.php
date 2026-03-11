<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function serviceSingle(string $slug)
    {
        return view('pages.service-single', compact('slug'));
    }

    public function projects()
    {
        return view('pages.projects');
    }

    public function projectSingle(string $slug)
    {
        return view('pages.project-single', compact('slug'));
    }

    public function news()
    {
        return view('pages.news');
    }

    public function newsSingle(string $slug)
    {
        return view('pages.news-single', compact('slug'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
