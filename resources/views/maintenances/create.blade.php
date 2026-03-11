@extends('layouts.app')

@section('title', 'Nova Manutencao | AgroMaq')

@section('content')
    <section class="panel fade-up">
        <h1 class="panel-title mb-4">Registrar manutencao</h1>
        <form action="{{ route('maintenances.store') }}" method="POST">
            @include('maintenances._form', ['method' => 'POST'])
        </form>
    </section>
@endsection
