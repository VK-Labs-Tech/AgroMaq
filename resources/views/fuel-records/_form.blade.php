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
                <option value="{{ $machine->id }}" @selected((string) old('machine_id', $fuelRecord->machine_id) === (string) $machine->id)>{{ $machine->name }} ({{ $machine->asset_tag }})</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Operador (opcional)</span>
        <select name="operator_id">
            <option value="">Sem operador</option>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected((string) old('operator_id', $fuelRecord->operator_id) === (string) $operator->id)>{{ $operator->name }}</option>
            @endforeach
        </select>
    </label>

    <label class="field">
        <span>Data do abastecimento</span>
        <input type="datetime-local" name="fueled_at" value="{{ old('fueled_at', optional($fuelRecord->fueled_at)->format('Y-m-d\TH:i')) }}" required>
    </label>

    <label class="field">
        <span>Horimetro no abastecimento</span>
        <input type="number" step="0.01" min="0" name="hour_meter" value="{{ old('hour_meter', $fuelRecord->hour_meter) }}" required>
    </label>

    <label class="field">
        <span>Litros</span>
        <input type="number" step="0.01" min="0.01" name="liters" value="{{ old('liters', $fuelRecord->liters) }}" required>
    </label>

    <label class="field">
        <span>Preco por litro</span>
        <input type="number" step="0.01" min="0.01" name="price_per_liter" value="{{ old('price_per_liter', $fuelRecord->price_per_liter) }}" required>
    </label>

    <label class="field md:col-span-2">
        <span>Fornecedor</span>
        <input type="text" name="supplier" value="{{ old('supplier', $fuelRecord->supplier) }}">
    </label>

    <label class="field md:col-span-2">
        <span>Observacoes</span>
        <textarea name="notes" rows="3">{{ old('notes', $fuelRecord->notes) }}</textarea>
    </label>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Salvar</button>
    <a href="{{ route('fuel-records.index') }}" class="btn-secondary">Cancelar</a>
</div>
