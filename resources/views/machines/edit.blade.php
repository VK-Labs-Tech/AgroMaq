@extends('layouts.app')

@section('title', 'Editar Maquina | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Editar maquina</h1>
        <form action="{{ route('machines.update', $machine) }}" method="POST">
            @include('machines._form', ['method' => 'PUT'])
        </form>
    </section>
@endsection
