@php
    $analyticsEnabled = \App\Models\Configuration::get('analytics_enabled', '0') === '1';
    $analyticsGtmId   = $analyticsEnabled ? \App\Models\Configuration::get('analytics_gtm_id', '') : '';
    $analyticsGa4Id   = $analyticsEnabled ? \App\Models\Configuration::get('analytics_ga4_id', '') : '';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Conoce Tandil')</title>

    @if ($analyticsGtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $analyticsGtmId }}');</script>
    @elseif ($analyticsGa4Id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsGa4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $analyticsGa4Id }}', { send_page_view: true });
    </script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>*, *::before, *::after { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php $rcSiteKey = \App\Models\Configuration::get('recaptcha_site_key'); @endphp
    @if($rcSiteKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $rcSiteKey }}" async defer></script>
    @endif
</head>
<body class="bg-[#FAFAF8] text-[#111827] min-h-screen flex flex-col">

    @if ($analyticsGtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $analyticsGtmId }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @php $navItems = \App\Models\NavItem::ordered()->visible()->get(); @endphp

    {{-- ═══════════════ NAVBAR ═══════════════ --}}
    <nav class="bg-[#0F1A14]/90 backdrop-blur-md border-b border-white/[0.06] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">

                {{-- Logo --}}
                <a href="{{ route('inicio') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-[#2D6A4F] flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-white text-[0.9375rem] tracking-tight">Conoce Tandil</span>
                </a>

                {{-- Desktop nav links --}}
                <div class="hidden md:flex items-center gap-0.5">
                    @foreach ($navItems as $navItem)
                        <a href="{{ route($navItem->route_name) }}"
                           class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs($navItem->route_name) ? 'text-white bg-white/10' : 'text-white/60 hover:text-white hover:bg-white/8' }}">
                            {{ $navItem->label }}
                        </a>
                    @endforeach
                    <a href="{{ route('premium.upsell') }}"
                       class="ml-1 px-3.5 py-2 text-sm font-semibold flex items-center gap-1.5 rounded-lg transition-colors {{ request()->is('premium*') ? 'text-amber-300 bg-amber-400/15' : 'text-amber-400/80 hover:text-amber-300 hover:bg-amber-400/10' }}">
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Premium
                    </a>
                </div>

                {{-- Desktop right side --}}
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        <div class="relative" id="user-menu-wrapper">
                            <button id="user-menu-btn"
                                class="flex items-center gap-2 text-sm font-medium text-white/70 hover:text-white px-3 py-2 rounded-lg hover:bg-white/8 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-[#2D6A4F] flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="user-menu-dropdown"
                                class="absolute right-0 top-full mt-2 w-52 bg-[#1A2820] rounded-2xl shadow-2xl border border-white/10 py-2 hidden z-50">
                                @if (auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/8 transition-colors">Panel Admin</a>
                                @elseif (auth()->user()->isPremium())
                                    <a href="{{ route('premium.hub') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-amber-300/80 hover:text-amber-300 hover:bg-amber-400/10 transition-colors">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Mi cuenta Premium
                                    </a>
                                @endif
                                <div class="my-1 border-t border-white/8"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400/80 hover:text-red-400 hover:bg-red-400/10 transition-colors">Cerrar sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white/60 hover:text-white transition-colors px-3 py-2 rounded-lg hover:bg-white/8">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-[#2D6A4F] hover:bg-[#52B788] text-white px-4 py-2 rounded-xl transition-colors shadow-sm">
                            Registrarse
                        </a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-xl text-white/60 hover:text-white hover:bg-white/8 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#0F1A14] border-t border-white/[0.06]">
            <div class="px-4 py-3 space-y-0.5">
                @foreach ($navItems as $navItem)
                    <a href="{{ route($navItem->route_name) }}"
                       class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($navItem->route_name) ? 'text-white bg-white/10' : 'text-white/60 hover:text-white hover:bg-white/8' }}">
                        {{ $navItem->label }}
                    </a>
                @endforeach
                <a href="{{ route('premium.upsell') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-amber-400/80 hover:text-amber-300 hover:bg-amber-400/10 transition-colors">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Premium
                </a>
                <div class="pt-3 mt-2 border-t border-white/[0.06] space-y-0.5">
                    @auth
                        <p class="px-3 py-1 text-xs text-white/30 font-medium">{{ auth()->user()->name }}</p>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm text-white/60 hover:text-white hover:bg-white/8 transition-colors">Panel Admin</a>
                        @elseif (auth()->user()->isPremium())
                            <a href="{{ route('premium.hub') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm text-amber-400/80 hover:text-amber-300 hover:bg-amber-400/10 transition-colors">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Mi cuenta Premium
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm text-red-400/80 hover:text-red-400 hover:bg-red-400/10 transition-colors">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm text-white/60 hover:text-white hover:bg-white/8 transition-colors">Ingresar</a>
                        <a href="{{ route('register') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold text-[#52B788] hover:bg-[#2D6A4F]/20 transition-colors">Registrarse gratis</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    <footer class="bg-[#0F1A14]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-white/[0.07]">
                <div>
                    <a href="{{ route('inicio') }}" class="inline-flex items-center gap-2.5 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-[#2D6A4F] flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-[0.9375rem] tracking-tight">Conoce Tandil</span>
                    </a>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs">
                        Descubrí todo lo que Tandil tiene para ofrecerte. Turismo, guías y experiencias únicas en las sierras bonaerenses.
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-white/40 uppercase tracking-widest mb-5">Explorar</h3>
                    <ul class="space-y-3">
                        @foreach ($navItems as $navItem)
                            <li><a href="{{ route($navItem->route_name) }}" class="text-gray-500 hover:text-white text-sm transition-colors duration-150">{{ $navItem->label }}</a></li>
                        @endforeach
                        <li><a href="{{ route('premium.upsell') }}" class="text-amber-500/70 hover:text-amber-400 text-sm transition-colors duration-150">Premium ✦</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-white/40 uppercase tracking-widest mb-5">Seguinos</h3>
                    <div class="flex gap-2.5">
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-[#2D6A4F] flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-[#2D6A4F] flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-[#2D6A4F] flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200" aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-gray-700 text-xs">&copy; {{ date('Y') }} Conoce Tandil. Todos los derechos reservados.</p>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-white text-xs transition-colors duration-150">Área administrativa</a>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        const userBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-menu-dropdown');
        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function (e) { e.stopPropagation(); userDropdown.classList.toggle('hidden'); });
            document.addEventListener('click', function () { userDropdown.classList.add('hidden'); });
        }
    </script>

    @if ($analyticsGtmId || $analyticsGa4Id)
    <script>
    (function () {
        function sendEvent(name, params) {
            if (typeof gtag === 'function') { gtag('event', name, params || {}); }
            else if (typeof dataLayer !== 'undefined') { dataLayer.push(Object.assign({ event: name }, params || {})); }
        }
        var path = window.location.pathname;
        if (path.match(/^\/lugares\/.+/)) { sendEvent('view_item', { item_name: document.title, item_category: 'Lugar', item_id: path }); }
        else if (path === '/lugares') { sendEvent('view_item_list', { item_list_name: 'Lugares' }); }
        else if (path === '/guias') { sendEvent('view_item_list', { item_list_name: 'Guías' }); }
        if (path === '/premium') { sendEvent('view_promotion', { promotion_name: 'Premium Upsell', creative_slot: 'upsell_page' }); }
        if (path === '/premium/planes') { sendEvent('view_item_list', { item_list_name: 'Planes Premium' }); }
        if (path.match(/^\/premium\/checkout\//)) { sendEvent('begin_checkout', { currency: 'ARS', value: 0 }); }
        if (path.match(/^\/premium\/pedido\//)) { var orderId = path.split('/').filter(Boolean).pop(); sendEvent('purchase', { transaction_id: orderId, currency: 'ARS', value: 0 }); }
        if (path === '/contacto') { document.addEventListener('submit', function (e) { if (e.target && e.target.tagName === 'FORM') { sendEvent('generate_lead', { form_destination: path }); } }); }
    })();
    </script>
    @endif
</body>
</html>
