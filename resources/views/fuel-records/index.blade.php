@extends('layouts.app')

@section('title', 'Combustivel | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Controle de combustivel</h1>
            <a href="{{ route('fuel-records.create') }}" class="btn-primary">Novo abastecimento</a>
        </div>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Maquina</th>
                        <th>Litros</th>
                        <th>Preco/L</th>
                        <th>Custo</th>
                        <th>Horimetro</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fuelRecords as $fuelRecord)
                        <tr>
                            <td>{{ $fuelRecord->fueled_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $fuelRecord->machine->name }}</td>
                            <td>{{ number_format($fuelRecord->liters, 2, ',', '.') }} L</td>
                            <td>R$ {{ number_format($fuelRecord->price_per_liter, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($fuelRecord->total_cost, 2, ',', '.') }}</td>
                            <td>{{ number_format($fuelRecord->hour_meter, 2, ',', '.') }} h</td>
                            <td>
                                <div class="flex gap-2 text-sm">
                                    <a href="{{ route('fuel-records.show', $fuelRecord) }}" class="action-link">Ver</a>
                                    <a href="{{ route('fuel-records.edit', $fuelRecord) }}" class="action-link">Editar</a>
                                    <form action="{{ route('fuel-records.destroy', $fuelRecord) }}" method="POST" onsubmit="return confirm('Remover este abastecimento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link text-red-700" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-zinc-500">Nenhum abastecimento registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $fuelRecords->links() }}</div>
    </section>
@endsection
