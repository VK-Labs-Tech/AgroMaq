@extends('layouts.app')

@section('title', 'Editar Abastecimento | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Editar abastecimento</h1>
        <form action="{{ route('fuel-records.update', $fuelRecord) }}" method="POST">
            @include('fuel-records._form', ['method' => 'PUT'])
        </form>
    </section>
@endsection
