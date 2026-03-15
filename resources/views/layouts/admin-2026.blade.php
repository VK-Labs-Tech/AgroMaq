<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Agromaq 2026')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --app-ink: #1b1f2a;
            --app-muted: #667085;
            --app-brand: #0d6a9f;
            --app-brand-soft: #e4f4fb;
            --app-surface: #f7f8fa;
            --app-card: #ffffff;
            --app-accent: #f59e0b;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--app-ink);
            background:
                radial-gradient(1000px 500px at 85% -10%, #d9f2ff 0%, transparent 60%),
                radial-gradient(700px 350px at 0% 0%, #fff3d5 0%, transparent 45%),
                var(--app-surface);
            min-height: 100vh;
        }

        .app-shell {
            max-width: 1320px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2.5rem;
        }

        .app-navbar {
            border-radius: 16px;
            background: linear-gradient(90deg, #0b2030 0%, #163e5a 100%);
            box-shadow: 0 8px 28px rgba(12, 36, 54, 0.22);
        }

        .app-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .app-muted {
            color: var(--app-muted);
        }

        .app-panel {
            background: var(--app-card);
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(13, 26, 39, 0.08);
        }

        .app-chip {
            border-radius: 999px;
            padding: 0.4rem 0.85rem;
            border: 1px solid #d7dde5;
            background: #fff;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .app-chip.active {
            background: var(--app-brand);
            color: #fff;
            border-color: var(--app-brand);
        }

        @media (max-width: 768px) {
            .app-shell {
                padding: 1rem 0.75rem 2rem;
            }
        }
    </style>
    @yield('after_styles')
</head>
<body>
    <div class="app-shell">
        <nav class="navbar navbar-expand-lg app-navbar mb-4 px-3 px-lg-4">
            <div class="container-fluid px-0">
                <a class="navbar-brand text-white app-title" href="{{ route('admin.diario-bordo.index') }}">Agromaq 2026</a>
                <div class="navbar-nav">
                    <a class="nav-link text-white-50" href="{{ route('admin.diario-bordo.index') }}">Diario de Bordo</a>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('after_scripts')
</body>
</html>
