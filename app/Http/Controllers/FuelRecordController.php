<?php

namespace App\Http\Controllers;

use App\Models\FuelRecord;
use App\Models\Machine;
use App\Models\Operator;
use Illuminate\Http\Request;

class FuelRecordController extends Controller
{
    public function index()
    {
        $fuelRecords = FuelRecord::query()
            ->with(['machine', 'operator'])
            ->latest('fueled_at')
            ->paginate(15);

        return view('fuel-records.index', [
            'fuelRecords' => $fuelRecords,
        ]);
    }

    public function create()
    {
        return view('fuel-records.create', [
            'fuelRecord' => new FuelRecord(),
            'machines' => Machine::query()->orderBy('name')->get(),
            'operators' => Operator::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['total_cost'] = round((float) $validated['liters'] * (float) $validated['price_per_liter'], 2);

        $fuelRecord = FuelRecord::query()->create($validated);
        $this->syncMachineHourMeter($fuelRecord->machine, (float) $validated['hour_meter']);

        return redirect()
            ->route('fuel-records.index')
            ->with('success', 'Abastecimento registrado com sucesso.');
    }

    public function show(FuelRecord $fuelRecord)
    {
        $fuelRecord->load(['machine', 'operator']);

        return view('fuel-records.show', [
            'fuelRecord' => $fuelRecord,
        ]);
    }

    public function edit(FuelRecord $fuelRecord)
    {
        return view('fuel-records.edit', [
            'fuelRecord' => $fuelRecord,
            'machines' => Machine::query()->orderBy('name')->get(),
            'operators' => Operator::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FuelRecord $fuelRecord)
    {
        $validated = $this->validated($request);
        $validated['total_cost'] = round((float) $validated['liters'] * (float) $validated['price_per_liter'], 2);

        $fuelRecord->update($validated);
        $this->syncMachineHourMeter($fuelRecord->machine, (float) $validated['hour_meter']);

        return redirect()
            ->route('fuel-records.show', $fuelRecord)
            ->with('success', 'Abastecimento atualizado com sucesso.');
    }

    public function destroy(FuelRecord $fuelRecord)
    {
        $machine = $fuelRecord->machine;

        $fuelRecord->delete();
        $this->recalculateMachineHourMeter($machine);

        return redirect()
            ->route('fuel-records.index')
            ->with('success', 'Abastecimento removido com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'operator_id' => ['nullable', 'exists:operators,id'],
            'fueled_at' => ['required', 'date'],
            'hour_meter' => ['required', 'numeric', 'min:0'],
            'liters' => ['required', 'numeric', 'gt:0'],
            'price_per_liter' => ['required', 'numeric', 'gt:0'],
            'supplier' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function syncMachineHourMeter(Machine $machine, float $newHourMeter): void
    {
        if ($newHourMeter > (float) $machine->hour_meter) {
            $machine->update(['hour_meter' => $newHourMeter]);
        }
    }

    private function recalculateMachineHourMeter(Machine $machine): void
    {
        $maxWork = (float) ($machine->workLogs()->max('end_hour_meter') ?? 0);
        $maxFuel = (float) ($machine->fuelRecords()->max('hour_meter') ?? 0);
        $maxMaintenance = (float) ($machine->maintenances()->max('hour_meter') ?? 0);
        $lastPreventive = (float) ($machine->last_preventive_hour_meter ?? 0);

        $machine->update([
            'hour_meter' => max($maxWork, $maxFuel, $maxMaintenance, $lastPreventive),
        ]);
    }
}
