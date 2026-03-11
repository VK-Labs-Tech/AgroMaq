@extends('layouts.app')

@section('title', 'Operadores | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Operadores</h1>
            <a href="{{ route('operators.create') }}" class="btn-primary">Novo operador</a>
        </div>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>CNH</th>
                        <th>Status</th>
                        <th>Horas trabalhadas</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operators as $operator)
                        <tr>
                            <td>{{ $operator->name }}</td>
                            <td>{{ $operator->cpf }}</td>
                            <td>
                                <p>{{ $operator->license_number ?: '-' }}</p>
                                <p class="text-xs text-zinc-500">
                                    Cat {{ $operator->license_category ?: '-' }}
                                    @if ($operator->license_expires_at)
                                        - {{ $operator->license_expires_at->format('d/m/Y') }}
                                    @endif
                                </p>
                            </td>
                            <td>
                                <span class="badge {{ $operator->active ? 'badge-success' : 'badge-neutral' }}">
                                    {{ $operator->active ? 'ATIVO' : 'INATIVO' }}
                                </span>
                            </td>
                            <td>{{ number_format($operator->totalWorkedHours(), 2, ',', '.') }} h</td>
                            <td>
                                <div class="flex gap-2 text-sm">
                                    <a href="{{ route('operators.show', $operator) }}" class="action-link">Ver</a>
                                    <a href="{{ route('operators.edit', $operator) }}" class="action-link">Editar</a>
                                    <form action="{{ route('operators.destroy', $operator) }}" method="POST" onsubmit="return confirm('Remover este operador?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link text-red-700" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-zinc-500">Nenhum operador cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $operators->links() }}</div>
    </section>
@endsection
