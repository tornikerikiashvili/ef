<?php

namespace App\Filament\Resources\TextBlocks\Pages;

use App\Filament\Resources\TextBlocks\TextBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTextBlocks extends ListRecords
{
    protected static string $resource = TextBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
