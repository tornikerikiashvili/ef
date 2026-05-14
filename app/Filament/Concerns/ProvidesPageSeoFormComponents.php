<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Str;

trait ProvidesPageSeoFormComponents
{
    /**
     * @return list<Section>
     */
    protected function pageSeoLocaleTabFields(): array
    {
        return [
            Section::make('Meta fields')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Title')
                        ->maxLength(255),
                    TextInput::make('meta_description')
                        ->label('Description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->columns(1),
            Section::make('Open Graph')
                ->schema([
                    TextInput::make('og_title')
                        ->label('OG:Title')
                        ->maxLength(255),
                    TextInput::make('og_description')
                        ->label('OG:Description')
                        ->maxLength(65535),
                    FileUpload::make('og_image')
                        ->label('OG:Image')
                        ->disk('public')
                        ->directory('pages/seo/og')
                        ->visibility('public')
                        ->image()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    protected function pageSeoTabs(string $pageKey): Tabs
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return Tabs::make($pageKey.'_seo_locales')
            ->tabs(
                collect($locales)->map(fn (string $locale) => Tab::make(Str::upper($locale))
                    ->statePath('seo.locales.'.$locale)
                    ->schema($this->pageSeoLocaleTabFields())
                )->all()
            )
            ->columns(1)
            ->extraAttributes([
                'style' => 'background-color: #fff7ef',
            ]);
    }

    /**
     * SEO block for forms where a parent {@see Group} already uses {@code ->statePath($pageKey)}.
     */
    protected function pageSeoSectionBlock(string $pageKey): Section
    {
        return Section::make('SEO')
            ->description('Search engine meta tags and Open Graph preview (optional), per language.')
            ->schema([
                $this->pageSeoTabs($pageKey),
            ])
            ->columns(1)
            ->collapsible();
    }

    /**
     * SEO block for the contact page form (no single wrapping group around all fields).
     */
    protected function pageSeoSectionForStandaloneStatePath(string $pageKey): Section
    {
        return Section::make('SEO')
            ->description('Search engine meta tags and Open Graph preview (optional), per language.')
            ->schema([
                Group::make([
                    $this->pageSeoTabs($pageKey),
                ])
                    ->statePath($pageKey),
            ])
            ->columns(1)
            ->collapsible();
    }
}
