@extends('layouts.app')

@section('title', 'Manutencoes | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Manutencoes</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('maintenances.preventive-launch') }}" class="btn-secondary">Lancamento preventivo</a>
                <a href="{{ route('maintenances.create') }}" class="btn-primary">Nova manutencao</a>
            </div>
        </div>

        <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
            <label class="field max-w-xs">
                <span>Status</span>
                <select name="status">
                    <option value="">Todos</option>
                    @foreach ($statuses as $statusValue)
                        <option value="{{ $statusValue }}" @selected($status === $statusValue)>{{ strtoupper($statusValue) }}</option>
                    @endforeach
                </select>
            </label>
            <button class="btn-secondary" type="submit">Filtrar</button>
            <a href="{{ route('maintenances.index') }}" class="btn-secondary">Limpar</a>
        </form>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Servico</th>
                        <th>Maquina</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data prevista</th>
                        <th>Custo</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($maintenances as $maintenance)
                        <tr>
                            <td>{{ $maintenance->service_name }}</td>
                            <td>{{ $maintenance->machine->name }}</td>
                            <td>{{ strtoupper($maintenance->type) }}</td>
                            <td>
                                <span class="badge {{ $maintenance->status === 'completed' ? 'badge-success' : ($maintenance->status === 'overdue' ? 'badge-danger' : 'badge-warning') }}">
                                    {{ strtoupper($maintenance->status) }}
                                </span>
                            </td>
                            <td>{{ $maintenance->scheduled_for?->format('d/m/Y') ?: '-' }}</td>
                            <td>R$ {{ number_format($maintenance->cost, 2, ',', '.') }}</td>
                            <td>
                                <div class="flex gap-2 text-sm">
                                    <a href="{{ route('maintenances.show', $maintenance) }}" class="action-link">Ver</a>
                                    <a href="{{ route('maintenances.edit', $maintenance) }}" class="action-link">Editar</a>
                                    <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" onsubmit="return confirm('Remover esta manutencao?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link text-red-700" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-zinc-500">Nenhuma manutencao registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $maintenances->links() }}</div>
    </section>
@endsection
