@extends('layouts.app')

@section('title', 'Nova Sessao de Trabalho | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Registrar horas trabalhadas</h1>
        <form action="{{ route('work-logs.store') }}" method="POST">
            @include('work-logs._form', ['method' => 'POST'])
        </form>
    </section>
@endsection
