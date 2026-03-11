@csrf
@if (($method ?? 'POST') !== 'POST')
    @method($method)
@endif

<div class="grid gap-4 md:grid-cols-2">
    <label class="field">
        <span>Nome</span>
        <input type="text" name="name" value="{{ old('name', $machine->name) }}" required>
    </label>

    <label class="field">
        <span>Codigo patrimonial</span>
        <input type="text" name="asset_tag" value="{{ old('asset_tag', $machine->asset_tag) }}" required>
    </label>

    <label class="field">
        <span>Tipo</span>
        <select name="type" required>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $machine->type) === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $machine->status ?? 'active') === $status)>{{ strtoupper($status) }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Marca</span>
        <input type="text" name="brand" value="{{ old('brand', $machine->brand) }}" required>
    </label>

    <label class="field">
        <span>Modelo</span>
        <input type="text" name="model" value="{{ old('model', $machine->model) }}" required>
    </label>

    <label class="field">
        <span>Ano</span>
        <input type="number" name="manufacture_year" min="1980" max="{{ now()->year }}" value="{{ old('manufacture_year', $machine->manufacture_year) }}">
    </label>

    <label class="field">
        <span>Placa</span>
        <input type="text" name="plate" value="{{ old('plate', $machine->plate) }}">
    </label>

    <label class="field">
        <span>Numero de serie</span>
        <input type="text" name="serial_number" value="{{ old('serial_number', $machine->serial_number) }}">
    </label>

    <label class="field">
        <span>Horimetro atual</span>
        <input type="number" step="0.01" min="0" name="hour_meter" value="{{ old('hour_meter', $machine->hour_meter ?? 0) }}" required>
    </label>

    <label class="field">
        <span>Intervalo preventivo (h)</span>
        <input type="number" step="0.01" min="1" name="preventive_interval_hours" value="{{ old('preventive_interval_hours', $machine->preventive_interval_hours ?? 250) }}" required>
    </label>

    <label class="field">
        <span>Horimetro da ultima preventiva</span>
        <input type="number" step="0.01" min="0" name="last_preventive_hour_meter" value="{{ old('last_preventive_hour_meter', $machine->last_preventive_hour_meter ?? 0) }}" required>
    </label>

    <label class="field md:col-span-2">
        <span>Data da ultima preventiva</span>
        <input type="date" name="last_preventive_date" value="{{ old('last_preventive_date', optional($machine->last_preventive_date)->format('Y-m-d')) }}">
    </label>

    <label class="field md:col-span-2">
        <span>Observacoes</span>
        <textarea name="notes" rows="3">{{ old('notes', $machine->notes) }}</textarea>
    </label>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Salvar</button>
    <a href="{{ route('machines.index') }}" class="btn-secondary">Cancelar</a>
</div>
