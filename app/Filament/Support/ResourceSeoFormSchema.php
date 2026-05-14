<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Str;

final class ResourceSeoFormSchema
{
    /**
     * SEO block: per-locale meta + OG text (via {@code resourceTranslations}), shared {@code og_image}.
     */
    public static function section(string $tabsKey, string $ogDirectory): Section
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return Section::make('SEO')
            ->description('Optional. If empty, the public page uses the record title and a short excerpt from teaser/body for meta and Open Graph.')
            ->columnSpanFull()
            ->schema([
                Group::make([
                    Tabs::make($tabsKey)
                        ->tabs(
                            collect($locales)->map(fn (string $locale) => Tab::make(Str::ucfirst($locale))
                                ->statePath($locale)
                                ->schema([
                                    Section::make('Meta fields')
                                        ->schema([
                                            TextInput::make('meta_title')
                                                ->label('Meta title')
                                                ->maxLength(255),
                                            Textarea::make('meta_description')
                                                ->label('Meta description')
                                                ->rows(3)
                                                ->maxLength(65535)
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(1),
                                    Section::make('Open Graph')
                                        ->schema([
                                            TextInput::make('og_title')
                                                ->label('OG title')
                                                ->maxLength(255),
                                            Textarea::make('og_description')
                                                ->label('OG description')
                                                ->rows(3)
                                                ->maxLength(65535)
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(1),
                                ])
                            )->all()
                        )
                        ->columns(1)
                        ->extraAttributes([
                            'style' => 'background-color: #fff7ef',
                        ]),
                ])
                    ->statePath('resourceTranslations'),
                FileUpload::make('og_image')
                    ->label('OG image (shared for all languages)')
                    ->disk('public')
                    ->directory($ogDirectory.'/seo')
                    ->visibility('public')
                    ->image()
                    ->columnSpanFull(),
            ]);
    }
}
