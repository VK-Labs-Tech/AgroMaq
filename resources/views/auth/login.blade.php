<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroMaq — Entrar</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Banner ───────────────────────────────────── */
        .login-banner {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse 70% 55% at 15% 10%, rgba(168,211,111,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 85%, rgba(11,36,25,0.6) 0%, transparent 60%),
                linear-gradient(155deg, #143a2d 0%, #1f6a4d 45%, #277a58 75%, #1a5038 100%);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            animation: drift 10s ease-in-out infinite alternate;
        }
        .orb-1 { width:320px;height:320px;background:rgba(168,211,111,0.14);top:-80px;left:-60px;animation-delay:0s; }
        .orb-2 { width:240px;height:240px;background:rgba(239,201,90,0.1);bottom:40px;right:-40px;animation-delay:-4s; }
        .orb-3 { width:180px;height:180px;background:rgba(46,142,103,0.2);top:45%;left:30%;animation-delay:-7s; }

        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(20px,30px) scale(1.08); }
        }

        .banner-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 9999px;
            padding: 0.45rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(236,253,245,0.82);
            backdrop-filter: blur(8px);
        }

        .stat-dot {
            width:7px;height:7px;border-radius:50%;
            background:#a8d36f;
            box-shadow:0 0 6px rgba(168,211,111,0.8);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%,100% { opacity:1;transform:scale(1); }
            50%      { opacity:0.6;transform:scale(0.85); }
        }

        .banner-arc {
            position:absolute;bottom:-1px;right:-1px;
            width:180px;height:180px;
            border:1px solid rgba(168,211,111,0.15);
            border-radius:50%;
            transform:translate(50%,50%);
        }
        .banner-arc-2 {
            width:280px;height:280px;
            border-color:rgba(168,211,111,0.08);
        }

        /* ── Form side ────────────────────────────────── */
        .login-form-side {
            background: #f9f7f2;
            background-image:
                radial-gradient(ellipse 70% 50% at 100% 0%, rgba(239,201,90,0.1) 0%, transparent 55%),
                radial-gradient(ellipse 50% 60% at 0% 100%, rgba(20,58,45,0.05) 0%, transparent 50%);
        }

        .login-card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(20,58,45,0.08);
            border-radius: 1.25rem;
            box-shadow:
                0 1px 2px rgba(20,58,45,0.04),
                0 8px 24px rgba(20,58,45,0.08),
                0 32px 64px rgba(20,58,45,0.06);
            backdrop-filter: blur(8px);
            padding: 2.5rem;
        }

        /* ── Inputs ───────────────────────────────────── */
        .login-input {
            width: 100%;
            background: #ffffff;
            border: 1.5px solid #d4ddd8;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.88rem;
            font-family: 'Manrope', sans-serif;
            color: #1a2e24;
            transition: border-color 150ms ease, box-shadow 150ms ease;
            outline: none;
        }
        .login-input::placeholder { color: #a0b4ab; }
        .login-input:focus {
            border-color: #2e8e67;
            box-shadow: 0 0 0 3.5px rgba(46,142,103,0.13);
        }

        .input-wrapper { position: relative; }
        .input-icon {
            position:absolute;left:0.85rem;top:50%;
            transform:translateY(-50%);
            color:#8aab9e;pointer-events:none;
        }
        .toggle-pw {
            position:absolute;right:0.85rem;top:50%;
            transform:translateY(-50%);
            background:none;border:none;cursor:pointer;
            color:#8aab9e;padding:0;
            transition:color 150ms;
        }
        .toggle-pw:hover { color:#2e8e67; }
        .login-input.has-toggle { padding-right: 2.5rem; }

        /* ── Submit ───────────────────────────────────── */
        .btn-login {
            width:100%;display:flex;align-items:center;justify-content:center;gap:0.5rem;
            padding:0.82rem 1rem;border-radius:0.75rem;border:none;
            font-family:'Manrope',sans-serif;font-size:0.9rem;font-weight:700;
            color:#f0fdf4;
            background:linear-gradient(135deg,#143a2d 0%,#1f6a4d 60%,#2e8e67 100%);
            box-shadow:0 2px 8px rgba(20,58,45,0.25),0 1px 2px rgba(20,58,45,0.12);
            cursor:pointer;transition:all 180ms ease;letter-spacing:0.01em;
        }
        .btn-login:hover {
            background:linear-gradient(135deg,#0b2419 0%,#143a2d 60%,#1f6a4d 100%);
            box-shadow:0 6px 20px rgba(20,58,45,0.32),0 2px 4px rgba(20,58,45,0.14);
            transform:translateY(-1px);
        }
        .btn-login:active { transform:translateY(0); }

        /* ── Checkbox ─────────────────────────────────── */
        .custom-check {
            appearance:none;width:1rem;height:1rem;
            border:1.5px solid #c2d0ca;border-radius:0.3rem;
            background:#fff;cursor:pointer;
            transition:all 150ms;flex-shrink:0;position:relative;
        }
        .custom-check:checked { background:#1f6a4d;border-color:#1f6a4d; }
        .custom-check:checked::after {
            content:'';position:absolute;
            left:2.5px;top:0.5px;width:5px;height:8px;
            border:2px solid white;border-top:none;border-left:none;
            transform:rotate(45deg);
        }

        /* ── Divider ──────────────────────────────────── */
        .or-divider {
            display:flex;align-items:center;gap:0.75rem;
            font-size:0.72rem;color:#9aada5;font-weight:700;
            letter-spacing:0.07em;text-transform:uppercase;
        }
        .or-divider::before,.or-divider::after {
            content:'';flex:1;height:1px;background:rgba(20,58,45,0.09);
        }

        /* ── Animations ───────────────────────────────── */
        .banner-in {
            opacity:0;
            animation:fadeIn 700ms ease forwards;
        }
        .banner-in-2 { animation-delay:150ms; }
        .banner-in-3 { animation-delay:280ms; }

        .stagger > * {
            opacity:0;
            animation:slideUp 500ms cubic-bezier(0.22,1,0.36,1) forwards;
        }
        .stagger > *:nth-child(1) { animation-delay:100ms; }
        .stagger > *:nth-child(2) { animation-delay:160ms; }
        .stagger > *:nth-child(3) { animation-delay:220ms; }
        .stagger > *:nth-child(4) { animation-delay:280ms; }
        .stagger > *:nth-child(5) { animation-delay:340ms; }
        .stagger > *:nth-child(6) { animation-delay:400ms; }
        .stagger > *:nth-child(7) { animation-delay:460ms; }

        @keyframes fadeIn  { to { opacity:1; } }
        @keyframes slideUp {
            from { opacity:0;transform:translateY(14px); }
            to   { opacity:1;transform:translateY(0); }
        }
    </style>
</head>

<body class="min-h-screen flex" style="font-family:'Manrope',sans-serif;">

{{-- ══════════════════════════════════════
     BANNER LATERAL
══════════════════════════════════════ --}}
<div class="login-banner hidden lg:flex lg:w-[48%] flex-col justify-between p-12" style="min-height:100vh;">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="banner-grid"></div>
    <div class="banner-arc"></div>
    <div class="banner-arc banner-arc-2"></div>

    {{-- Logo --}}
    <div class="banner-in relative z-10">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <div style="width:42px;height:42px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.18);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a8d36f" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:#f0fdf4;letter-spacing:-0.02em;">AgroMaq</span>
        </div>
    </div>

    {{-- Hero --}}
    <div class="banner-in banner-in-2 relative z-10">
        <div class="stat-chip" style="margin-bottom:2rem;width:fit-content;">
            <span class="stat-dot"></span>
            Sistema de Gestão Agrícola
        </div>

        <h1 style="font-family:'Syne',sans-serif;font-size:2.9rem;font-weight:800;color:#f0fdf4;line-height:1.08;letter-spacing:-0.03em;margin-bottom:1.2rem;">
            Gestão que<br>
            <em style="font-style:italic;color:#a8d36f;text-shadow:0 0 30px rgba(168,211,111,0.4);">transforma</em><br>
            o campo.
        </h1>

        <p style="color:rgba(240,253,244,0.5);font-size:0.88rem;line-height:1.75;max-width:270px;margin-bottom:2.5rem;">
            Controle máquinas, equipes e produtividade com precisão — tudo em um só lugar.
        </p>

        {{-- Stats --}}
        <div style="display:flex;gap:2rem;">
            @foreach([['200+','Produtores'],['98%','Satisfação'],['5x','Eficiência']] as $stat)
                <div>
                    <div style="font-family:'Syne',sans-serif;font-size:1.7rem;font-weight:800;color:#f0fdf4;letter-spacing:-0.03em;">{{ $stat[0] }}</div>
                    <div style="font-size:0.68rem;color:rgba(240,253,244,0.38);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;margin-top:0.15rem;">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Footer --}}
    <div class="banner-in banner-in-3 relative z-10">
        <p style="font-size:0.75rem;color:rgba(240,253,244,0.38);display:flex;align-items:center;gap:0.5rem;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Dados protegidos com criptografia SSL
        </p>
    </div>
</div>

{{-- ══════════════════════════════════════
     FORMULÁRIO
══════════════════════════════════════ --}}
<div class="login-form-side flex-1 flex items-center justify-center px-6 py-12 lg:px-14">
    <div style="width:100%;max-width:410px;">

        {{-- Mobile logo --}}
        <div class="lg:hidden" style="margin-bottom:2rem;">
            <span style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#143a2d;">AgroMaq</span>
        </div>

        <div class="login-card">
            <div class="stagger">

                {{-- Header --}}
                <div style="margin-bottom:1.75rem;">
                    <div style="display:inline-flex;align-items:center;gap:0.35rem;background:#f0fdf4;border:1px solid #86efac;border-radius:9999px;padding:0.22rem 0.65rem;font-size:0.65rem;font-weight:800;color:#14532d;letter-spacing:0.07em;text-transform:uppercase;margin-bottom:0.9rem;">
                        <svg width="8" height="8" viewBox="0 0 8 8" fill="#14532d"><circle cx="4" cy="4" r="4"/></svg>
                        Acesso seguro
                    </div>
                    <h2 style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;color:#0f2d1f;letter-spacing:-0.025em;line-height:1.2;margin-bottom:0.35rem;">
                        Bem-vindo de volta
                    </h2>
                    <p style="font-size:0.82rem;color:#7a8a82;line-height:1.5;">
                        Entre com suas credenciais para acessar o painel
                    </p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div style="display:flex;align-items:flex-start;gap:0.6rem;background:#fff5f5;border:1px solid #fca5a5;border-radius:0.75rem;padding:0.8rem 1rem;margin-bottom:0.5rem;">
                        <svg style="flex-shrink:0;margin-top:1px;color:#dc2626;" width="15" height="15" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p style="font-size:0.81rem;color:#7f1d1d;font-weight:500;">{{ $errors->first('email') }}</p>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('login.auth') }}" style="display:flex;flex-direction:column;gap:1rem;">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" style="display:block;font-size:0.7rem;font-weight:700;color:#4d6157;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.4rem;">E-mail</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com.br" required autocomplete="email" class="login-input" />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.4rem;">
                            <label for="password" style="font-size:0.7rem;font-weight:700;color:#4d6157;text-transform:uppercase;letter-spacing:0.08em;">Senha</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:0.75rem;font-weight:600;color:#2e8e67;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Esqueceu?</a>
                            @endif
                        </div>
                        <div class="input-wrapper">
                            <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" id="passwordField" name="password" placeholder="••••••••••" required autocomplete="current-password" class="login-input has-toggle" />
                            <button type="button" class="toggle-pw" onclick="togglePassword()" title="Mostrar/ocultar senha">
                                <svg id="eyeIcon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <input type="checkbox" id="remember" name="remember" class="custom-check" />
                        <label for="remember" style="font-size:0.82rem;color:#6b7a72;cursor:pointer;user-select:none;">Manter-me conectado</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login" style="margin-top:0.3rem;">
                        Entrar na plataforma
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>

                {{-- Register link --}}
                @if (Route::has('register'))
                    <div>
                        <div class="or-divider" style="margin:1.5rem 0 1.25rem;">ou</div>
                        <p style="text-align:center;font-size:0.82rem;color:#7a8a82;">
                            Não tem uma conta?
                            <a href="{{ route('register') }}" style="color:#1f6a4d;font-weight:700;text-decoration:none;margin-left:3px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Cadastre-se grátis →
                            </a>
                        </p>
                    </div>
                @endif

            </div>{{-- /stagger --}}
        </div>{{-- /login-card --}}

        <p style="text-align:center;font-size:0.7rem;color:#9aada5;margin-top:1.25rem;display:flex;align-items:center;justify-content:center;gap:0.35rem;">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            Conexão protegida com criptografia SSL
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        const f = document.getElementById('passwordField');
        const i = document.getElementById('eyeIcon');
        if (f.type === 'password') {
            f.type = 'text';
            i.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;
        } else {
            f.type = 'password';
            i.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        }
    }
</script>

</body>
</html>
