@extends('layouts.app')

@section('title', 'Horas Trabalhadas | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="panel-title">Horas trabalhadas</h1>
            <a href="{{ route('work-logs.create') }}" class="btn-primary">Nova sessao</a>
        </div>

        <div class="table-shell overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Maquina</th>
                        <th>Operador</th>
                        <th>Horas</th>
                        <th>Horimetro</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($workLogs as $workLog)
                        <tr>
                            <td>{{ $workLog->started_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $workLog->machine->name }}</td>
                            <td>{{ $workLog->operator->name }}</td>
                            <td>{{ number_format($workLog->hours_worked, 2, ',', '.') }} h</td>
                            <td>{{ number_format($workLog->start_hour_meter, 2, ',', '.') }} -> {{ number_format($workLog->end_hour_meter, 2, ',', '.') }}</td>
                            <td>
                                <div class="flex gap-2 text-sm">
                                    <a href="{{ route('work-logs.show', $workLog) }}" class="action-link">Ver</a>
                                    <a href="{{ route('work-logs.edit', $workLog) }}" class="action-link">Editar</a>
                                    <form action="{{ route('work-logs.destroy', $workLog) }}" method="POST" onsubmit="return confirm('Remover esta sessao?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-link text-red-700" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-zinc-500">Nenhuma sessao de trabalho registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $workLogs->links() }}</div>
    </section>
@endsection
