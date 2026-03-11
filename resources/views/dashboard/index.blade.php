@extends('layouts.app')

@section('title', 'Dashboard | AgroMaq')

@section('content')
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="metric-card fade-up delay-1">
            <p class="metric-label">Maquinas cadastradas</p>
            <p class="metric-value">{{ $machinesTotal }}</p>
            <p class="metric-helper">{{ $activeMachines }} ativas</p>
        </article>
        <article class="metric-card fade-up delay-2">
            <p class="metric-label">Em manutencao</p>
            <p class="metric-value">{{ $machinesInMaintenance }}</p>
            <p class="metric-helper">monitorar disponibilidade</p>
        </article>
        <article class="metric-card fade-up delay-3">
            <p class="metric-label">Horas no mes</p>
            <p class="metric-value">{{ number_format($workedHoursMonth, 2, ',', '.') }}h</p>
            <p class="metric-helper">produtividade da frota</p>
        </article>
        <article class="metric-card fade-up delay-4">
            <p class="metric-label">Operadores ativos</p>
            <p class="metric-value">{{ $operatorsTotal }}</p>
            <p class="metric-helper">equipes disponiveis</p>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <article class="panel fade-up">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="panel-title">Alertas de manutencao</h2>
                <a href="{{ route('maintenances.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">ver tudo</a>
            </div>

            <div class="space-y-3">
                @forelse ($maintenancesWithAlert as $maintenance)
                    <div class="alert-item">
                        <div>
                            <p class="font-semibold text-amber-900">{{ $maintenance->service_name }}</p>
                            <p class="text-sm text-amber-800">
                                {{ $maintenance->machine->name }}
                                @if ($maintenance->scheduled_for)
                                    - Prevista para {{ $maintenance->scheduled_for->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="badge badge-warning">{{ strtoupper($maintenance->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-600">Sem alertas pendentes por data ou horimetro.</p>
                @endforelse
            </div>
        </article>

        <article class="panel fade-up">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="panel-title">Preventiva por horimetro</h2>
                <a href="{{ route('machines.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">ver maquinas</a>
            </div>

            <div class="space-y-3">
                @forelse ($preventiveAlerts as $machine)
                    <div class="alert-item">
                        <div>
                            <p class="font-semibold text-amber-900">{{ $machine->name }}</p>
                            <p class="text-sm text-amber-800">
                                Horimetro atual {{ number_format($machine->hour_meter, 2, ',', '.') }}h
                            </p>
                        </div>
                        <span class="badge badge-danger">Preventiva vencida</span>
                    </div>
                @empty
                    <p class="text-sm text-zinc-600">Nenhuma maquina acima do intervalo preventivo.</p>
                @endforelse
            </div>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <article class="panel fade-up">
            <h2 class="panel-title mb-4">Custos do mes</h2>
            <div class="space-y-3">
                <div class="cost-line">
                    <span>Combustivel</span>
                    <strong>R$ {{ number_format($fuelCostMonth, 2, ',', '.') }}</strong>
                </div>
                <div class="cost-line">
                    <span>Manutencao</span>
                    <strong>R$ {{ number_format($maintenanceCostMonth, 2, ',', '.') }}</strong>
                </div>
                <div class="cost-line border-t border-zinc-200 pt-2 text-lg">
                    <span>Total operacional</span>
                    <strong>R$ {{ number_format($fuelCostMonth + $maintenanceCostMonth, 2, ',', '.') }}</strong>
                </div>
            </div>
        </article>

        <article class="panel fade-up">
            <h2 class="panel-title mb-4">Top custo operacional por maquina</h2>
            <div class="space-y-3">
                @forelse ($topOperationalCosts as $machine)
                    <div class="rounded-xl border border-zinc-200 bg-white p-3">
                        <p class="font-semibold text-zinc-800">{{ $machine->name }}</p>
                        <div class="mt-1 flex justify-between text-sm text-zinc-600">
                            <span>Custo total</span>
                            <span>R$ {{ number_format($machine->operational_cost, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-zinc-600">
                            <span>Custo por hora</span>
                            <span>R$ {{ number_format($machine->cost_per_hour, 2, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-zinc-600">Ainda sem dados suficientes para ranking.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
