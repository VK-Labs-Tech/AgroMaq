<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgroMaq')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Syne:wght@500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen px-4 py-6 md:px-8">
        <header class="mx-auto mb-6 max-w-7xl fade-up">
            <div class="glass-card p-4 md:p-5">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <a href="{{ route('dashboard') }}" class="agro-title text-2xl tracking-tight text-white md:text-3xl">AgroMaq</a>
                        <p class="text-sm text-emerald-100">Gestao inteligente de maquinas agricolas</p>
                    </div>
                    <nav class="flex flex-wrap gap-2 text-sm">
                        <a class="nav-pill {{ request()->routeIs('dashboard') ? 'nav-pill-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-pill {{ request()->routeIs('machines.*') ? 'nav-pill-active' : '' }}" href="{{ route('machines.index') }}">Maquinas</a>
                        <a class="nav-pill {{ request()->routeIs('operators.*') ? 'nav-pill-active' : '' }}" href="{{ route('operators.index') }}">Operadores</a>
                        <a class="nav-pill {{ request()->routeIs('work-logs.*') ? 'nav-pill-active' : '' }}" href="{{ route('work-logs.index') }}">Horas</a>
                        <a class="nav-pill {{ request()->routeIs('fuel-records.*') ? 'nav-pill-active' : '' }}" href="{{ route('fuel-records.index') }}">Combustivel</a>
                        <a class="nav-pill {{ request()->routeIs('maintenances.*') ? 'nav-pill-active' : '' }}" href="{{ route('maintenances.index') }}">Manutencoes</a>
                        <a class="nav-pill {{ request()->routeIs('maintenances.preventive-launch*') ? 'nav-pill-active' : '' }}" href="{{ route('maintenances.preventive-launch') }}">Prev. lancamento</a>
                        <a class="nav-pill {{ request()->routeIs('reports.*') ? 'nav-pill-active' : '' }}" href="{{ route('reports.operational-costs') }}">Relatorios</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl space-y-4">
            @if (session('success'))
                <div class="notice-success fade-up">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="notice-error fade-up">
                    <p class="mb-2 font-semibold">Revise os dados informados:</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
