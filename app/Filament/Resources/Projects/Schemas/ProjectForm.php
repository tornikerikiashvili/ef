<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\ResourceSeoFormSchema;
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

class ProjectForm
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
                            ->helperText('Leave empty to auto-generate from title. Used in URLs: /projects/your-slug'),
                        Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        Select::make('status_id')
                            ->label('Status')
                            ->relationship('status', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columnSpan(1),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('cover_photo')
                            ->label('Cover image')
                            ->disk('public')
                            ->directory('projects')
                            ->visibility('public')
                            ->image()
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->disk('public')
                            ->directory('projects')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                        TextInput::make('video_url')
                            ->label('YouTube video URL')
                            ->maxLength(2048)
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('Paste a full URL (watch, embed, shorts, or youtu.be).'),
                        FileUpload::make('video_poster')
                            ->label('Video poster image')
                            ->disk('public')
                            ->directory('projects')
                            ->visibility('public')
                            ->image()
                            ->nullable()
                            ->columnSpanFull()
                            ->helperText('Optional thumbnail before play. If empty, the YouTube default thumbnail is used.'),
                    ])
                    ->columnSpanFull(),
                ResourceSeoFormSchema::section('project_seo_locales', 'projects'),
            ]);
    }

    /**
     * @return array<int, TextInput|RichEditor>
     */
    protected static function translatableFields(): array
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),
            TextInput::make('client')
                ->label('Client')
                ->maxLength(255),
            TextInput::make('area')
                ->label('Area (e.g. 2 300 sq.m.)')
                ->maxLength(255),
            TextInput::make('location')
                ->label('Location (e.g. Tbilisi, Georgia)')
                ->maxLength(255),
            TextInput::make('status_text')
                ->label('Status text (e.g. Completed in 2024, Ongoing)')
                ->maxLength(255)
                ->helperText('Optional display text; use Status dropdown in Options for filtering.'),
            RichEditor::make('text_content')
                ->label('Description / Project information')
                ->columnSpanFull(),
        ];
    }
}
