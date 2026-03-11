@extends('layouts.app')

@section('title', 'Nova Maquina | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Cadastrar maquina</h1>
        <form action="{{ route('machines.store') }}" method="POST">
            @include('machines._form', ['method' => 'POST'])
        </form>
    </section>
@endsection
