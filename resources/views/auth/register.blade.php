<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $rcKey = \App\Models\Configuration::get('recaptcha_site_key'); @endphp
    @if($rcKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $rcKey }}" async defer></script>
    @endif
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
            <p class="text-gray-400 text-sm mt-1.5 font-normal">Creá tu cuenta gratuita y explorá Tandil</p>
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

            <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-register">

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Nombre completo
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        placeholder="Tu nombre"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="tu@email.com"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Contraseña
                    </label>
                    <input type="password" name="password" id="password" required
                        placeholder="Mínimo 8 caracteres"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Confirmar contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Repetí tu contraseña"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/25 focus:border-[#2D6A4F] transition-all duration-200">
                </div>

                <button type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#245c43] active:bg-[#1e4f3a] text-white font-semibold py-3.5 px-4 rounded-xl text-sm tracking-wide transition-all duration-200 shadow-sm hover:shadow-md mt-2">
                    Crear cuenta
                </button>
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

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 mb-0.5">¿Ya tenés cuenta?</p>
                <a href="{{ route('login') }}"
                    class="inline-block text-sm font-semibold text-[#2D6A4F] hover:underline mt-1 transition-colors duration-200">
                    Iniciar sesión
                </a>
            </div>

        </div>
    </div>

</body>
</html>
