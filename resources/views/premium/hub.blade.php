@extends('layouts.app')
@section('title', 'Mi cuenta Premium — Conoce Tandil')

@section('content')

<section class="min-h-screen bg-gray-50 py-12">
<div class="max-w-3xl mx-auto px-4 sm:px-6">

    {{-- Welcome header --}}
    <div class="bg-[#1A1A1A] text-white rounded-2xl overflow-hidden mb-8 relative">
        <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/70 to-[#1A1A1A]"></div>
        <div class="relative p-8 sm:p-10">
            <span class="inline-flex items-center gap-1.5 bg-amber-500/20 text-amber-300 text-xs font-bold px-3 py-1.5 rounded-full border border-amber-500/30 mb-4">
                ✦ PREMIUM
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold mb-1">Bienvenido, {{ auth()->user()->name }}.</h1>
            <p class="text-white/70">Tu membresía Premium está activa.</p>

            {{-- Expiry --}}
            @if (auth()->user()->premium_expires_at)
            <div class="mt-4 inline-flex items-center gap-2 bg-white/10 rounded-xl px-4 py-2 text-sm text-white/80">
                <svg class="w-4 h-4 text-[#52B788]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Acceso hasta el
                <strong class="text-white">{{ auth()->user()->premium_expires_at->format('d/m/Y') }}</strong>
                <span class="text-white/50">({{ auth()->user()->premium_expires_at->diffForHumans() }})</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Main CTA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 text-center">
        <p class="text-4xl mb-4">🗺️</p>
        <h2 class="text-xl font-bold text-[#1A1A1A] mb-2">Planificá tu visita a Tandil</h2>
        <p class="text-gray-500 text-sm mb-6">Respondé 5 preguntas y te armamos el itinerario ideal para vos — optimizado por días, tipo de experiencia y contexto.</p>
        <a href="{{ route('premium.planner') }}"
            class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold px-8 py-4 rounded-xl transition text-base">
            Empezar a planificar
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Quick action cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('premium.planner') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-[#2D6A4F]/30 hover:shadow-md transition group text-center">
            <div class="w-12 h-12 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-2xl mb-3 mx-auto group-hover:bg-[#2D6A4F]/20 transition">
                📋
            </div>
            <p class="font-bold text-[#1A1A1A] text-sm">Planificador</p>
            <p class="text-xs text-gray-400 mt-1">Respondé las preguntas</p>
        </a>

        <a href="{{ route('lugares') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-[#2D6A4F]/30 hover:shadow-md transition group text-center">
            <div class="w-12 h-12 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-2xl mb-3 mx-auto group-hover:bg-[#2D6A4F]/20 transition">
                📍
            </div>
            <p class="font-bold text-[#1A1A1A] text-sm">Explorar lugares</p>
            <p class="text-xs text-gray-400 mt-1">Todos los atractivos</p>
        </a>

        <a href="{{ route('contacto') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:border-[#2D6A4F]/30 hover:shadow-md transition group text-center">
            <div class="w-12 h-12 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-2xl mb-3 mx-auto group-hover:bg-[#2D6A4F]/20 transition">
                ✉️
            </div>
            <p class="font-bold text-[#1A1A1A] text-sm">Contacto</p>
            <p class="text-xs text-gray-400 mt-1">¿Necesitás ayuda?</p>
        </a>
    </div>

    {{-- What you can do --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7">
        <h3 class="font-bold text-[#1A1A1A] mb-4">¿Qué podés hacer con Premium?</h3>
        <ul class="space-y-3">
            @foreach([
                ['🧠', 'Itinerarios curados por días', 'Planes optimizados según tu contexto específico.'],
                ['⏰', 'Orden inteligente por momento del día', 'Mañana, tarde, noche — todo organizado.'],
                ['📌', '"Por qué este orden"', 'La razón editorial detrás de cada actividad.'],
                ['⚠️', 'Alertas y consejos contextuales', 'Clima, errores comunes, tips locales.'],
                ['⭐', '"Vale la pena porque…"', 'Justificación de cada lugar incluido.'],
                ['🗺️', 'Links directos a Google Maps', 'Sin buscar, sin perderte.'],
            ] as [$icon, $title, $desc])
            <li class="flex items-start gap-3">
                <span class="text-xl flex-shrink-0 mt-0.5">{{ $icon }}</span>
                <div>
                    <p class="text-sm font-semibold text-[#1A1A1A]">{{ $title }}</p>
                    <p class="text-xs text-gray-400">{{ $desc }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

</div>
</section>

@endsection
