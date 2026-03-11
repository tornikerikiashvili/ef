<?php

namespace App\Filament\Resources\TextBlocks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TextBlockForm
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

                Section::make('Image')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image')
                            ->directory('text_blocks')
                            ->image()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(1),
            ]);
    }

    /**
     * @return array<int, \Filament\Forms\Components\TextInput>
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
        ];
    }
}
