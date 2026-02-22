<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Conoce Tandil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-[#1A1A1A] text-white transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col">
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-700 flex-shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-[#52B788]">Conoce Tandil</a>
            <button id="close-sidebar" class="md:hidden text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto mt-6 px-4 space-y-1 pb-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.lugares.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.lugares.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Lugares
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.usuarios.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Usuarios
            </a>
            {{-- Editar Secciones (collapsible) --}}
            @php $seccionesActive = request()->routeIs('admin.secciones.*') || request()->routeIs('admin.inicio.*'); @endphp
            <div>
                <button onclick="toggleSeccionesMenu()"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition {{ $seccionesActive ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span class="flex-1 text-left">Editar Secciones</span>
                    <svg id="secciones-chevron" class="w-4 h-4 transition-transform shrink-0 {{ $seccionesActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="secciones-submenu" class="{{ $seccionesActive ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-gray-700 space-y-0.5">
                    @foreach ([
                        'inicio'   => 'Inicio',
                        'lugares'  => 'Lugares',
                        'guias'    => 'Guías',
                        'contacto' => 'Contacto',
                        'premium'  => 'Premium',
                    ] as $tab => $label)
                        @php
                            $subActive = request()->routeIs('admin.secciones.*') && request('tab', 'inicio') === $tab;
                        @endphp
                        <a href="{{ route('admin.secciones.index', ['tab' => $tab]) }}"
                            class="flex items-center px-3 py-2 rounded-lg text-sm transition
                                   {{ $subActive ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('admin.nav.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.nav.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Menú de Navegación
            </a>
            {{-- Premium --}}
            <a href="{{ route('admin.itinerarios.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.itinerarios.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                Itinerarios Premium
            </a>

            {{-- Ecommerce --}}
            @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
            <a href="{{ route('admin.pedidos.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.pedidos.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Pedidos
                @if ($pendingOrders > 0)
                    <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $pendingOrders }}</span>
                @endif
            </a>
            <a href="{{ route('admin.planes.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.planes.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Planes Premium
            </a>

            {{-- Mensajes --}}
            @php
                $unread = \App\Models\Message::where('is_read', false)->count();
            @endphp
            <a href="{{ route('admin.mensajes.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.mensajes.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Mensajes
                @if ($unread > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $unread }}</span>
                @endif
            </a>

            {{-- Formularios --}}
            <a href="{{ route('admin.formularios.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.formularios.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Formularios
            </a>

            <a href="{{ route('admin.analytics.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.analytics.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Analytics
            </a>

            {{-- Hoteles (collapsible) --}}
            @php
                $hotelesActive = request()->routeIs('admin.hoteles.*') || request()->routeIs('admin.hotel-planes.*') || request()->routeIs('admin.hotel-pedidos.*') || request()->routeIs('admin.hotel-contactos.*');
                $pendingHotelOrders = \App\Models\HotelOrder::where('status','pending')->count();
                $pendingHoteles = \App\Models\Hotel::where('status','pending')->count();
                $hotelBadge = $pendingHotelOrders + $pendingHoteles;
            @endphp
            <div>
                <button onclick="toggleHotelesMenu()"
                    class="w-full flex items-center px-4 py-3 rounded-lg transition {{ $hotelesActive ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="flex-1 text-left">Hoteles</span>
                    @if ($hotelBadge > 0)
                        <span class="bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center mr-2">{{ $hotelBadge }}</span>
                    @endif
                    <svg id="hoteles-chevron" class="w-4 h-4 transition-transform shrink-0 {{ $hotelesActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="hoteles-submenu" class="{{ $hotelesActive ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-gray-700 space-y-0.5">
                    <a href="{{ route('admin.hoteles.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.hoteles.*') ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        Lista de Hoteles
                        @if ($pendingHoteles > 0)
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingHoteles }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.hotel-planes.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.hotel-planes.*') ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        Planes de Hotel
                    </a>
                    <a href="{{ route('admin.hotel-pedidos.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.hotel-pedidos.*') ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        Pedidos de Hotel
                        @if ($pendingHotelOrders > 0)
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingHotelOrders }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.hoteles.analytics') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.hoteles.analytics') ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        Analíticas
                    </a>
                    <a href="{{ route('admin.hotel-contactos.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.hotel-contactos.*') ? 'text-[#52B788] font-semibold' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        Contactos
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.promociones.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.promociones.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Promociones
            </a>

            <a href="{{ route('admin.configuraciones.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.configuraciones.*') ? 'bg-[#2D6A4F] text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Configuraciones
            </a>

            <div class="pt-4 mt-4 border-t border-gray-700">
                <a href="{{ route('inicio') }}" target="_blank" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ver sitio
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- Overlay for mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    {{-- Main content --}}
    <div class="flex-1 md:ml-64">
        {{-- Top bar --}}
        <header class="bg-white shadow-sm h-16 flex items-center px-6">
            <button id="open-sidebar" class="md:hidden mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-lg font-semibold text-[#1A1A1A]">@yield('header', 'Dashboard')</h1>
            <div class="ml-auto text-sm text-gray-500">
                {{ auth()->user()->name }}
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        function toggleSeccionesMenu() {
            const menu    = document.getElementById('secciones-submenu');
            const chevron = document.getElementById('secciones-chevron');
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        }

        function toggleHotelesMenu() {
            const menu    = document.getElementById('hoteles-submenu');
            const chevron = document.getElementById('hoteles-chevron');
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        }
    </script>
</body>
</html>
