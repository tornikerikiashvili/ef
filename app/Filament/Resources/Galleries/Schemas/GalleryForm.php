<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gallery')
                    ->description('Internal name for admins; add one or more images (reorderable).')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->maxLength(255),
                        FileUpload::make('images')
                            ->label('Images')
                            ->disk('public')
                            ->directory('galleries')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
