<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Conoce Tandil')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#1A1A1A] min-h-screen flex flex-col">
    @php
        $navItems = \App\Models\NavItem::ordered()->visible()->get();
    @endphp

    {{-- Navbar --}}
    <nav class="bg-[#2D6A4F] text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('inicio') }}" class="text-xl font-bold tracking-tight">
                    Conoce Tandil
                </a>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    @foreach ($navItems as $navItem)
                        <a href="{{ route($navItem->route_name) }}"
                           class="hover:text-[#52B788] transition {{ request()->routeIs($navItem->route_name) ? 'text-[#52B788] font-semibold' : '' }}">
                            {{ $navItem->label }}
                        </a>
                    @endforeach

                    {{-- Premium nav item --}}
                    <a href="{{ route('premium.upsell') }}"
                       class="flex items-center gap-1.5 text-amber-300 hover:text-amber-200 font-semibold transition {{ request()->is('premium*') ? 'text-amber-200' : '' }}">
                        <span class="text-xs">✦</span>
                        Premium
                    </a>

                    {{-- Auth area --}}
                    @auth
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-btn"
                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-3 py-1.5 rounded-lg transition">
                                <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="user-menu-dropdown"
                                class="absolute right-0 top-full mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1 hidden z-50">
                                @if (auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        Panel Admin
                                    </a>
                                @elseif (auth()->user()->isPremium())
                                    <a href="{{ route('premium.hub') }}"
                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                        Mi cuenta Premium
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-[#52B788] transition text-white/70 text-sm">Iniciar sesión</a>
                    @endauth
                </div>

                {{-- Hamburger button --}}
                <button id="mobile-menu-btn" class="md:hidden focus:outline-none" aria-label="Abrir menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#2D6A4F] border-t border-[#52B788]/30">
            <div class="px-4 py-3 space-y-1">
                @foreach ($navItems as $navItem)
                    <a href="{{ route($navItem->route_name) }}"
                       class="block py-2 hover:text-[#52B788] transition {{ request()->routeIs($navItem->route_name) ? 'text-[#52B788] font-semibold' : '' }}">
                        {{ $navItem->label }}
                    </a>
                @endforeach

                {{-- Premium nav item --}}
                <a href="{{ route('premium.upsell') }}"
                   class="flex items-center gap-1.5 py-2 text-amber-300 font-semibold">
                    <span class="text-xs">✦</span> Premium
                </a>

                <div class="pt-2 border-t border-[#52B788]/20 mt-2">
                    @auth
                        <p class="text-xs text-white/40 pb-1">{{ auth()->user()->name }}</p>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm text-white/80 hover:text-white transition">Panel Admin</a>
                        @elseif (auth()->user()->isPremium())
                            <a href="{{ route('premium.hub') }}" class="block py-2 text-sm text-white/80 hover:text-white transition">Mi cuenta Premium</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block py-2 text-sm text-red-400 hover:text-red-300 transition text-left w-full">
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block py-2 text-sm text-white/70 hover:text-white transition">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="block py-2 text-sm text-white/70 hover:text-white transition">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#1A1A1A] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4 text-[#52B788]">Conoce Tandil</h3>
                    <p class="text-gray-400 text-sm">Descubrí todo lo que Tandil tiene para ofrecerte. Turismo, guías y experiencias únicas en las sierras.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 text-[#52B788]">Enlaces</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        @foreach ($navItems as $navItem)
                            <li>
                                <a href="{{ route($navItem->route_name) }}" class="hover:text-[#52B788] transition">
                                    {{ $navItem->label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 text-[#52B788]">Seguinos</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-[#52B788] transition" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#52B788] transition" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#52B788] transition" aria-label="Twitter">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Conoce Tandil. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // User dropdown
        const userBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-menu-dropdown');
        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', function () {
                userDropdown.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
