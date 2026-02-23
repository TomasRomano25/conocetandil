<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #F5F4F0;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(45,106,79,0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(82,183,136,0.05) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

    {{-- Back to site --}}
    <div class="w-full max-w-sm mb-6">
        <a href="{{ route('inicio') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-[#2D6A4F] transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al sitio
        </a>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-sm">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('inicio') }}"
                class="inline-block text-2xl font-bold text-[#1A1A1A] tracking-tight hover:text-[#2D6A4F] transition-colors duration-200">
                Conoce Tandil
            </a>
            <p class="text-gray-400 text-sm mt-1.5 font-normal">Accedé a tu cuenta para continuar</p>
        </div>

        <div class="bg-white rounded-2xl shadow-[0_2px_24px_rgba(0,0,0,0.07)] border border-gray-100 px-7 py-8 sm:px-8">

            {{-- Errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        placeholder="tu@email.com"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Contraseña
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-[#2D6A4F] hover:text-[#1A1A1A] transition-colors duration-200 font-medium">
                            ¿Olvidaste la tuya?
                        </a>
                    </div>
                    <input type="password" name="password" id="password" required
                        placeholder="••••••••"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <div class="flex items-center gap-2.5 pt-1">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-gray-300 text-[#2D6A4F] focus:ring-[#2D6A4F]/30 cursor-pointer">
                    <label for="remember" class="text-sm text-gray-500 cursor-pointer select-none">Recordarme</label>
                </div>

                <button type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#245c43] active:bg-[#1e4f3a] text-white font-semibold py-3.5 px-4 rounded-xl text-sm tracking-wide transition-all duration-200 shadow-sm hover:shadow-md mt-2">
                    Ingresar
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 mb-3">¿Todavía no tenés cuenta?</p>
                <a href="{{ route('register') }}"
                    class="inline-block w-full text-center text-sm font-semibold text-[#2D6A4F] hover:text-white hover:bg-[#2D6A4F] border border-[#2D6A4F]/30 hover:border-[#2D6A4F] py-3 rounded-xl transition-all duration-200">
                    Crear cuenta gratis
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-4 bg-[#2D6A4F]/10 border border-[#2D6A4F]/20 text-[#2D6A4F] px-4 py-3 rounded-xl text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

    </div>

</body>
</html>
