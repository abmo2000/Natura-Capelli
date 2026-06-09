<?php

namespace App\Filament\Resources\Locations\Tables;

use App\Filament\Resources\Locations\Pages\ListLocations;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHas('translations', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('price')
                    ->label('Shipping Price')
                    ->numeric()
                    ->sortable(),

                ToggleColumn::make('has_discussion_for_delivery')
                    ->label('Open For Discuss'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ListLocations::editAction(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
