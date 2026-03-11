<?php

namespace App\Filament\Resources\PartnerLogos;

use App\Filament\Resources\PartnerLogos\Pages\CreatePartnerLogo;
use App\Filament\Resources\PartnerLogos\Pages\EditPartnerLogo;
use App\Filament\Resources\PartnerLogos\Pages\ListPartnerLogos;
use App\Filament\Resources\PartnerLogos\Schemas\PartnerLogoForm;
use App\Filament\Resources\PartnerLogos\Tables\PartnerLogosTable;
use App\Models\PartnerLogo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnerLogoResource extends Resource
{
    protected static ?string $model = PartnerLogo::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Partner Logos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PartnerLogoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerLogosTable::configure($table);
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
            'index' => ListPartnerLogos::route('/'),
            'create' => CreatePartnerLogo::route('/create'),
            'edit' => EditPartnerLogo::route('/{record}/edit'),
        ];
    }
}
