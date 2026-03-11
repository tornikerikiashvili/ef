<?php

namespace App\Filament\Resources\TextBlocks;

use App\Filament\Resources\TextBlocks\Pages\CreateTextBlock;
use App\Filament\Resources\TextBlocks\Pages\EditTextBlock;
use App\Filament\Resources\TextBlocks\Pages\ListTextBlocks;
use App\Filament\Resources\TextBlocks\Schemas\TextBlockForm;
use App\Filament\Resources\TextBlocks\Tables\TextBlocksTable;
use App\Models\TextBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TextBlockResource extends Resource
{
    protected static ?string $model = TextBlock::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Text Blocks';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TextBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TextBlocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTextBlocks::route('/'),
            'create' => CreateTextBlock::route('/create'),
            'edit' => EditTextBlock::route('/{record}/edit'),
        ];
    }
}
