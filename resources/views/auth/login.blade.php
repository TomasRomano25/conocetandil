<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#2D6A4F] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('inicio') }}" class="text-3xl font-bold text-white tracking-tight">Conoce Tandil</a>
            <p class="text-[#52B788] mt-2">Accedé a tu cuenta</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6 text-center">Iniciar Sesión</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                        <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-[#2D6A4F] hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3 px-4 rounded-lg transition">
                    Ingresar
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 mb-3">¿Primera vez en Conoce Tandil?</p>
                <a href="{{ route('register') }}"
                    class="inline-block w-full text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 text-[#2D6A4F] font-bold py-3 rounded-lg transition text-sm">
                    Crear cuenta gratis
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-green-500/20 border border-green-400/30 text-green-100 px-4 py-3 rounded-lg mt-4 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <p class="text-center mt-6">
            <a href="{{ route('inicio') }}" class="text-white/80 hover:text-white text-sm transition">&larr; Volver al sitio</a>
        </p>
    </div>
</body>
</html>
