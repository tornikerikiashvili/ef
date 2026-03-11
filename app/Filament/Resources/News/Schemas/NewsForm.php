<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
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
                        Select::make('news_category_id')
                            ->label('News Category')
                            ->relationship('newsCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columnSpan(1),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_photo')
                            ->label('Cover image')
                            ->directory('news')
                            ->image()
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->directory('news')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
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
            TextInput::make('teaser')
                ->label('Teaser')
                ->maxLength(65535)
                ->columnSpanFull(),
            RichEditor::make('text_content')
                ->label('Text')
                ->columnSpanFull(),
        ];
    }
}
