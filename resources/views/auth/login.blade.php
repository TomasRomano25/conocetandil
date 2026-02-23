<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        {{-- Decorative ring top-right --}}
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
                Explorá Tandil<br>
                <span class="text-[#52B788]">como nunca</span><br>
                antes.
            </h1>
            <p class="text-white/55 text-base leading-relaxed mb-10 max-w-xs">
                Itinerarios, lugares únicos y experiencias pensadas para que tu visita sea inolvidable.
            </p>

            <ul class="space-y-4">
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Lugares seleccionados con reseñas reales</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Itinerarios premium según tus intereses</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="feature-dot mt-[7px]"></span>
                    <span class="text-white/70 text-sm leading-snug">Guías actualizadas por locales</span>
                </li>
            </ul>
        </div>

        {{-- Bottom: back link --}}
        <div>
            <a href="{{ route('inicio') }}"
                class="inline-flex items-center gap-2 text-white/40 hover:text-white/80 text-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al sitio
            </a>
        </div>

        {{-- Decorative bottom-left ring --}}
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
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Bienvenido de nuevo</h2>
                <p class="text-gray-400 text-sm mt-1.5">Ingresá con tu cuenta para continuar.</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="bg-[#2D6A4F]/8 border border-[#2D6A4F]/20 text-[#2D6A4F] px-4 py-3 rounded-xl text-sm mb-6">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        placeholder="nombre@email.com"
                        class="input-field">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-medium text-[#2D6A4F] hover:text-[#1A1A1A] transition-colors duration-150">
                            ¿Olvidaste la tuya?
                        </a>
                    </div>
                    <input type="password" name="password" id="password" required
                        placeholder="Tu contraseña"
                        class="input-field">
                </div>

                <div class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-gray-300 text-[#2D6A4F] focus:ring-[#2D6A4F]/25 cursor-pointer">
                    <label for="remember" class="text-sm text-gray-500 cursor-pointer select-none">
                        Mantenerme conectado
                    </label>
                </div>

                <div class="pt-1">
                    <button type="submit" class="btn-primary">
                        Ingresar
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-400 text-center">
                    ¿No tenés cuenta?
                    <a href="{{ route('register') }}"
                        class="font-semibold text-gray-900 hover:text-[#2D6A4F] transition-colors duration-150 ml-1">
                        Registrate gratis
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>
