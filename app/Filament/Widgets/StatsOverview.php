<?php

namespace App\Filament\Widgets;

use App\Models\Machine;
use App\Models\Operator;
use App\Models\WorkLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $horasNoMes = WorkLog::whereMonth('created_at', now()->month)->sum('hours_worked');
        return [
            Stat::make('Máquinas cadastradas', Machine::count())
                ->description(Machine::where('status', 'active')->count() . ' ativas')
                ->color('success')
                ->icon('heroicon-o-wrench-screwdriver'),

            Stat::make('Em manutenção', Machine::where('status', 'maintenance')->count())
                ->description('monitorar disponibilidade')
                ->color('warning')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Horas no mês', number_format($horasNoMes, 2, ',', '.') . 'h')
                ->description('produtividade da frota')
                ->color('info')
                ->icon('heroicon-o-clock'),

            Stat::make('Operadores ativos', Operator::where('active', true)->count())
                ->description('equipes disponíveis')
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
