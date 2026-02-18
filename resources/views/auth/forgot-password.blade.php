<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#2D6A4F] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('inicio') }}" class="text-3xl font-bold text-white tracking-tight">Conoce Tandil</a>
            <p class="text-[#52B788] mt-2">Recuperar contraseña</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-[#1A1A1A] mb-2 text-center">¿Olvidaste tu contraseña?</h2>
            <p class="text-gray-500 text-sm text-center mb-6">Ingresá tu email y te enviamos un enlace para restablecerla.</p>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>

                <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3 px-4 rounded-lg transition">
                    Enviar enlace de recuperación
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                <a href="{{ route('login') }}" class="text-[#2D6A4F] font-semibold hover:underline">Volver al inicio de sesión</a>
            </p>
        </div>
    </div>
</body>
</html>
