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
                <option value="{{ $machine->id }}" @selected((string) old('machine_id', $maintenance->machine_id) === (string) $machine->id)>{{ $machine->name }} ({{ $machine->asset_tag }})</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Tipo</span>
        <select name="type" required>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $maintenance->type ?? 'preventive') === $type)>{{ strtoupper($type) }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Servico</span>
        <input type="text" name="service_name" value="{{ old('service_name', $maintenance->service_name) }}" required>
    </label>

    <label class="field">
        <span>Status</span>
        <select name="status" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $maintenance->status ?? 'scheduled') === $status)>{{ strtoupper($status) }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Data prevista</span>
        <input type="date" name="scheduled_for" value="{{ old('scheduled_for', optional($maintenance->scheduled_for)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Data executada</span>
        <input type="date" name="performed_at" value="{{ old('performed_at', optional($maintenance->performed_at)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Horimetro no servico</span>
        <input type="number" step="0.01" min="0" name="hour_meter" value="{{ old('hour_meter', $maintenance->hour_meter) }}">
    </label>

    <label class="field">
        <span>Custo</span>
        <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost', $maintenance->cost ?? 0) }}" required>
    </label>

    <label class="field">
        <span>Proxima data limite</span>
        <input type="date" name="next_due_date" value="{{ old('next_due_date', optional($maintenance->next_due_date)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Proximo horimetro limite</span>
        <input type="number" step="0.01" min="0" name="next_due_hour_meter" value="{{ old('next_due_hour_meter', $maintenance->next_due_hour_meter) }}">
    </label>

    <label class="field md:col-span-2">
        <span>Fornecedor</span>
        <input type="text" name="vendor" value="{{ old('vendor', $maintenance->vendor) }}">
    </label>

    <label class="field md:col-span-2">
        <span>Descricao</span>
        <textarea name="description" rows="3">{{ old('description', $maintenance->description) }}</textarea>
    </label>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Salvar</button>
    <a href="{{ route('maintenances.index') }}" class="btn-secondary">Cancelar</a>
</div>
