<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::query()
            ->orderBy('name')
            ->paginate(12);

        return view('machines.index', [
            'machines' => $machines,
        ]);
    }

    public function create()
    {
        return view('machines.create', [
            'machine' => new Machine(),
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $machine = Machine::query()->create($this->validated($request));

        return redirect()
            ->route('machines.show', $machine)
            ->with('success', 'Maquina cadastrada com sucesso.');
    }

    public function show(Machine $machine)
    {
        $machine->load([
            'workLogs' => fn ($query) => $query->latest('started_at')->limit(10),
            'fuelRecords' => fn ($query) => $query->latest('fueled_at')->limit(10),
            'maintenances' => fn ($query) => $query->latest('scheduled_for')->limit(10),
        ]);

        return view('machines.show', [
            'machine' => $machine,
        ]);
    }

    public function edit(Machine $machine)
    {
        return view('machines.edit', [
            'machine' => $machine,
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Machine $machine)
    {
        $machine->update($this->validated($request, $machine));

        return redirect()
            ->route('machines.show', $machine)
            ->with('success', 'Maquina atualizada com sucesso.');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()
            ->route('machines.index')
            ->with('success', 'Maquina removida com sucesso.');
    }

    private function validated(Request $request, ?Machine $machine = null): array
    {
        $machineId = $machine?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'asset_tag' => ['required', 'string', 'max:80', Rule::unique('machines', 'asset_tag')->ignore($machineId)],
            'type' => ['required', Rule::in($this->types())],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'manufacture_year' => ['nullable', 'integer', 'min:1980', 'max:' . now()->year],
            'serial_number' => ['nullable', 'string', 'max:120', Rule::unique('machines', 'serial_number')->ignore($machineId)],
            'plate' => ['nullable', 'string', 'max:20', Rule::unique('machines', 'plate')->ignore($machineId)],
            'status' => ['required', Rule::in($this->statuses())],
            'hour_meter' => ['required', 'numeric', 'min:0'],
            'preventive_interval_hours' => ['required', 'numeric', 'min:1'],
            'last_preventive_hour_meter' => ['required', 'numeric', 'min:0'],
            'last_preventive_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function types(): array
    {
        return ['Trator', 'Colheitadeira', 'Pulverizador', 'Plantadeira', 'Caminhao', 'Retroescavadeira'];
    }

    private function statuses(): array
    {
        return ['active', 'inactive', 'maintenance'];
    }
}
