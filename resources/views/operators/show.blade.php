@extends('layouts.app')

@section('title', 'Detalhes do Operador | AgroMaq')

@section('content')
    <section class="grid gap-4 lg:grid-cols-3">
        <article class="panel lg:col-span-2 fade-up">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h1 class="panel-title">{{ $operator->name }}</h1>
                    <p class="text-sm text-zinc-500">CPF {{ $operator->cpf }}</p>
                </div>
                <a href="{{ route('operators.edit', $operator) }}" class="btn-secondary">Editar</a>
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                <div class="metric-mini">
                    <p class="metric-label">Status</p>
                    <p class="metric-value-sm">{{ $operator->active ? 'ATIVO' : 'INATIVO' }}</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Horas trabalhadas</p>
                    <p class="metric-value-sm">{{ number_format($operator->totalWorkedHours(), 2, ',', '.') }} h</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Validade CNH</p>
                    <p class="metric-value-sm">{{ $operator->license_expires_at?->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div class="metric-mini">
                    <p class="metric-label">Categoria CNH</p>
                    <p class="metric-value-sm">{{ $operator->license_category ?: '-' }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-zinc-500">Historico de uso</h2>
                <div class="space-y-2">
                    @forelse ($operator->workLogs as $workLog)
                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-sm">
                            <p class="font-semibold">{{ $workLog->machine->name ?? 'Maquina removida' }}</p>
                            <p class="text-zinc-600">{{ $workLog->started_at->format('d/m/Y H:i') }} - {{ $workLog->ended_at->format('d/m/Y H:i') }}</p>
                            <p class="text-zinc-500">{{ number_format($workLog->hours_worked, 2, ',', '.') }} horas</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">Sem historico de operacao.</p>
                    @endforelse
                </div>
            </div>
        </article>

        <aside class="panel fade-up">
            <h2 class="panel-title mb-4">Contato e licenca</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">Telefone</dt>
                    <dd>{{ $operator->phone ?: '-' }}</dd>
                </div>
                <div class="flex justify-between gap-3 border-b border-zinc-200 pb-2">
                    <dt class="text-zinc-500">CNH numero</dt>
                    <dd>{{ $operator->license_number ?: '-' }}</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-zinc-500">Observacoes</dt>
                    <dd class="max-w-[60%] text-right">{{ $operator->notes ?: '-' }}</dd>
                </div>
            </dl>
        </aside>
    </section>
@endsection
