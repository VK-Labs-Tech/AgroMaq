<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $this->syncOverdueMaintenances();

        $status = $request->query('status');

        $maintenances = Maintenance::query()
            ->with('machine')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('scheduled_for')
            ->paginate(15)
            ->withQueryString();

        return view('maintenances.index', [
            'maintenances' => $maintenances,
            'status' => $status,
            'statuses' => $this->statuses(),
        ]);
    }

    public function create()
    {
        return view('maintenances.create', [
            'maintenance' => new Maintenance(),
            'machines' => Machine::query()->orderBy('name')->get(),
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function preventiveLaunch(Request $request)
    {
        $machines = Machine::query()->orderBy('name')->get();

        $selectedMachineId = (int) ($request->query('machine_id') ?: ($machines->first()->id ?? 0));
        $selectedMachine = $machines->firstWhere('id', $selectedMachineId);

        $history = collect();

        if ($selectedMachine) {
            $history = Maintenance::query()
                ->where('machine_id', $selectedMachine->id)
                ->where('type', 'preventive')
                ->latest('scheduled_for')
                ->limit(40)
                ->get();
        }

        return view('maintenances.preventive-launch', [
            'machines' => $machines,
            'selectedMachine' => $selectedMachine,
            'history' => $history,
            'businessUnits' => $this->businessUnits(),
            'workshops' => $this->workshops(),
            'movements' => $this->movements(),
            'origins' => $this->origins(),
        ]);
    }

    public function storePreventiveLaunch(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'business_unit' => ['nullable', 'string', 'max:120'],
            'workshop' => ['nullable', 'string', 'max:120'],
            'mechanism' => ['required', 'string', 'max:120'],
            'movement' => ['required', 'string', 'max:120'],
            'reason' => ['nullable', 'string', 'max:120'],
            'odometer_km' => ['nullable', 'integer', 'min:0'],
            'hour_meter' => ['nullable', 'numeric', 'min:0'],
            'planned_exchange_date' => ['nullable', 'date'],
            'scheduled_exchange_date' => ['nullable', 'date'],
            'origin' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        $maintenance = Maintenance::query()->create([
            'machine_id' => $validated['machine_id'],
            'type' => 'preventive',
            'service_name' => $validated['mechanism'],
            'business_unit' => $validated['business_unit'] ?? null,
            'workshop' => $validated['workshop'] ?? null,
            'movement' => $validated['movement'],
            'reason' => $validated['reason'] ?? null,
            'odometer_km' => $validated['odometer_km'] ?? null,
            'origin' => $validated['origin'] ?? null,
            'status' => 'scheduled',
            'scheduled_for' => $validated['scheduled_exchange_date'] ?? null,
            'next_due_date' => $validated['planned_exchange_date'] ?? null,
            'hour_meter' => $validated['hour_meter'] ?? null,
            'cost' => 0,
            'description' => $validated['notes'] ?? null,
        ]);

        $this->applyMaintenanceEffects($maintenance);

        return redirect()
            ->route('maintenances.preventive-launch', ['machine_id' => $validated['machine_id']])
            ->with('success', 'Lancamento preventivo registrado com sucesso.');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        if ($validated['status'] === 'completed' && empty($validated['performed_at'])) {
            $validated['performed_at'] = now()->toDateString();
        }

        $maintenance = Maintenance::query()->create($validated);
        $this->applyMaintenanceEffects($maintenance);

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', 'Manutencao registrada com sucesso.');
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load('machine');

        return view('maintenances.show', [
            'maintenance' => $maintenance,
        ]);
    }

    public function edit(Maintenance $maintenance)
    {
        return view('maintenances.edit', [
            'maintenance' => $maintenance,
            'machines' => Machine::query()->orderBy('name')->get(),
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $this->validated($request);

        if ($validated['status'] === 'completed' && empty($validated['performed_at'])) {
            $validated['performed_at'] = now()->toDateString();
        }

        $maintenance->update($validated);
        $this->applyMaintenanceEffects($maintenance);

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', 'Manutencao atualizada com sucesso.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()
            ->route('maintenances.index')
            ->with('success', 'Manutencao removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'type' => ['required', Rule::in($this->types())],
            'service_name' => ['required', 'string', 'max:120'],
            'status' => ['required', Rule::in($this->statuses())],
            'scheduled_for' => ['nullable', 'date'],
            'performed_at' => ['nullable', 'date'],
            'hour_meter' => ['nullable', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'vendor' => ['nullable', 'string', 'max:120'],
            'next_due_date' => ['nullable', 'date'],
            'next_due_hour_meter' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function applyMaintenanceEffects(Maintenance $maintenance): void
    {
        $machine = $maintenance->machine;

        if (!empty($maintenance->hour_meter) && (float) $maintenance->hour_meter > (float) $machine->hour_meter) {
            $machine->update(['hour_meter' => (float) $maintenance->hour_meter]);
        }

        if ($maintenance->status === 'completed' && $maintenance->type === 'preventive') {
            $machine->update([
                'last_preventive_hour_meter' => $maintenance->hour_meter ?: $machine->hour_meter,
                'last_preventive_date' => $maintenance->performed_at ?: now()->toDateString(),
            ]);
        }
    }

    private function syncOverdueMaintenances(): void
    {
        Maintenance::query()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->whereDate('scheduled_for', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }

    private function types(): array
    {
        return ['preventive', 'corrective'];
    }

    private function statuses(): array
    {
        return ['scheduled', 'in_progress', 'completed', 'overdue'];
    }

    private function businessUnits(): array
    {
        return [
            'DISTRIBUIDORES',
            'MATRIZ',
            'FILIAL - NORTE',
            'FILIAL - SUL',
        ];
    }

    private function workshops(): array
    {
        return [
            'ALMOXARIFADO - SPZ',
            'OFICINA MECANICA',
            'OFICINA TERCEIRIZADA',
            'CAMPO',
        ];
    }

    private function movements(): array
    {
        return [
            'Troca de oleo',
            'Troca de filtros',
            'Revisao de rotina',
            'Inspecao geral',
            'Afericao de sistema',
        ];
    }

    private function origins(): array
    {
        return [
            'Interna',
            'Terceiro',
            'Campo',
        ];
    }
}
