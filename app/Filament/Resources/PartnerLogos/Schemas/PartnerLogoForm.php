<?php

namespace App\Filament\Resources\PartnerLogos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PartnerLogoForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $schema
            ->components([
                Group::make([
                    Tabs::make('Translations')
                        ->tabs(
                            collect($locales)->map(fn (string $locale) => Tab::make(Str::ucfirst($locale))
                                ->statePath($locale)
                                ->schema(static::translatableFields())
                            )->all()
                        ),
                ])
                    ->statePath('resourceTranslations'),
            ]);
    }

    /**
     * @return array<int, \Filament\Forms\Components\TextInput|\Filament\Forms\Components\FileUpload>
     */
    protected static function translatableFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),
            TextInput::make('link')
                ->label('Link (URL)')
                ->url()
                ->maxLength(65535),
            FileUpload::make('logo_white')
                ->label('Logo (white / default)')
                ->directory('partner_logos')
                ->image()
                ->helperText('Used as default logo.'),
            FileUpload::make('logo_colorful')
                ->label('Logo (colorful / hover)')
                ->directory('partner_logos')
                ->image()
                ->helperText('Shown on hover.'),
        ];
    }
}
