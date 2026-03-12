<?php

namespace App\Filament\Resources\Maintenances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaintenancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('machine.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('service_name')
                    ->searchable(),
                TextColumn::make('business_unit')
                    ->searchable(),
                TextColumn::make('workshop')
                    ->searchable(),
                TextColumn::make('movement')
                    ->searchable(),
                TextColumn::make('reason')
                    ->searchable(),
                TextColumn::make('odometer_km')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('origin')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('scheduled_for')
                    ->date()
                    ->sortable(),
                TextColumn::make('performed_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('hour_meter')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('vendor')
                    ->searchable(),
                TextColumn::make('next_due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('next_due_hour_meter')
                    ->numeric()
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
