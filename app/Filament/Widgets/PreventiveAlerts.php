<?php

namespace App\Filament\Widgets;

use App\Models\Machine;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PreventiveAlerts extends BaseWidget
{
    protected static ?string $heading = 'Preventiva por horímetro';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Machine::query()
                    ->whereNotNull('preventive_interval')
                    ->whereColumn('hour_meter', '>=', 'preventive_interval')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Máquina')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('hour_meter')
                    ->label('Horímetro atual')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.'),

                Tables\Columns\TextColumn::make('preventive_interval')
                    ->label('Intervalo preventivo'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'danger'  => 'overdue',
                    ]),
            ]);
    }
}
