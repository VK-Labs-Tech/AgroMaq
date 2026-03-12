<?php

namespace App\Filament\Resources\FuelRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FuelRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine.name')
                    ->searchable(),
                TextColumn::make('operator.name')
                    ->searchable(),
                TextColumn::make('fueled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('hour_meter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('liters')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_liter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('supplier')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
