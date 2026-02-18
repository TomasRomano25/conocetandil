@extends('layouts.app')
@section('title', 'Conoce Tandil Premium')

@section('content')

{{-- Hero --}}
<section class="relative bg-[#1A1A1A] text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 text-xs font-bold px-4 py-1.5 rounded-full mb-6 border border-amber-500/30">
            ✦ PREMIUM
        </span>
        <h1 class="text-4xl md:text-5xl font-bold mb-5 leading-tight">
            Dejá de preguntarte qué hacer.<br>
            <span class="text-[#52B788]">Tomá mejores decisiones.</span>
        </h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto mb-8">
            El módulo Premium no te da más lugares — te dice cuáles visitar primero, en qué orden, y por qué. Diseñado para que aproveches cada hora en Tandil.
        </p>
        @auth
            <p class="text-amber-300 text-sm">Tu cuenta aún no tiene acceso Premium. Contactá al administrador para activarlo.</p>
        @else
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 bg-[#52B788] hover:bg-[#2D6A4F] text-white font-bold py-3.5 px-8 rounded-xl transition text-base">
                Iniciar sesión para acceder
            </a>
        @endauth
    </div>
</section>

{{-- Free vs Premium comparison --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-center text-[#1A1A1A] mb-10">¿Qué cambia con Premium?</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Free --}}
            <div class="rounded-2xl border-2 border-gray-200 p-7">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Gratis</p>
                <ul class="space-y-3">
                    @foreach([
                        '¿Qué es este lugar?',
                        'Fotos y descripción',
                        'Dirección y horarios',
                        'Buscar por categoría',
                    ] as $item)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Premium --}}
            <div class="rounded-2xl border-2 border-[#2D6A4F] p-7 relative shadow-lg">
                <span class="absolute -top-3 left-6 bg-[#2D6A4F] text-white text-xs font-bold px-3 py-1 rounded-full">✦ PREMIUM</span>
                <p class="text-xs font-bold text-[#2D6A4F] uppercase tracking-widest mb-4">Premium</p>
                <ul class="space-y-3">
                    @foreach([
                        '¿Qué debería hacer primero?',
                        'Itinerarios optimizados por días',
                        'Orden inteligente: mañana / tarde / noche',
                        'Por qué cada actividad va en ese lugar',
                        'Alertas: errores comunes, clima, consejos',
                        'Filtros: días, tipo, temporada, niños, auto',
                        '"Saltá esto si…" y "Vale la pena porque…"',
                        'Links directos a Google Maps',
                    ] as $item)
                    <li class="flex items-center gap-3 text-sm text-[#1A1A1A] font-medium">
                        <svg class="w-4 h-4 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-[#1A1A1A] mb-3">Cómo funciona</h2>
        <p class="text-gray-500 mb-10">Tres pasos para tener tu plan perfecto.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            @foreach([
                ['1', '📋', 'Respondé 5 preguntas', 'Días disponibles, tipo de viaje, si viajás con niños, si tenés auto.'],
                ['2', '🧠', 'Recibís itinerarios curados', 'Planes estructurados y optimizados según tu contexto específico.'],
                ['3', '🗺️', 'Disfrutá sin dudar', 'Cada actividad con su orden, motivo y consejo editorial incluido.'],
            ] as [$num, $icon, $title, $desc])
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="w-10 h-10 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-xl mb-4">{{ $icon }}</div>
                <p class="text-xs font-bold text-gray-400 mb-1">Paso {{ $num }}</p>
                <p class="font-bold text-[#1A1A1A] mb-2">{{ $title }}</p>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
