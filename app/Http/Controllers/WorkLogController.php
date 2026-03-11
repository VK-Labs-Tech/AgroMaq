<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Operator;
use App\Models\WorkLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkLogController extends Controller
{
    public function index()
    {
        $workLogs = WorkLog::query()
            ->with(['machine', 'operator'])
            ->latest('started_at')
            ->paginate(15);

        return view('work-logs.index', [
            'workLogs' => $workLogs,
        ]);
    }

    public function create()
    {
        return view('work-logs.create', [
            'workLog' => new WorkLog(),
            'machines' => Machine::query()->orderBy('name')->get(),
            'operators' => Operator::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['hours_worked'] = $this->calculateHours($validated);

        $workLog = WorkLog::query()->create($validated);
        $this->syncMachineHourMeter($workLog->machine, (float) $validated['end_hour_meter']);

        return redirect()
            ->route('work-logs.index')
            ->with('success', 'Sessao de trabalho registrada com sucesso.');
    }

    public function show(WorkLog $workLog)
    {
        $workLog->load(['machine', 'operator']);

        return view('work-logs.show', [
            'workLog' => $workLog,
        ]);
    }

    public function edit(WorkLog $workLog)
    {
        return view('work-logs.edit', [
            'workLog' => $workLog,
            'machines' => Machine::query()->orderBy('name')->get(),
            'operators' => Operator::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, WorkLog $workLog)
    {
        $validated = $this->validated($request);
        $validated['hours_worked'] = $this->calculateHours($validated);

        $workLog->update($validated);
        $this->syncMachineHourMeter($workLog->machine, (float) $validated['end_hour_meter']);

        return redirect()
            ->route('work-logs.show', $workLog)
            ->with('success', 'Sessao de trabalho atualizada com sucesso.');
    }

    public function destroy(WorkLog $workLog)
    {
        $machine = $workLog->machine;

        $workLog->delete();
        $this->recalculateMachineHourMeter($machine);

        return redirect()
            ->route('work-logs.index')
            ->with('success', 'Sessao de trabalho removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'machine_id' => ['required', 'exists:machines,id'],
            'operator_id' => ['required', 'exists:operators,id'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'start_hour_meter' => ['required', 'numeric', 'min:0'],
            'end_hour_meter' => ['required', 'numeric', 'gte:start_hour_meter'],
            'activity' => ['nullable', 'string'],
        ]);
    }

    private function calculateHours(array $data): float
    {
        $byHourMeter = (float) $data['end_hour_meter'] - (float) $data['start_hour_meter'];

        if ($byHourMeter > 0) {
            return round($byHourMeter, 2);
        }

        $start = Carbon::parse($data['started_at']);
        $end = Carbon::parse($data['ended_at']);

        return round(max($start->diffInMinutes($end) / 60, 0.01), 2);
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
