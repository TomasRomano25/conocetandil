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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }

        .brand-panel {
            background-color: #13251B;
            background-image:
                radial-gradient(circle at 15% 85%, rgba(45,106,79,0.45) 0%, transparent 45%),
                radial-gradient(circle at 85% 15%, rgba(82,183,136,0.12) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(45,106,79,0.08) 0%, transparent 70%);
        }

        .input-field {
            display: block;
            width: 100%;
            background: #fff;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.9375rem;
            color: #111827;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
        }
        .input-field::placeholder { color: #C9CDD4; }
        .input-field:focus {
            border-color: #2D6A4F;
            box-shadow: 0 0 0 3px rgba(45,106,79,0.12);
        }
        .input-field:hover:not(:focus) { border-color: #D1D5DB; }

        .btn-primary {
            width: 100%;
            background: #2D6A4F;
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            padding: 13px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
            box-shadow: 0 1px 3px rgba(45,106,79,0.3), 0 4px 12px rgba(45,106,79,0.15);
        }
        .btn-primary:hover {
            background: #255A41;
            box-shadow: 0 2px 6px rgba(45,106,79,0.35), 0 8px 20px rgba(45,106,79,0.2);
            transform: translateY(-1px);
        }
        .btn-primary:active {
            background: #1D4A35;
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(45,106,79,0.3);
        }

        .feature-dot {
            width: 6px;
            height: 6px;
            background: #52B788;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 6px;
        }
    </style>
</head>
<body class="min-h-screen flex bg-white">

    {{-- ═══════════════════════════════ LEFT — Brand Panel ═══════════════════════════════ --}}
    <div class="brand-panel hidden lg:flex lg:w-[44%] xl:w-[42%] flex-col justify-between p-12 xl:p-16 relative overflow-hidden flex-shrink-0">

        {{-- Decorative rings --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full border border-white/5 pointer-events-none"></div>
        <div class="absolute -top-12 -right-12 w-56 h-56 rounded-full border border-white/5 pointer-events-none"></div>

        {{-- Top: logo --}}
        <div>
            <a href="{{ route('inicio') }}" class="inline-flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-[#2D6A4F] flex items-center justify-center flex-shrink-0 group-hover:bg-[#52B788] transition-colors duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-white font-semibold text-lg tracking-tight">Conoce Tandil</span>
            </a>
        </div>

        {{-- Middle: headline & features --}}
        <div>
            <h1 class="text-white text-4xl xl:text-5xl font-bold leading-[1.15] tracking-tight mb-6">
                Tu próxima<br>
                aventura<br>
                <span class="text-[#52B788]">empieza acá.</span>
            </h1>
            <p class="text-white/55 text-base leading-relaxed mb-10 max-w-xs">
                Creá tu cuenta gratis y accedé a los mejores destinos, guías y experiencias de Tandil.
            </p>

            <ul class="space-y-4">
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Acceso gratuito a cientos de lugares</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Guardá tus favoritos y planificá tu viaje</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Upgrades premium disponibles en cualquier momento</span>
                </li>
            </ul>
        </div>

        {{-- Bottom --}}
        <div>
            <a href="{{ route('inicio') }}"
                class="inline-flex items-center gap-2 text-white/40 hover:text-white/80 text-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al sitio
            </a>
        </div>

        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full border border-white/5 pointer-events-none"></div>
    </div>

    {{-- ═══════════════════════════════ RIGHT — Form Panel ═══════════════════════════════ --}}
    <div class="flex-1 flex flex-col justify-center px-6 py-10 sm:px-10 lg:px-16 xl:px-24">

        {{-- Mobile: brand + back --}}
        <div class="lg:hidden flex items-center justify-between mb-10">
            <a href="{{ route('inicio') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#2D6A4F] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">Conoce Tandil</span>
            </a>
            <a href="{{ route('inicio') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                ← Volver
            </a>
        </div>

        {{-- Form container --}}
        <div class="w-full max-w-[400px] mx-auto">

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Creá tu cuenta</h2>
                <p class="text-gray-400 text-sm mt-1.5">Es gratis, siempre.</p>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-register">

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre completo
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        placeholder="Tu nombre"
                        class="input-field">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="nombre@email.com"
                        class="input-field">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input type="password" name="password" id="password" required
                        placeholder="Mínimo 8 caracteres"
                        class="input-field">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Repetí tu contraseña"
                        class="input-field">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary">
                        Crear cuenta gratis
                    </button>
                </div>

                <p class="text-xs text-gray-400 text-center pt-1">
                    Al registrarte aceptás nuestros términos y política de privacidad.
                </p>
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

            <div class="mt-8 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-400 text-center">
                    ¿Ya tenés cuenta?
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-900 hover:text-[#2D6A4F] transition-colors duration-150 ml-1">
                        Iniciá sesión
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>
