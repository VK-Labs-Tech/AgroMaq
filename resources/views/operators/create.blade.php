@extends('layouts.app')

@section('title', 'Novo Operador | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Cadastrar operador</h1>
        <form action="{{ route('operators.store') }}" method="POST">
            @include('operators._form', ['method' => 'POST'])
        </form>
    </section>
@endsection
