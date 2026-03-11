@extends('layouts.app')

@section('title', 'Detalhes do Abastecimento | AgroMaq')

@section('content')
    <section class="panel fade-up max-w-3xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="panel-title">Detalhes do abastecimento</h1>
            <a href="{{ route('fuel-records.edit', $fuelRecord) }}" class="btn-secondary">Editar</a>
        </div>

        <dl class="grid gap-3 md:grid-cols-2">
            <div class="metric-mini">
                <dt class="metric-label">Maquina</dt>
                <dd class="metric-value-sm">{{ $fuelRecord->machine->name }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Operador</dt>
                <dd class="metric-value-sm">{{ $fuelRecord->operator->name ?? '-' }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Data</dt>
                <dd class="metric-value-sm">{{ $fuelRecord->fueled_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Horimetro</dt>
                <dd class="metric-value-sm">{{ number_format($fuelRecord->hour_meter, 2, ',', '.') }} h</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Litros</dt>
                <dd class="metric-value-sm">{{ number_format($fuelRecord->liters, 2, ',', '.') }} L</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Custo total</dt>
                <dd class="metric-value-sm">R$ {{ number_format($fuelRecord->total_cost, 2, ',', '.') }}</dd>
            </div>
        </dl>

        <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-700">
            <p><strong>Fornecedor:</strong> {{ $fuelRecord->supplier ?: '-' }}</p>
            <p><strong>Observacoes:</strong> {{ $fuelRecord->notes ?: '-' }}</p>
        </div>
    </section>
@endsection
