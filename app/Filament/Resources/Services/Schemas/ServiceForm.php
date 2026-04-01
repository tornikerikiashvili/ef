<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        $locales = config('cms.supported_locales', ['en', 'ka']);

        return $schema
            ->columns(3)
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
                    ->statePath('resourceTranslations')
                    ->columnSpan(2),

                Section::make('Options')
                    ->schema([
                        TextInput::make('slug')
                            ->label('URL slug')
                            ->maxLength(255)
                            ->helperText('Leave empty to auto-generate from English title. Used in URLs: /services/your-slug'),
                    ])
                    ->columnSpan(1),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_photo')
                            ->label('Cover photo')
                            ->disk('public')
                            ->directory('services')
                            ->visibility('public')
                            ->image()
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->disk('public')
                            ->directory('services')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),

                Section::make('Homepage Hero')
                    ->description('Show this service in the homepage hero slider.')
                    ->schema([
                        Toggle::make('is_featured_in_hero')
                            ->label('Featured in hero slider')
                            ->default(false),
                        TextInput::make('hero_order')
                            ->label('Hero order')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Lower = first')
                            ->helperText('Optional. Lower numbers appear first.'),
                    ])
                    ->columnSpan(1),
            ]);
    }

    /**
     * @return array<int, \Filament\Forms\Components\TextInput|\Filament\Forms\Components\RichEditor>
     */
    protected static function translatableFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),
            TextInput::make('short_teaser')
                ->label('Short teaser')
                ->maxLength(65535)
                ->columnSpanFull(),
            RichEditor::make('text_content')
                ->label('Text content')
                ->columnSpanFull(),
        ];
    }
}
