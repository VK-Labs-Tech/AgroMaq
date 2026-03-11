@extends('layouts.app')

@section('title', 'Lancamento Preventivo | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="panel-title">Lancamento de manutencao preventiva</h1>
                <p class="text-sm text-zinc-500">Tela operacional inspirada no fluxo de manutencao legado.</p>
            </div>
            <a href="{{ route('maintenances.index') }}" class="btn-secondary">Voltar para manutencoes</a>
        </div>

        <form method="POST" action="{{ route('maintenances.preventive-launch.store') }}" class="space-y-4">
            @csrf

            <div class="rounded-xl border border-sky-300 bg-sky-50 p-3">
                <div class="mb-2 text-xs font-bold uppercase tracking-wide text-sky-800">Equipamento</div>
                <div class="grid gap-3 md:grid-cols-4">
                    <label class="field md:col-span-2">
                        <span>Equipamento</span>
                        <select name="machine_id" required>
                            <option value="">Selecione</option>
                            @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" @selected((string) old('machine_id', $selectedMachine?->id) === (string) $machine->id)>
                                    {{ $machine->asset_tag }} - {{ $machine->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Unidade de negocio</span>
                        <select name="business_unit">
                            <option value="">Selecione</option>
                            @foreach ($businessUnits as $businessUnit)
                                <option value="{{ $businessUnit }}" @selected(old('business_unit') === $businessUnit)>{{ $businessUnit }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Oficina</span>
                        <select name="workshop">
                            <option value="">Selecione</option>
                            @foreach ($workshops as $workshop)
                                <option value="{{ $workshop }}" @selected(old('workshop') === $workshop)>{{ $workshop }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="rounded-xl border border-sky-300 bg-white p-3">
                <div class="mb-2 text-xs font-bold uppercase tracking-wide text-sky-800">Movimentacao preventiva</div>
                <div class="grid gap-3 md:grid-cols-4">
                    <label class="field">
                        <span>Data</span>
                        <input type="date" name="scheduled_exchange_date" value="{{ old('scheduled_exchange_date') }}">
                    </label>

                    <label class="field">
                        <span>Mecanismo</span>
                        <input type="text" name="mechanism" value="{{ old('mechanism') }}" placeholder="Ex: Troca de filtros" required>
                    </label>

                    <label class="field">
                        <span>Movimento</span>
                        <select name="movement" required>
                            <option value="">Selecione</option>
                            @foreach ($movements as $movement)
                                <option value="{{ $movement }}" @selected(old('movement') === $movement)>{{ $movement }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Motivo</span>
                        <input type="text" name="reason" value="{{ old('reason') }}" placeholder="Rotina / desgaste / ajuste">
                    </label>

                    <label class="field">
                        <span>KM</span>
                        <input type="number" min="0" name="odometer_km" value="{{ old('odometer_km') }}">
                    </label>

                    <label class="field">
                        <span>Horimetro</span>
                        <input type="number" step="0.01" min="0" name="hour_meter" value="{{ old('hour_meter', $selectedMachine?->hour_meter) }}">
                    </label>

                    <label class="field">
                        <span>Troca prevista</span>
                        <input type="date" name="planned_exchange_date" value="{{ old('planned_exchange_date') }}">
                    </label>

                    <label class="field">
                        <span>Origem</span>
                        <select name="origin">
                            <option value="">Selecione</option>
                            @foreach ($origins as $origin)
                                <option value="{{ $origin }}" @selected(old('origin') === $origin)>{{ $origin }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field md:col-span-4">
                        <span>Observacoes</span>
                        <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
                    </label>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="submit" class="btn-primary">Lancar preventiva</button>
                    <a href="{{ route('maintenances.preventive-launch', ['machine_id' => old('machine_id', $selectedMachine?->id)]) }}" class="btn-secondary">Atualizar historico</a>
                </div>
            </div>
        </form>

        <div class="mt-5 rounded-xl border border-sky-300 bg-white p-3">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wide text-sky-800">Historico do equipamento</h2>
                <span class="text-xs text-zinc-500">{{ $selectedMachine?->name ?? 'Nenhum equipamento selecionado' }}</span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-zinc-200">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Mecanismo</th>
                            <th>Movimento</th>
                            <th>Motivo</th>
                            <th>KM</th>
                            <th>Horimetro</th>
                            <th>Troca prevista</th>
                            <th>Troca agendada</th>
                            <th>Origem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $item)
                            <tr>
                                <td>{{ $item->created_at?->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $item->service_name }}</td>
                                <td>{{ $item->movement ?: '-' }}</td>
                                <td>{{ $item->reason ?: '-' }}</td>
                                <td>{{ $item->odometer_km ?: '-' }}</td>
                                <td>{{ $item->hour_meter ? number_format($item->hour_meter, 2, ',', '.') : '-' }}</td>
                                <td>{{ $item->next_due_date?->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $item->scheduled_for?->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $item->origin ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-7 text-center text-zinc-500">Sem dados para visualizar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
