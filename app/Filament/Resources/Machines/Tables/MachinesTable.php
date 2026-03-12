<?php

namespace App\Filament\Resources\Machines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MachinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('asset_tag')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('brand')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('manufacture_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('plate')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('hour_meter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('preventive_interval_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_preventive_hour_meter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_preventive_date')
                    ->date()
                    ->sortable(),
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
