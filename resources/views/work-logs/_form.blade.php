@csrf
@if (($method ?? 'POST') !== 'POST')
    @method($method)
@endif

<div class="grid gap-4 md:grid-cols-2">
    <label class="field">
        <span>Maquina</span>
        <select name="machine_id" required>
            <option value="">Selecione</option>
            @foreach ($machines as $machine)
                <option value="{{ $machine->id }}" @selected((string) old('machine_id', $workLog->machine_id) === (string) $machine->id)>{{ $machine->name }} ({{ $machine->asset_tag }})</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Operador</span>
        <select name="operator_id" required>
            <option value="">Selecione</option>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected((string) old('operator_id', $workLog->operator_id) === (string) $operator->id)>{{ $operator->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Inicio</span>
        <input type="datetime-local" name="started_at" value="{{ old('started_at', optional($workLog->started_at)->format('Y-m-d\TH:i')) }}" required>
    </label>

    <label class="field">
        <span>Fim</span>
        <input type="datetime-local" name="ended_at" value="{{ old('ended_at', optional($workLog->ended_at)->format('Y-m-d\TH:i')) }}" required>
    </label>

    <label class="field">
        <span>Horimetro inicial</span>
        <input type="number" step="0.01" min="0" name="start_hour_meter" value="{{ old('start_hour_meter', $workLog->start_hour_meter) }}" required>
    </label>

    <label class="field">
        <span>Horimetro final</span>
        <input type="number" step="0.01" min="0" name="end_hour_meter" value="{{ old('end_hour_meter', $workLog->end_hour_meter) }}" required>
    </label>

    <label class="field md:col-span-2">
        <span>Atividade</span>
        <textarea name="activity" rows="3">{{ old('activity', $workLog->activity) }}</textarea>
    </label>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Salvar</button>
    <a href="{{ route('work-logs.index') }}" class="btn-secondary">Cancelar</a>
</div>
