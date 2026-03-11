<?php

namespace App\Http\Controllers;

use App\Models\FuelRecord;
use App\Models\Machine;
use App\Models\Maintenance;
use App\Models\Operator;
use App\Models\WorkLog;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $this->syncOverdueMaintenances();

        $machines = Machine::query()->get();
        $maintenancesWithAlert = Maintenance::query()
            ->with('machine')
            ->withAlerts()
            ->latest('scheduled_for')
            ->get();

        $preventiveAlerts = $machines
            ->filter(fn (Machine $machine): bool => $machine->needsPreventiveMaintenance())
            ->values();

        $topOperationalCosts = $this->topOperationalCosts();

        return view('dashboard.index', [
            'machinesTotal' => $machines->count(),
            'activeMachines' => $machines->where('status', 'active')->count(),
            'machinesInMaintenance' => $machines->where('status', 'maintenance')->count(),
            'operatorsTotal' => Operator::query()->where('active', true)->count(),
            'workedHoursMonth' => (float) WorkLog::query()
                ->whereMonth('started_at', now()->month)
                ->whereYear('started_at', now()->year)
                ->sum('hours_worked'),
            'fuelCostMonth' => (float) FuelRecord::query()
                ->whereMonth('fueled_at', now()->month)
                ->whereYear('fueled_at', now()->year)
                ->sum('total_cost'),
            'maintenanceCostMonth' => (float) Maintenance::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('cost'),
            'maintenancesWithAlert' => $maintenancesWithAlert,
            'preventiveAlerts' => $preventiveAlerts,
            'topOperationalCosts' => $topOperationalCosts,
        ]);
    }

    private function syncOverdueMaintenances(): void
    {
        Maintenance::query()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->whereDate('scheduled_for', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }

    private function topOperationalCosts(): Collection
    {
        return Machine::query()
            ->withSum('workLogs as total_hours', 'hours_worked')
            ->withSum('fuelRecords as fuel_cost', 'total_cost')
            ->withSum('maintenances as maintenance_cost', 'cost')
            ->get()
            ->map(function (Machine $machine): Machine {
                $fuel = (float) ($machine->fuel_cost ?? 0);
                $maintenance = (float) ($machine->maintenance_cost ?? 0);
                $hours = (float) ($machine->total_hours ?? 0);

                $machine->setAttribute('operational_cost', round($fuel + $maintenance, 2));
                $machine->setAttribute(
                    'cost_per_hour',
                    $hours > 0 ? round(($fuel + $maintenance) / $hours, 2) : 0
                );

                return $machine;
            })
            ->sortByDesc('operational_cost')
            ->take(5)
            ->values();
    }
}
