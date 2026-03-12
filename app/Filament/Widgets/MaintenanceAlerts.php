<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MaintenanceAlerts extends BaseWidget
{
    protected static ?string $heading = 'Alertas de manutenção';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->with('machine')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('service_name')
                    ->label('Serviço')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Máquina'),

                Tables\Columns\TextColumn::make('scheduled_for')
                    ->label('Prevista para')
                    ->date('d/m/Y')
                    ->placeholder('Sem data'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'danger'  => 'overdue',
                    ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('ver')
                    ->url(fn ($record) => route('filament.admin.resources.maintenances.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
