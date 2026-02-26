@extends('layouts.app')
@section('title', 'Planificador Premium — Conoce Tandil')

@section('content')

@php $plannerHero = \App\Models\Configuration::get('planner_hero_image'); @endphp

{{-- Hero con fondo sutil --}}
<div class="relative bg-[#1A1A1A] overflow-hidden">
    {{-- Foto de fondo (si está configurada) --}}
    @if($plannerHero)
    <div class="absolute inset-0">
        <img src="{{ Storage::url($plannerHero) }}" alt=""
            class="w-full h-full object-cover opacity-30">
    </div>
    @endif
    {{-- Textura de fondo (solo cuando no hay foto) --}}
    @if(!$plannerHero)
    <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>
    @endif
    {{-- Gradiente oscurecedor siempre presente --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#1A1A1A]/80 via-[#1A1A1A]/60 to-[#2D6A4F]/40"></div>
    {{-- Glow verde sutil --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#52B788]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-14 text-center">
        <span class="inline-flex items-center gap-1.5 bg-white/10 text-[#52B788] text-xs font-bold px-3 py-1.5 rounded-full mb-5 tracking-widest border border-white/10">
            ✦ PREMIUM
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-3 tracking-tight leading-tight">
            Planificá tu visita<br>
            <span class="text-[#52B788]">a medida.</span>
        </h1>
        <p class="text-white/60 text-base max-w-md mx-auto">
            Respondé estas preguntas y te armamos el itinerario ideal para vos, día por día.
        </p>
    </div>
</div>

<section class="bg-gradient-to-b from-gray-50 to-white py-12">
<div class="max-w-5xl mx-auto px-4 sm:px-6">

    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ══ FORM CARD ══ --}}
        <div class="flex-1 min-w-0">
            <form method="GET" action="{{ route('premium.resultados') }}"
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

                {{-- Days --}}
                @if(count($days) > 0)
                <div>
                    <label class="block text-base font-extrabold text-[#1A1A1A] mb-1">¿Cuántos días vas a estar?</label>
                    <p class="text-xs text-gray-400 mb-3">Seleccioná la duración de tu viaje.</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($days as $d)
                        <label class="cursor-pointer">
                            <input type="radio" name="days" value="{{ $d }}" class="sr-only peer"
                                {{ old('days', request('days')) == $d ? 'checked' : '' }}
                                {{ $loop->first ? 'required' : '' }}>
                            <span class="flex items-center justify-center w-12 h-12 rounded-xl border-2 border-gray-200 text-sm font-bold text-gray-500 peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F] peer-checked:text-white transition">
                                {{ $d }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">días</p>
                </div>
                @else
                <input type="hidden" name="days" value="{{ $defaultDays }}">
                @endif

                {{-- Type --}}
                @if(count($types) > 0)
                <div>
                    <label class="block text-base font-extrabold text-[#1A1A1A] mb-1">¿Qué tipo de experiencia preferís?</label>
                    <p class="text-xs text-gray-400 mb-3">Elegí el estilo que mejor te describe.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($types as $val => [$icon, $label])
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" class="sr-only peer"
                                {{ old('type', request('type', array_key_first($types))) === $val ? 'checked' : '' }}>
                            <span class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 border-gray-200 text-center peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/5 transition">
                                <span class="text-2xl">{{ $icon }}</span>
                                <span class="text-xs font-semibold text-gray-700 peer-checked:text-[#2D6A4F]">{{ $label }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @else
                <input type="hidden" name="type" value="{{ $defaultType }}">
                @endif

                {{-- Season --}}
                @if(count($seasons) > 0)
                <div>
                    <label class="block text-base font-extrabold text-[#1A1A1A] mb-1">¿En qué temporada viajás?</label>
                    <p class="text-xs text-gray-400 mb-3">Los itinerarios se adaptan a la época del año.</p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($seasons as $val => [$icon, $label])
                        <label class="cursor-pointer">
                            <input type="radio" name="season" value="{{ $val }}" class="sr-only peer"
                                {{ old('season', request('season', array_key_first($seasons))) === $val ? 'checked' : '' }}>
                            <span class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 border-gray-200 text-center peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/5 transition">
                                <span class="text-2xl">{{ $icon }}</span>
                                <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @else
                <input type="hidden" name="season" value="{{ $defaultSeason }}">
                @endif

                {{-- Kids & Car --}}
                <div class="space-y-3">
                    <label class="block text-base font-extrabold text-[#1A1A1A] mb-1">Opciones adicionales</label>
                    <p class="text-xs text-gray-400 mb-3">Ajustamos los itinerarios a tu situación.</p>
                    <label class="flex items-center gap-3 cursor-pointer bg-gray-50 rounded-xl px-4 py-3 border-2 border-transparent hover:border-[#2D6A4F]/30 transition">
                        <input type="checkbox" name="kids" value="1" class="rounded border-gray-300 text-[#2D6A4F] w-5 h-5"
                            {{ old('kids', request('kids')) ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-semibold text-[#1A1A1A]">Viajo con niños</p>
                            <p class="text-xs text-gray-400">Solo verás actividades aptas para toda la familia.</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer bg-gray-50 rounded-xl px-4 py-3 border-2 border-transparent hover:border-[#2D6A4F]/30 transition">
                        <input type="checkbox" name="car" value="1" class="rounded border-gray-300 text-[#2D6A4F] w-5 h-5"
                            {{ old('car', request('car')) ? 'checked' : '' }}>
                        <div>
                            <p class="text-sm font-semibold text-[#1A1A1A]">Tengo auto</p>
                            <p class="text-xs text-gray-400">Habilita itinerarios que requieren movilidad propia.</p>
                        </div>
                    </label>
                </div>

                {{-- CTA --}}
                <div class="pt-1">
                    <button type="submit"
                        class="w-full bg-[#2D6A4F] hover:bg-[#1f4f3a] active:scale-[0.98] text-white font-extrabold py-4 rounded-xl transition-all text-base tracking-wide shadow-lg shadow-[#2D6A4F]/20 flex items-center justify-center gap-2">
                        Ver mi plan personalizado
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">Gratis para todos los miembros Premium ✦</p>
                </div>
            </form>
        </div>

        {{-- ══ SOCIAL PROOF SIDEBAR ══ --}}
        <aside class="w-full lg:w-72 flex-shrink-0 space-y-4">

            {{-- Trust stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-xs font-bold text-[#2D6A4F] uppercase tracking-widest mb-4">Por qué funciona</p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/10 flex items-center justify-center flex-shrink-0 text-base">👥</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">+200</p>
                            <p class="text-xs text-gray-500 mt-0.5">viajeros ya planificaron con esto</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 text-base">★</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">4.9 / 5</p>
                            <p class="text-xs text-gray-500 mt-0.5">valoración promedio de viajeros</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/10 flex items-center justify-center flex-shrink-0 text-base">🗺</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">+15</p>
                            <p class="text-xs text-gray-500 mt-0.5">itinerarios optimizados disponibles</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Testimonial --}}
            <div class="bg-[#1A1A1A] rounded-2xl p-6 text-white">
                <div class="flex gap-0.5 mb-3 text-amber-300 text-sm">★★★★★</div>
                <p class="text-sm leading-relaxed text-white/80 italic mb-4">
                    "El planificador nos armó el fin de semana perfecto. Ni una hora perdida, todo a medida de lo que queríamos."
                </p>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#2D6A4F]/60 flex items-center justify-center text-xs font-bold text-white">MG</div>
                    <div>
                        <p class="text-xs font-bold text-white">María G.</p>
                        <p class="text-xs text-white/40">Visitó Tandil en enero</p>
                    </div>
                </div>
            </div>

            {{-- How it works --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-xs font-bold text-[#1A1A1A] uppercase tracking-widest mb-4">¿Cómo funciona?</p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#2D6A4F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <p class="text-xs text-gray-600">Completás el cuestionario con tus preferencias de viaje.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#2D6A4F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <p class="text-xs text-gray-600">El sistema filtra y prioriza los mejores itinerarios para vos.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#2D6A4F] text-white text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        <p class="text-xs text-gray-600">Explorás cada itinerario día por día con mapas y tips exclusivos.</p>
                    </div>
                </div>
            </div>

            {{-- Premium badge --}}
            <div class="rounded-2xl border border-[#2D6A4F]/20 bg-[#2D6A4F]/5 p-5 flex items-start gap-3">
                <div class="text-2xl flex-shrink-0">🔒</div>
                <div>
                    <p class="text-xs font-bold text-[#2D6A4F] mb-1">Beneficio exclusivo Premium</p>
                    <p class="text-xs text-gray-600 leading-relaxed">Solo miembros Premium acceden al Planificador personalizado y a todos los itinerarios detallados.</p>
                </div>
            </div>

        </aside>

    </div>
</div>
</section>

@endsection
