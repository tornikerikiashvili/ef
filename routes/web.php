<?php

use App\Http\Controllers\PageController;
use App\Http\Middleware\SetAppLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/'.config('cms.supported_locales')[0]);
});

Route::prefix('{locale}')
    ->where(['locale' => 'en|ka'])
    ->middleware(SetAppLocale::class)
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/services', [PageController::class, 'services'])->name('services');
        Route::get('/services/{slug}', [PageController::class, 'serviceSingle'])->name('services.show');
        Route::get('/projects', [PageController::class, 'projects'])->name('projects');
        Route::get('/projects/{slug}', [PageController::class, 'projectSingle'])->name('projects.show');
        Route::get('/partners', [PageController::class, 'partners'])->name('partners');
        Route::get('/news', [PageController::class, 'news'])->name('news');
        Route::get('/news/{slug}', [PageController::class, 'newsSingle'])->name('news.show');
        Route::get('/contact', [PageController::class, 'contact'])->name('contact');
        Route::get('/legal/{document}', [PageController::class, 'legalDocument'])
            ->whereIn('document', ['terms', 'privacy', 'cookies'])
            ->name('legal.document');
    });
