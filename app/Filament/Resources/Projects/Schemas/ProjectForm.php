<?php

namespace App\Filament\Resources\Projects\Schemas;

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
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
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
                            ->directory('projects')
                            ->image()
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Gallery')
                            ->directory('projects')
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
