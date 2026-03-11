@extends('layouts.app')

@section('title', 'Novo Abastecimento | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Registrar abastecimento</h1>
        <form action="{{ route('fuel-records.store') }}" method="POST">
            @include('fuel-records._form', ['method' => 'POST'])
        </form>
    </section>
@endsection
