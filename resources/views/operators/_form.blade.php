@csrf
@if (($method ?? 'POST') !== 'POST')
    @method($method)
@endif

<div class="grid gap-4 md:grid-cols-2">
    <label class="field">
        <span>Nome completo</span>
        <input type="text" name="name" value="{{ old('name', $operator->name) }}" required>
    </label>

    <label class="field">
        <span>CPF</span>
        <input type="text" name="cpf" value="{{ old('cpf', $operator->cpf) }}" required>
    </label>

    <label class="field">
        <span>Telefone</span>
        <input type="text" name="phone" value="{{ old('phone', $operator->phone) }}">
    </label>

    <label class="field">
        <span>CNH numero</span>
        <input type="text" name="license_number" value="{{ old('license_number', $operator->license_number) }}">
    </label>

    <label class="field">
        <span>Categoria CNH</span>
        <input type="text" name="license_category" value="{{ old('license_category', $operator->license_category) }}">
    </label>

    <label class="field">
        <span>Validade da CNH</span>
        <input type="date" name="license_expires_at" value="{{ old('license_expires_at', optional($operator->license_expires_at)->format('Y-m-d')) }}">
    </label>

    <label class="field">
        <span>Status</span>
        <select name="active" required>
            <option value="1" @selected(old('active', $operator->active ?? true) == true)>Ativo</option>
            <option value="0" @selected(old('active', $operator->active ?? true) == false)>Inativo</option>
        </select>
    </label>

    <label class="field md:col-span-2">
        <span>Observacoes</span>
        <textarea name="notes" rows="3">{{ old('notes', $operator->notes) }}</textarea>
    </label>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Salvar</button>
    <a href="{{ route('operators.index') }}" class="btn-secondary">Cancelar</a>
</div>
