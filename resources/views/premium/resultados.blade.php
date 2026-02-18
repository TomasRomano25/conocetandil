@extends('layouts.app')
@section('title', 'Resultados — Premium')

@section('content')

<section class="min-h-screen bg-gray-50 py-10">
<div class="max-w-3xl mx-auto px-4 sm:px-6">

    {{-- Back + Header --}}
    <div class="mb-8">
        <a href="{{ route('premium.planner') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Cambiar filtros
        </a>
        <h1 class="text-2xl font-bold text-[#1A1A1A]">Itinerarios para vos</h1>

        {{-- Active filters summary --}}
        <div class="flex flex-wrap gap-2 mt-3">
            <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">
                {{ $days }} {{ $days == 1 ? 'día' : 'días' }}
            </span>
            <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">
                {{ ['nature'=>'🌿 Naturaleza','gastronomy'=>'🧀 Gastronomía','adventure'=>'🧗 Aventura','relax'=>'🛁 Relax','mixed'=>'✨ Mixto'][$type] ?? $type }}
            </span>
            <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">
                {{ ['summer'=>'☀️ Verano','winter'=>'❄️ Invierno','all'=>'🍃 Cualquier época'][$season] ?? $season }}
            </span>
            @if ($kids) <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">👨‍👧 Con niños</span> @endif
            @if ($car)  <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">🚗 Con auto</span> @endif
        </div>
    </div>

    @if ($itineraries->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <p class="text-3xl mb-4">🗺️</p>
            <p class="font-bold text-[#1A1A1A] mb-2">No encontramos itinerarios exactos</p>
            <p class="text-gray-500 text-sm mb-6">Probá ajustando los días o el tipo de experiencia.</p>
            <a href="{{ route('premium.planner') }}"
                class="inline-flex items-center gap-2 bg-[#2D6A4F] text-white font-semibold px-6 py-2.5 rounded-xl text-sm hover:bg-[#1A1A1A] transition">
                Volver al planificador
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($itineraries as $itin)
            <a href="{{ route('premium.show', $itin->slug) }}"
                class="block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-[#2D6A4F]/30 transition group">
                <div class="flex">
                    {{-- Cover image --}}
                    @if ($itin->cover_image)
                    <div class="w-32 sm:w-44 flex-shrink-0 overflow-hidden">
                        <img src="{{ asset('storage/' . $itin->cover_image) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="w-32 sm:w-44 flex-shrink-0 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center">
                        <span class="text-4xl">🗺️</span>
                    </div>
                    @endif

                    {{-- Content --}}
                    <div class="flex-1 p-5 min-w-0">
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded-full">✦ Premium</span>
                            <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full">
                                {{ $itin->days_min === $itin->days_max ? $itin->days_min . ' día(s)' : "{$itin->days_min}–{$itin->days_max} días" }}
                            </span>
                            @if ($itin->requires_car)
                                <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2.5 py-0.5 rounded-full">🚗 Auto</span>
                            @endif
                        </div>
                        <h2 class="font-bold text-[#1A1A1A] text-base mb-1 group-hover:text-[#2D6A4F] transition">{{ $itin->title }}</h2>
                        @if ($itin->description)
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $itin->description }}</p>
                        @endif
                        <div class="flex items-center gap-1 mt-3 text-[#2D6A4F] font-semibold text-sm">
                            Ver itinerario
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif

</div>
</section>

@endsection
