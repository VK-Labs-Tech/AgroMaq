@extends('layouts.app')

@section('title', 'Editar Operador | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Editar operador</h1>
        <form action="{{ route('operators.update', $operator) }}" method="POST">
            @include('operators._form', ['method' => 'PUT'])
        </form>
    </section>
@endsection
