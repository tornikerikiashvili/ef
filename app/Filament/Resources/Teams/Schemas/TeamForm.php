<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TeamForm
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
                            ->label('Photo')
                            ->directory('team')
                            ->image()
                            ->columnSpanFull(),
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
            RichEditor::make('text_content')
                ->label('Text information')
                ->columnSpanFull(),
        ];
    }
}
