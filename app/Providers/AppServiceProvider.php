<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('use-translation-manager', function (?User $user): bool {
            return $user !== null;
        });

        View::composer('layouts.app', function (\Illuminate\View\View $view): void {
            $name = request()->route()?->getName();
            $map = [
                'home' => Page::KEY_HOME_PAGE,
                'about' => Page::KEY_ABOUT_PAGE,
                'services' => Page::KEY_SERVICES_LISTING_PAGE,
                'projects' => Page::KEY_PROJECTS_LISTING_PAGE,
                'partners' => Page::KEY_PARTNERS_PAGE,
                'news' => Page::KEY_NEWS_LISTING_PAGE,
                'contact' => Page::KEY_CONTACT_PAGE,
            ];
            $key = is_string($name) && isset($map[$name]) ? $map[$name] : null;
            $view->with('cmsPageSeo', $key !== null ? Page::resolvedPublicSeoForPageKey($key) : null);
        });
    }
}
