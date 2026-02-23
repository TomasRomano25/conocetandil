<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $rcKey = \App\Models\Configuration::get('recaptcha_site_key'); @endphp
    @if($rcKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $rcKey }}" async defer></script>
    @endif
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { -webkit-text-size-adjust: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100svh;
            min-height: 100vh;
            background-color: #13251B;
            background-image:
                radial-gradient(ellipse at 80% 0%, rgba(82,183,136,0.18) 0%, transparent 50%),
                radial-gradient(ellipse at 5% 90%, rgba(45,106,79,0.55) 0%, transparent 45%);
        }

        .page-wrap {
            display: flex;
            flex-direction: column;
            min-height: 100svh;
            min-height: 100vh;
        }

        /* ─── Mobile brand hero ────────────────────────────────── */
        .mobile-hero {
            padding: 52px 28px 60px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }
        .mobile-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 36px;
        }
        .logo-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(82,183,136,0.25);
            border: 1px solid rgba(82,183,136,0.3);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-icon svg { width: 18px; height: 18px; color: #52B788; }
        .logo-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.01em;
        }
        .back-link {
            font-size: 0.8125rem;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s;
        }
        .back-link:hover { color: rgba(255,255,255,0.8); }
        .hero-heading {
            font-size: 2.375rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 10px;
        }
        .hero-heading em {
            font-style: normal;
            color: #52B788;
        }
        .hero-sub {
            font-size: 0.9375rem;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
            line-height: 1.5;
        }

        /* ─── Form card ────────────────────────────────────────── */
        .form-card {
            background: #fff;
            border-radius: 28px 28px 0 0;
            flex: 1;
            padding: 32px 24px 52px;
            margin-top: -20px;
            position: relative;
            z-index: 2;
            box-shadow: 0 -4px 40px rgba(0,0,0,0.18);
        }

        /* ─── Form elements ────────────────────────────────────── */
        .form-title {
            font-size: 1.3125rem;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }
        .form-subtitle {
            font-size: 0.875rem;
            color: #9CA3AF;
            margin-bottom: 24px;
        }
        .field-group { margin-bottom: 14px; }
        .field-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 7px;
        }
        .input-field {
            display: block;
            width: 100%;
            height: 52px;
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            padding: 0 16px;
            font-size: 1rem;
            color: #111827;
            font-family: inherit;
            outline: none;
            -webkit-appearance: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .input-field::placeholder { color: #D1D5DB; }
        .input-field:focus {
            background: #fff;
            border-color: #2D6A4F;
            box-shadow: 0 0 0 4px rgba(45,106,79,0.10);
        }

        .btn-primary {
            display: block;
            width: 100%;
            height: 54px;
            background: #2D6A4F;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            letter-spacing: -0.01em;
            margin-top: 20px;
            transition: background 0.15s, transform 0.1s, box-shadow 0.15s;
            box-shadow: 0 2px 8px rgba(45,106,79,0.35), 0 1px 2px rgba(45,106,79,0.2);
            -webkit-tap-highlight-color: transparent;
        }
        .btn-primary:hover {
            background: #245c43;
            box-shadow: 0 4px 16px rgba(45,106,79,0.4), 0 2px 4px rgba(45,106,79,0.2);
            transform: translateY(-1px);
        }
        .btn-primary:active {
            background: #1D4A35;
            transform: translateY(0);
            box-shadow: 0 1px 4px rgba(45,106,79,0.3);
        }

        .terms-note {
            font-size: 0.75rem;
            color: #C9CDD4;
            text-align: center;
            margin-top: 12px;
            line-height: 1.5;
        }

        .card-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #F3F4F6;
            text-align: center;
        }
        .card-footer p { font-size: 0.9375rem; color: #9CA3AF; }
        .card-footer a {
            font-weight: 600;
            color: #111827;
            text-decoration: none;
            transition: color 0.15s;
        }
        .card-footer a:hover { color: #2D6A4F; }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }
        .alert-error p {
            font-size: 0.875rem;
            color: #DC2626;
            line-height: 1.5;
        }

        /* ─── Desktop override: split layout ──────────────────── */
        @media (min-width: 1024px) {
            body {
                background-color: #13251B;
                background-image:
                    radial-gradient(ellipse at 80% 0%, rgba(82,183,136,0.18) 0%, transparent 50%),
                    radial-gradient(ellipse at 5% 90%, rgba(45,106,79,0.55) 0%, transparent 45%);
            }
            .page-wrap {
                flex-direction: row;
                min-height: 100vh;
            }
            .mobile-hero {
                width: 44%;
                flex-shrink: 0;
                padding: 52px 60px;
                justify-content: space-between;
            }
            .mobile-topbar { margin-bottom: 0; }
            .hero-heading { font-size: 3.25rem; margin-bottom: 16px; }
            .hero-sub { font-size: 1rem; max-width: 280px; }
            .hero-features { display: flex; flex-direction: column; gap: 14px; margin-top: 40px; }
            .hero-feature { display: flex; align-items: flex-start; gap: 12px; }
            .feature-dot {
                width: 7px; height: 7px;
                background: #52B788;
                border-radius: 50%;
                flex-shrink: 0;
                margin-top: 6px;
            }
            .hero-feature span {
                font-size: 0.9375rem;
                color: rgba(255,255,255,0.65);
                line-height: 1.5;
            }
            .form-card {
                flex: 1;
                border-radius: 0;
                margin-top: 0;
                padding: 0;
                box-shadow: none;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #fff;
            }
            .form-inner {
                width: 100%;
                max-width: 400px;
                padding: 0 40px;
            }
            .form-title { font-size: 1.75rem; margin-bottom: 8px; }
            .form-subtitle { margin-bottom: 28px; }
        }
        @media (min-width: 1280px) {
            .mobile-hero { padding: 64px 72px; }
            .hero-heading { font-size: 3.75rem; }
        }
    </style>
</head>
<body>
<div class="page-wrap">

    {{-- ══════════ BRAND SECTION ══════════ --}}
    <div class="mobile-hero">
        <div class="mobile-topbar">
            <a href="{{ route('inicio') }}" class="logo-badge">
                <span class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <span class="logo-name">Conoce Tandil</span>
            </a>
            <a href="{{ route('inicio') }}" class="back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>

        <div>
            <h1 class="hero-heading">
                Tu próxima<br>aventura<br><em>empieza acá.</em>
            </h1>
            <p class="hero-sub">Creá tu cuenta gratis en segundos.</p>

            <div class="hero-features" style="display:none" id="hero-features-list">
                <div class="hero-feature">
                    <span class="feature-dot"></span>
                    <span>Acceso gratuito a cientos de lugares</span>
                </div>
                <div class="hero-feature">
                    <span class="feature-dot"></span>
                    <span>Guardá tus favoritos y planificá tu viaje</span>
                </div>
                <div class="hero-feature">
                    <span class="feature-dot"></span>
                    <span>Upgrades premium disponibles siempre</span>
                </div>
            </div>
        </div>

        <div style="display:none" id="hero-back-bottom">
            <a href="{{ route('inicio') }}" class="back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al sitio
            </a>
        </div>
    </div>

    {{-- ══════════ FORM CARD ══════════ --}}
    <div class="form-card">
        <div class="form-inner" id="form-inner">

            <p class="form-title">Crear cuenta</p>
            <p class="form-subtitle">Es gratis, siempre.</p>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-register">

                <div class="field-group">
                    <label for="name" class="field-label">Nombre completo</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name') }}" required autofocus
                        placeholder="Tu nombre"
                        class="input-field">
                </div>

                <div class="field-group">
                    <label for="email" class="field-label">Correo electrónico</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email') }}" required
                        placeholder="nombre@email.com"
                        class="input-field">
                </div>

                <div class="field-group">
                    <label for="password" class="field-label">Contraseña</label>
                    <input type="password" name="password" id="password"
                        required placeholder="Mínimo 8 caracteres"
                        class="input-field">
                </div>

                <div class="field-group">
                    <label for="password_confirmation" class="field-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        required placeholder="Repetí tu contraseña"
                        class="input-field">
                </div>

                <button type="submit" class="btn-primary">Crear cuenta gratis</button>
                <p class="terms-note">Al registrarte aceptás nuestros términos y política de privacidad.</p>
            </form>

            @if(isset($rcKey) && $rcKey)
            <script>
            document.getElementById('register-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var form = this;
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ $rcKey }}', {action: 'register'}).then(function(token) {
                        document.getElementById('g-recaptcha-register').value = token;
                        form.submit();
                    });
                });
            });
            </script>
            @endif

            <div class="card-footer">
                <p>¿Ya tenés cuenta? <a href="{{ route('login') }}">Iniciá sesión</a></p>
            </div>

        </div>
    </div>

</div>

<script>
    function applyDesktopLayout() {
        var isDesktop = window.innerWidth >= 1024;
        var featureList = document.getElementById('hero-features-list');
        var backBottom = document.getElementById('hero-back-bottom');
        if (featureList) featureList.style.display = isDesktop ? 'flex' : 'none';
        if (backBottom) backBottom.style.display = isDesktop ? 'block' : 'none';
    }
    applyDesktopLayout();
    window.addEventListener('resize', applyDesktopLayout);
</script>
</body>
</html>
