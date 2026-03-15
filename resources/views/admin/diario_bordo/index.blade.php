@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Diario de Bordo Agroindustrial</h2>
                <p class="text-muted mb-0">Fluxo completo com pre-viagem, checklist, transito e encerramento com assinatura digital.</p>
            </div>
            <a href="{{ route('admin.diario-bordo.create') }}" class="btn btn-primary btn-lg">Novo Diario</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block">Em andamento</small>
                        <h3 class="mb-0">{{ $metrics['abertos'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block">Encerrados</small>
                        <h3 class="mb-0">{{ $metrics['encerrados'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted d-block">Total recentes</small>
                        <h3 class="mb-0">{{ $metrics['total'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>Ultimos diarios</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                                <td colspan="7" class="text-center text-muted py-5">Nenhum diario cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

