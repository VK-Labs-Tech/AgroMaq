<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function operationalCosts(Request $request)
    {
        $from = $request->date('from');
        $to = $request->date('to');

        $machines = Machine::query()
            ->withSum([
                'workLogs as total_hours' => function ($query) use ($from, $to): void {
                    if ($from) {
                        $query->whereDate('started_at', '>=', $from->toDateString());
                    }

                    if ($to) {
                        $query->whereDate('started_at', '<=', $to->toDateString());
                    }
                },
            ], 'hours_worked')
            ->withSum([
                'fuelRecords as fuel_cost' => function ($query) use ($from, $to): void {
                    if ($from) {
                        $query->whereDate('fueled_at', '>=', $from->toDateString());
                    }

                    if ($to) {
                        $query->whereDate('fueled_at', '<=', $to->toDateString());
                    }
                },
            ], 'total_cost')
            ->withSum([
                'maintenances as maintenance_cost' => function ($query) use ($from, $to): void {
                    if ($from) {
                        $query->whereDate('performed_at', '>=', $from->toDateString());
                    }

                    if ($to) {
                        $query->whereDate('performed_at', '<=', $to->toDateString());
                    }
                },
            ], 'cost')
            ->orderBy('name')
            ->get();

        $report = $this->buildCostReport($machines);

        return view('reports.operational-costs', [
            'machines' => $report,
            'from' => $from,
            'to' => $to,
            'totals' => [
                'hours' => $report->sum('total_hours'),
                'fuel_cost' => $report->sum('fuel_cost'),
                'maintenance_cost' => $report->sum('maintenance_cost'),
                'operational_cost' => $report->sum('operational_cost'),
            ],
        ]);
    }

    private function buildCostReport(Collection $machines): Collection
    {
        return $machines->map(function (Machine $machine): Machine {
            $hours = (float) ($machine->total_hours ?? 0);
            $fuel = (float) ($machine->fuel_cost ?? 0);
            $maintenance = (float) ($machine->maintenance_cost ?? 0);
            $operationalCost = round($fuel + $maintenance, 2);

            $machine->setAttribute('total_hours', round($hours, 2));
            $machine->setAttribute('fuel_cost', round($fuel, 2));
            $machine->setAttribute('maintenance_cost', round($maintenance, 2));
            $machine->setAttribute('operational_cost', $operationalCost);
            $machine->setAttribute('cost_per_hour', $hours > 0 ? round($operationalCost / $hours, 2) : 0);

            return $machine;
        });
    }
}
