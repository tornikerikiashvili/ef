<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                TextColumn::make('featured_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('categories_summary')
                    ->label('Categories')
                    ->toggleable()
                    ->sortable(query: function ($query, string $direction): void {
                        $query->withCount('categories')->orderBy('categories_count', $direction);
                    })
                    ->getStateUsing(fn (Project $record): string => $record->categories->pluck('name')->unique()->filter()->implode(', ') ?: '—'),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
