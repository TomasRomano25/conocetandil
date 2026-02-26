@extends('layouts.app')
@section('title', 'Mi cuenta — Conoce Tandil')

@section('content')

{{-- ══ HERO HEADER ══ --}}
<div class="bg-[#1A1A1A] relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/50 via-[#1A1A1A] to-[#1A1A1A]"></div>
    <div class="absolute -right-32 -top-32 w-[500px] h-[500px] rounded-full bg-[#2D6A4F]/10 blur-3xl pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 bg-amber-400/15 text-amber-300 text-xs font-bold px-3 py-1.5 rounded-full border border-amber-400/20 mb-4 tracking-widest">
                    ✦ PREMIUM ACTIVO
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Hola, {{ auth()->user()->name }}.
                </h1>
                <p class="text-white/50 mt-1 text-sm">Bienvenido a tu espacio Premium.</p>
            </div>
            @if(auth()->user()->premium_expires_at)
            <div class="flex items-center gap-2 bg-white/8 border border-white/10 rounded-xl px-4 py-3 text-sm text-white/70 flex-shrink-0">
                <svg class="w-4 h-4 text-[#52B788] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Acceso hasta el <strong class="text-white font-semibold">{{ auth()->user()->premium_expires_at->format('d/m/Y') }}</strong></span>
                <span class="text-white/30 hidden sm:inline">·</span>
                <span class="text-white/40 text-xs hidden sm:inline">{{ auth()->user()->premium_expires_at->diffForHumans() }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══ MAIN CONTENT ══ --}}
<div class="bg-gray-50 min-h-screen">
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ══ LEFT / MAIN COLUMN ══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- PRIMARY CTA — Planner --}}
            <div class="relative bg-[#1A1A1A] rounded-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/60 to-transparent"></div>
                <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-[#52B788]/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative p-7 sm:p-8 flex flex-col sm:flex-row sm:items-center gap-6">
                    <div class="flex-1">
                        <p class="text-[#52B788] text-xs font-bold uppercase tracking-widest mb-2">Tu próxima aventura</p>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-2">
                            Armá tu itinerario<br>personalizado
                        </h2>
                        <p class="text-white/50 text-sm leading-relaxed">Respondé algunas preguntas y te generamos un plan día por día optimizado para vos.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('premium.planner') }}"
                            class="inline-flex items-center gap-2.5 bg-[#2D6A4F] hover:bg-[#52B788] active:scale-95 text-white font-extrabold px-7 py-4 rounded-xl transition-all duration-200 text-base shadow-lg shadow-[#2D6A4F]/30 whitespace-nowrap">
                            Ir al Planificador
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- QUICK ACCESS GRID --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Accesos rápidos</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach([
                        [route('premium.planner'),  '🗺️', 'Planificador',   'Creá tu itinerario'],
                        [route('lugares'),           '📍', 'Lugares',        'Explorá Tandil'],
                        [route('guias'),             '📖', 'Guías',          'Tips y recorridos'],
                        [route('contacto'),          '✉️', 'Contacto',       '¿Necesitás ayuda?'],
                    ] as [$href, $icon, $label, $sub])
                    <a href="{{ $href }}"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-[#2D6A4F]/40 hover:shadow-md transition-all group flex flex-col items-center text-center">
                        <div class="w-11 h-11 bg-gray-50 group-hover:bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-2xl mb-3 transition-colors">{{ $icon }}</div>
                        <p class="text-sm font-bold text-[#1A1A1A]">{{ $label }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 leading-tight">{{ $sub }}</p>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- PREMIUM BENEFITS --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7">
                <div class="flex items-center gap-2 mb-5">
                    <span class="text-base">✦</span>
                    <h3 class="font-extrabold text-[#1A1A1A]">Qué incluye tu membresía</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach([
                        ['🧠', 'Itinerarios por días',           'Planes optimizados según tu contexto.'],
                        ['⏰', 'Orden por momento del día',      'Mañana, tarde y noche organizados.'],
                        ['📌', '"Por qué este orden"',           'La razón editorial de cada actividad.'],
                        ['⚠️', 'Alertas y tips locales',         'Clima, errores comunes, consejos.'],
                        ['⭐', '"Vale la pena porque…"',         'Justificación de cada lugar.'],
                        ['🗺️', 'Links directos a Google Maps',   'Sin buscar, sin perderte.'],
                    ] as [$icon, $title, $desc])
                    <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-3.5">
                        <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                        <div>
                            <p class="text-sm font-semibold text-[#1A1A1A]">{{ $title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 leading-snug">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ══ RIGHT / SIDEBAR ══ --}}
        <div class="space-y-5">

            {{-- Membership card --}}
            <div class="bg-[#1A1A1A] rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#2D6A4F]/20 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <p class="text-xs font-bold text-[#52B788] uppercase tracking-widest mb-4">Tu membresía</p>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 flex items-center justify-center text-amber-300 text-lg font-extrabold flex-shrink-0">✦</div>
                    <div>
                        <p class="font-extrabold text-white text-base leading-none">Premium</p>
                        <p class="text-xs text-white/40 mt-0.5">Acceso completo</p>
                    </div>
                </div>
                @if(auth()->user()->premium_expires_at)
                <div class="bg-white/8 rounded-xl px-4 py-3 text-xs text-white/60 space-y-1">
                    <div class="flex justify-between">
                        <span>Vencimiento</span>
                        <span class="text-white font-semibold">{{ auth()->user()->premium_expires_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estado</span>
                        <span class="text-[#52B788] font-semibold">Activa</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tiempo restante</span>
                        <span class="text-white/80">{{ auth()->user()->premium_expires_at->diffForHumans() }}</span>
                    </div>
                </div>
                @else
                <div class="bg-white/8 rounded-xl px-4 py-3 text-xs text-white/60">
                    <div class="flex justify-between">
                        <span>Estado</span>
                        <span class="text-[#52B788] font-semibold">Activa</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Start planning nudge --}}
            <div class="bg-[#2D6A4F]/8 border border-[#2D6A4F]/20 rounded-2xl p-5 text-center">
                <p class="text-2xl mb-2">🧭</p>
                <p class="text-sm font-bold text-[#1A1A1A] mb-1">¿No sabés por dónde empezar?</p>
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">El planificador te hace 4 preguntas y te arma el viaje perfecto.</p>
                <a href="{{ route('premium.planner') }}"
                    class="inline-flex items-center gap-1.5 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all">
                    Empezar ahora →
                </a>
            </div>

            {{-- Recent orders --}}
            @if($recentOrders->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-extrabold text-[#1A1A1A] mb-4">Mis pedidos</h3>
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                    @php $c = ['pending'=>'amber','completed'=>'green','cancelled'=>'red'][$order->status] ?? 'gray'; @endphp
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0 gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#1A1A1A] truncate">{{ $order->plan->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y') }}</p>
                        </div>
                        <span class="text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700 px-2.5 py-1 rounded-full flex-shrink-0">
                            {{ $order->statusLabel() }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
</div>

@endsection
