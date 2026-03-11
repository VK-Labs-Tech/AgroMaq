@extends('layouts.app')

@section('title', 'Detalhes da Sessao | AgroMaq')

@section('content')
    <section class="panel fade-up max-w-3xl">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h1 class="panel-title">Detalhes da sessao</h1>
            <a href="{{ route('work-logs.edit', $workLog) }}" class="btn-secondary">Editar</a>
        </div>

        <dl class="grid gap-3 md:grid-cols-2">
            <div class="metric-mini">
                <dt class="metric-label">Maquina</dt>
                <dd class="metric-value-sm">{{ $workLog->machine->name }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Operador</dt>
                <dd class="metric-value-sm">{{ $workLog->operator->name }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Inicio</dt>
                <dd class="metric-value-sm">{{ $workLog->started_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Fim</dt>
                <dd class="metric-value-sm">{{ $workLog->ended_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Horas</dt>
                <dd class="metric-value-sm">{{ number_format($workLog->hours_worked, 2, ',', '.') }} h</dd>
            </div>
            <div class="metric-mini">
                <dt class="metric-label">Horimetro</dt>
                <dd class="metric-value-sm">{{ number_format($workLog->start_hour_meter, 2, ',', '.') }} -> {{ number_format($workLog->end_hour_meter, 2, ',', '.') }}</dd>
            </div>
        </dl>

        @if ($workLog->activity)
            <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-700">
                <p class="font-semibold">Atividade</p>
                <p>{{ $workLog->activity }}</p>
            </div>
        @endif
    </section>
@endsection
