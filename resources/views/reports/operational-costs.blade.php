@extends('layouts.app')

@section('title', 'Relatorio de Custo Operacional | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Relatorio de custo operacional por maquina</h1>
        </div>

        <form method="GET" class="mb-4 grid gap-3 md:grid-cols-4">
            <label class="field">
                <span>Data inicial</span>
                <input type="date" name="from" value="{{ $from?->format('Y-m-d') }}">
            </label>
            <label class="field">
                <span>Data final</span>
                <input type="date" name="to" value="{{ $to?->format('Y-m-d') }}">
            </label>
            <div class="flex items-end gap-2 md:col-span-2">
                <button class="btn-primary" type="submit">Aplicar filtro</button>
                <a href="{{ route('reports.operational-costs') }}" class="btn-secondary">Limpar</a>
            </div>
        </form>

        <div class="mb-5 grid gap-3 md:grid-cols-4">
            <div class="metric-mini">
                <p class="metric-label">Horas totais</p>
                <p class="metric-value-sm">{{ number_format($totals['hours'], 2, ',', '.') }} h</p>
            </div>
            <div class="metric-mini">
                <p class="metric-label">Combustivel</p>
                <p class="metric-value-sm">R$ {{ number_format($totals['fuel_cost'], 2, ',', '.') }}</p>
            </div>
            <div class="metric-mini">
                <p class="metric-label">Manutencao</p>
                <p class="metric-value-sm">R$ {{ number_format($totals['maintenance_cost'], 2, ',', '.') }}</p>
            </div>
            <div class="metric-mini">
                <p class="metric-label">Total operacional</p>
                <p class="metric-value-sm">R$ {{ number_format($totals['operational_cost'], 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Maquina</th>
                        <th>Horas</th>
                        <th>Combustivel</th>
                        <th>Manutencao</th>
                        <th>Custo total</th>
                        <th>Custo/hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($machines as $machine)
                        <tr>
                            <td>{{ $machine->name }}</td>
                            <td>{{ number_format($machine->total_hours, 2, ',', '.') }} h</td>
                            <td>R$ {{ number_format($machine->fuel_cost, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($machine->maintenance_cost, 2, ',', '.') }}</td>
                            <td class="font-semibold">R$ {{ number_format($machine->operational_cost, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($machine->cost_per_hour, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-zinc-500">Sem dados para o periodo informado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
