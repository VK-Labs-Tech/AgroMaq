@extends('layouts.app')

@section('title', 'Detalhes da Manutencao | AgroMaq')

@section('content')
    <section class="panel fade-up max-w-3xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="panel-title">Detalhes da manutencao</h1>
            <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn-secondary">Editar</a>
        </div>

        <dl class="grid gap-3 md:grid-cols-2">
            <div class="metric-mini">
                <dt class="metric-label">Maquina</dt>
                <dd class="metric-value-sm">{{ $maintenance->machine->name }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Servico</dt>
                <dd class="metric-value-sm">{{ $maintenance->service_name }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Tipo</dt>
                <dd class="metric-value-sm">{{ strtoupper($maintenance->type) }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Status</dt>
                <dd class="metric-value-sm">{{ strtoupper($maintenance->status) }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Data prevista</dt>
                <dd class="metric-value-sm">{{ $maintenance->scheduled_for?->format('d/m/Y') ?: '-' }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Data executada</dt>
                <dd class="metric-value-sm">{{ $maintenance->performed_at?->format('d/m/Y') ?: '-' }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Horimetro</dt>
                <dd class="metric-value-sm">{{ $maintenance->hour_meter ? number_format($maintenance->hour_meter, 2, ',', '.') . ' h' : '-' }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Custo</dt>
                <dd class="metric-value-sm">R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</dd>
            </div>
        </dl>

        <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-700">
            <p><strong>Fornecedor:</strong> {{ $maintenance->vendor ?: '-' }}</p>
            <p><strong>Proxima data limite:</strong> {{ $maintenance->next_due_date?->format('d/m/Y') ?: '-' }}</p>
            <p><strong>Proximo horimetro limite:</strong> {{ $maintenance->next_due_hour_meter ? number_format($maintenance->next_due_hour_meter, 2, ',', '.') . ' h' : '-' }}</p>
            <p><strong>Descricao:</strong> {{ $maintenance->description ?: '-' }}</p>
        </div>
    </section>
@endsection
