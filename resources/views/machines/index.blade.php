@extends('layouts.app')

@section('title', 'Maquinas | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Maquinas</h1>
            <a href="{{ route('machines.create') }}" class="btn-primary">Nova maquina</a>
        </div>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Horimetro</th>
                        <th>Custo operacional</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($machines as $machine)
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $machine->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $machine->asset_tag }} - {{ $machine->brand }} {{ $machine->model }}</p>
                            </td>
                            <td>{{ $machine->type }}</td>
                            <td><span class="badge {{ $machine->status === 'active' ? 'badge-success' : ($machine->status === 'maintenance' ? 'badge-warning' : 'badge-neutral') }}">{{ strtoupper($machine->status) }}</span></td>
                            <td>{{ number_format($machine->hour_meter, 2, ',', '.') }} h</td>
                            <td>R$ {{ number_format($machine->operationalCost(), 2, ',', '.') }}</td>
                            <td>
                                <div class="flex gap-2 text-sm">
                                    <a href="{{ route('machines.show', $machine) }}" class="action-link">Ver</a>
                                    <a href="{{ route('machines.edit', $machine) }}" class="action-link">Editar</a>
                                    <form action="{{ route('machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('Remover esta maquina?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link text-red-700" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-zinc-500">Nenhuma maquina cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $machines->links() }}</div>
    </section>
@endsection
