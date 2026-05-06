<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->orderByRaw('COALESCE(sort_order, 2147483647) ASC')
                ->orderByDesc('id'))
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('sort_order')
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
                    BulkAction::make('setSortOrder')
                        ->label('Set order (selected)')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('start')
                                ->label('Start from')
                                ->numeric()
                                ->default(1)
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('step')
                                ->label('Step')
                                ->numeric()
                                ->default(1)
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $start = max(0, (int) ($data['start'] ?? 1));
                            $step = max(1, (int) ($data['step'] ?? 1));

                            $i = 0;
                            foreach ($records->sortBy('id') as $record) {
                                $record->update(['sort_order' => $start + ($i * $step)]);
                                $i++;
                            }
                        }),
                    BulkAction::make('clearSortOrder')
                        ->label('Clear order (selected)')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['sort_order' => null])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
