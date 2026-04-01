<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                        TextInput::make('slug')
                            ->label('URL slug')
                            ->maxLength(255)
                            ->helperText('Leave empty to auto-generate from English title. Used in URLs: /news/your-slug'),
                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->displayFormat('d M Y H:i')
                            ->native(false)
                            ->nullable(),
                        Select::make('news_category_id')
                            ->label('News Category')
                            ->relationship('newsCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columnSpan(1),

                Section::make('Featured')
                    ->description('Show this news in the homepage news section.')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Featured on homepage')
                            ->default(false),
                        TextInput::make('featured_order')
                            ->label('Featured order')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Lower = first')
                            ->helperText('Optional. Lower numbers appear first.'),
                    ])
                    ->columnSpan(1),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_photo')
                            ->label('Cover image')
                            ->disk('public')
                            ->directory('news')
                            ->visibility('public')
                            ->image()
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->disk('public')
                            ->directory('news')
                            ->visibility('public')
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
