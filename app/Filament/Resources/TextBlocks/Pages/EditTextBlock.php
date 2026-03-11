<?php

namespace App\Filament\Resources\TextBlocks\Pages;

use App\Filament\Resources\TextBlocks\TextBlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTextBlock extends EditRecord
{
    protected static string $resource = TextBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
