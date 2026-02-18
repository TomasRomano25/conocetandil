<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#2D6A4F] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('inicio') }}" class="text-3xl font-bold text-white tracking-tight">Conoce Tandil</a>
            <p class="text-[#52B788] mt-2">Establecer nueva contraseña</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6 text-center">Nueva contraseña</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">Mínimo 8 caracteres.</p>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>

                <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3 px-4 rounded-lg transition">
                    Restablecer contraseña
                </button>
            </form>
        </div>
    </div>
</body>
</html>
