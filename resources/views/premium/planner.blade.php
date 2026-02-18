@extends('layouts.app')
@section('title', 'Planificador Premium — Conoce Tandil')

@section('content')

<section class="min-h-screen bg-gray-50 py-12">
<div class="max-w-xl mx-auto px-4 sm:px-6">

    {{-- Header --}}
    <div class="text-center mb-10">
        <span class="inline-flex items-center gap-1.5 bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-bold px-3 py-1.5 rounded-full mb-4">
            ✦ PREMIUM
        </span>
        <h1 class="text-3xl font-bold text-[#1A1A1A] mb-2">Planificá tu visita</h1>
        <p class="text-gray-500">Respondé estas preguntas y te armamos el itinerario ideal para vos.</p>
    </div>

    <form method="GET" action="{{ route('premium.resultados') }}"
        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

        {{-- Days --}}
        <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿Cuántos días vas a estar?</label>
            <div class="flex flex-wrap gap-3">
                @foreach(range(1,7) as $d)
                <label class="cursor-pointer">
                    <input type="radio" name="days" value="{{ $d }}" class="sr-only peer"
                        {{ old('days', request('days')) == $d ? 'checked' : '' }}
                        {{ $d === 2 ? 'required' : '' }}>
                    <span class="flex items-center justify-center w-12 h-12 rounded-xl border-2 border-gray-200 text-sm font-bold text-gray-500 peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F] peer-checked:text-white transition">
                        {{ $d }}
                    </span>
                </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-2">día{{ request('days') == 1 ? '' : 's' }}</p>
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿Qué tipo de experiencia preferís?</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach([
                    'nature'      => ['🌿', 'Naturaleza'],
                    'gastronomy'  => ['🧀', 'Gastronomía'],
                    'adventure'   => ['🧗', 'Aventura'],
                    'relax'       => ['🛁', 'Relax'],
                    'mixed'       => ['✨', 'Mixto'],
                ] as $val => [$icon, $label])
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="{{ $val }}" class="sr-only peer"
                        {{ old('type', request('type', 'mixed')) === $val ? 'checked' : '' }}>
                    <span class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 border-gray-200 text-center peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/5 transition">
                        <span class="text-2xl">{{ $icon }}</span>
                        <span class="text-xs font-semibold text-gray-700 peer-checked:text-[#2D6A4F]">{{ $label }}</span>
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Season --}}
        <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-3">¿En qué temporada viajás?</label>
            <div class="grid grid-cols-3 gap-3">
                @foreach(['summer'=>['☀️','Verano'],'winter'=>['❄️','Invierno'],'all'=>['🍃','No sé']] as $val=>[$icon,$label])
                <label class="cursor-pointer">
                    <input type="radio" name="season" value="{{ $val }}" class="sr-only peer"
                        {{ old('season', request('season', 'all')) === $val ? 'checked' : '' }}>
                    <span class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 border-gray-200 text-center peer-checked:border-[#2D6A4F] peer-checked:bg-[#2D6A4F]/5 transition">
                        <span class="text-2xl">{{ $icon }}</span>
                        <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                    </span>
                </label>
                @endforeach
            </div>
        </div>

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
</section>

@endsection
