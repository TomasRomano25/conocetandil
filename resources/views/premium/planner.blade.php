@extends('layouts.app')
@section('title', 'Planificador Premium — Conoce Tandil')

@section('content')

<section class="min-h-screen bg-gradient-to-br from-gray-50 to-[#2D6A4F]/5 py-12">
<div class="max-w-5xl mx-auto px-4 sm:px-6">

    {{-- Header --}}
    <div class="text-center mb-10">
        <span class="inline-flex items-center gap-1.5 bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-bold px-3 py-1.5 rounded-full mb-4 tracking-wide">
            ✦ PREMIUM
        </span>
        <h1 class="text-3xl font-bold text-[#1A1A1A] mb-2">Planificá tu visita</h1>
        <p class="text-gray-500">Respondé estas preguntas y te armamos el itinerario ideal para vos.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ══ FORM CARD ══ --}}
        <div class="flex-1 min-w-0">
            <form method="GET" action="{{ route('premium.resultados') }}"
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

                {{-- Days --}}
                @if(count($days) > 0)
                <div>
                    <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿Cuántos días vas a estar?</label>
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
                    <p class="text-xs text-gray-400 mt-2">días disponibles</p>
                </div>
                @else
                <input type="hidden" name="days" value="{{ $defaultDays }}">
                @endif

                {{-- Type --}}
                @if(count($types) > 0)
                <div>
                    <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿Qué tipo de experiencia preferís?</label>
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
                    <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿En qué temporada viajás?</label>
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
                    <label class="block text-sm font-bold text-[#1A1A1A] mb-3">Opciones adicionales</label>
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

                <button type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-4 rounded-xl transition text-base">
                    Ver mis itinerarios →
                </button>
            </form>
        </div>

        {{-- ══ SOCIAL PROOF SIDEBAR ══ --}}
        <aside class="w-full lg:w-72 flex-shrink-0 space-y-4">

            {{-- Trust stats --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-xs font-bold text-[#2D6A4F] uppercase tracking-widest mb-4">Por qué funciona</p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/10 flex items-center justify-center flex-shrink-0 text-lg">✦</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">+500</p>
                            <p class="text-xs text-gray-500 mt-0.5">itinerarios generados este año</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 text-lg">★</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">4.9 / 5</p>
                            <p class="text-xs text-gray-500 mt-0.5">valoración promedio de viajeros</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-lg">🗺</div>
                        <div>
                            <p class="text-xl font-extrabold text-[#1A1A1A] leading-none">100%</p>
                            <p class="text-xs text-gray-500 mt-0.5">lugares verificados en Tandil</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Testimonial --}}
            <div class="bg-[#2D6A4F] rounded-2xl p-6 text-white">
                <div class="flex gap-0.5 mb-3 text-amber-300 text-sm">★★★★★</div>
                <p class="text-sm leading-relaxed text-white/90 italic mb-4">
                    "El planificador nos armó el fin de semana perfecto. Ni una hora perdida, todo a medida de lo que queríamos."
                </p>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">MG</div>
                    <div>
                        <p class="text-xs font-bold text-white">María G.</p>
                        <p class="text-xs text-white/60">Visitó Tandil en enero</p>
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
