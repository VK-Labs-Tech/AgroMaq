@extends('layouts.app')

@section('title', 'Detalhes da Maquina | AgroMaq')

@section('content')
    <section class="grid gap-4 lg:grid-cols-3">
        <article class="panel lg:col-span-2 fade-up">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="panel-title">{{ $machine->name }}</h1>
                    <p class="text-sm text-zinc-500">{{ $machine->asset_tag }} - {{ $machine->brand }} {{ $machine->model }}</p>
                </div>
                <a href="{{ route('machines.edit', $machine) }}" class="btn-secondary">Editar</a>
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                <div class="metric-mini">
                    <p class="metric-label">Horimetro atual</p>
                    <p class="metric-value-sm">{{ number_format($machine->hour_meter, 2, ',', '.') }}h</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Status</p>
                    <p class="metric-value-sm">{{ strtoupper($machine->status) }}</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Custo total</p>
                    <p class="metric-value-sm">R$ {{ number_format($machine->operationalCost(), 2, ',', '.') }}</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Custo por hora</p>
                    <p class="metric-value-sm">R$ {{ number_format($machine->operationalCostPerHour(), 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div>
                    <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-zinc-500">Historico de servicos</h2>
                    <div class="space-y-2">
                        @forelse ($machine->maintenances as $maintenance)
                            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-sm">
                                <p class="font-semibold">{{ $maintenance->service_name }}</p>
                                <p class="text-zinc-600">{{ strtoupper($maintenance->type) }} - {{ strtoupper($maintenance->status) }}</p>
                                <p class="text-zinc-500">Custo: R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">Sem servicos registrados.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-zinc-500">Ultimas operacoes</h2>
                    <div class="space-y-2">
                        @forelse ($machine->workLogs as $workLog)
                            <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-sm">
                                <p class="font-semibold">{{ $workLog->operator->name ?? 'Operador nao informado' }}</p>
                                <p class="text-zinc-600">{{ $workLog->started_at->format('d/m/Y H:i') }} - {{ $workLog->ended_at->format('d/m/Y H:i') }}</p>
                                <p class="text-zinc-500">{{ number_format($workLog->hours_worked, 2, ',', '.') }} horas</p>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">Sem registros de uso.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </article>

        <aside class="panel fade-up">
            <h2 class="panel-title mb-4">Resumo tecnico</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Tipo</dt>
                    <dd>{{ $machine->type }}</dd>
                </div>
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Ano</dt>
                    <dd>{{ $machine->manufacture_year ?: '-' }}</dd>
                </div>
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Serie</dt>
                    <dd>{{ $machine->serial_number ?: '-' }}</dd>
                </div>
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Placa</dt>
                    <dd>{{ $machine->plate ?: '-' }}</dd>
                </div>
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Proxima preventiva</dt>
                    <dd>{{ number_format($machine->nextPreventiveHourMeter(), 2, ',', '.') }}h</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-zinc-500">Horas restantes</dt>
                    <dd class="{{ $machine->hoursUntilPreventive() <= 0 ? 'text-red-700' : 'text-emerald-700' }}">{{ number_format($machine->hoursUntilPreventive(), 2, ',', '.') }}h</dd>
                </div>
            </dl>
        </aside>
    </section>
@endsection
