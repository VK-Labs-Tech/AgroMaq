@extends('layouts.admin-2026')

@section('title', 'Diario de Bordo')

@section('content')
    <x-ui.page-header
        title="Diario de Bordo Agroindustrial"
        subtitle="Estrutura Blade 2026: layout modular, componentes reutilizaveis e fluxo operacional em 4 etapas."
    >
        <x-slot:actions>
            <a href="{{ route('admin.diario-bordo.create') }}" class="btn btn-primary btn-lg">Novo Diario</a>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <x-ui.stat-card label="Em andamento" :value="$metrics['abertos']" />
        </div>
        <div class="col-md-4">
            <x-ui.stat-card label="Encerrados" :value="$metrics['encerrados']" />
        </div>
        <div class="col-md-4">
            <x-ui.stat-card label="Total recentes" :value="$metrics['total']" />
        </div>
    </div>

    <x-ui.panel title="Ultimos diarios">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Motorista</th>
                        <th>Rota</th>
                        <th>Status</th>
                        <th>GPS</th>
                        <th>Criado em</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($diarios as $diario)
                        <tr>
                            <td>#{{ $diario->id }}</td>
                            <td>{{ $diario->motorista_nome }}</td>
                            <td>{{ $diario->origem }} -> {{ $diario->destino }}</td>
                            <td>
                                <span class="badge bg-{{ $diario->status === 'encerrado' ? 'success' : 'warning' }}">
                                    {{ strtoupper(str_replace('_', ' ', $diario->status)) }}
                                </span>
                            </td>
                            <td>{{ $diario->transitos_count ?? 0 }} pontos</td>
                            <td>{{ $diario->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.diario-bordo.show', $diario->id) }}" class="btn btn-sm btn-outline-primary">Abrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center app-muted py-5">Nenhum diario cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.panel>
@endsection
