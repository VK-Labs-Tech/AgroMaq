@extends('layouts.app')

@section('title', 'Editar Sessao de Trabalho | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Editar sessao de trabalho</h1>
        <form action="{{ route('work-logs.update', $workLog) }}" method="POST">
            @include('work-logs._form', ['method' => 'PUT'])
        </form>
    </section>
@endsection
