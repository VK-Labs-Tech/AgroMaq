@extends('layouts.app')

@section('title', 'Editar Manutencao | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Editar manutencao</h1>
        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
            @include('maintenances._form', ['method' => 'PUT'])
        </form>
    </section>
@endsection
