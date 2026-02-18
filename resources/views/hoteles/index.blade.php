@extends('layouts.app')
@section('title', 'Hoteles en Tandil')

@section('content')

{{-- Hero --}}
<section class="relative bg-[#1A1A1A] text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Hoteles en Tandil</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto mb-6">
            Encontrá el alojamiento perfecto para tu estadía. Todos los hoteles verificados por Conoce Tandil.
        </p>
        <a href="{{ route('hoteles.propietarios') }}"
            class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold py-2.5 px-6 rounded-xl transition border border-white/20">
            ¿Sos propietario? Registrá tu hotel
        </a>
    </div>
</section>

{{-- Filters --}}
<section class="bg-white shadow-sm border-b sticky top-0 z-20">
    <form method="GET" action="{{ route('hoteles.index') }}" id="filter-form">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-3">

            {{-- Type tabs --}}
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button type="submit" name="type" value=""
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition
                        {{ $selectedType === '' ? 'bg-[#2D6A4F] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Todos
                </button>
                @foreach ($hotelTypes as $type)
                <button type="submit" name="type" value="{{ $type }}"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition
                        {{ $selectedType === $type ? 'bg-[#2D6A4F] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    {{ $type }}
                </button>
                @endforeach
            </div>

            {{-- Amenity checkboxes --}}
            <div class="flex flex-wrap gap-x-4 gap-y-2">
                @foreach ($amenityOptions as $amenity)
                <label class="flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                        {{ in_array($amenity, $selectedAmenities) ? 'checked' : '' }}
                        onchange="document.getElementById('filter-form').submit()"
                        class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                    {{ $amenity }}
                </label>
                @endforeach

                @if ($selectedType !== '' || ! empty($selectedAmenities))
                <a href="{{ route('hoteles.index') }}"
                    class="text-xs text-gray-400 hover:text-red-500 font-semibold flex items-center gap-1 ml-2 transition">
                    ✕ Limpiar filtros
                </a>
                @endif
            </div>

            {{-- Hidden type field so amenity checkboxes keep the type filter --}}
            @if ($selectedType !== '')
            <input type="hidden" name="type" value="{{ $selectedType }}">
            @endif

        </div>
    </form>
</section>

{{-- Content --}}
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        @php $hasAny = $featured->isNotEmpty() || $standard->isNotEmpty() || $basic->isNotEmpty(); @endphp

        @if (! $hasAny)
            <div class="text-center py-20">
                <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-gray-500 font-medium mb-1">No hay hoteles con esos filtros</p>
                <p class="text-gray-400 text-sm mb-5">Probá con otros criterios.</p>
                <a href="{{ route('hoteles.index') }}" class="text-[#2D6A4F] hover:underline text-sm font-semibold">Limpiar filtros</a>
            </div>
        @endif

        {{-- Destacados (Diamante tier) --}}
        @if ($featured->isNotEmpty())
        <section>
            <h2 class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-6 flex items-center gap-2">
                <span class="text-amber-500">✦</span> Destacados
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featured as $hotel)
                    @include('hoteles._card', compact('hotel'))
                @endforeach
            </div>
        </section>
        @endif

        {{-- Estándar tier --}}
        @if ($standard->isNotEmpty())
        <section>
            @if ($featured->isNotEmpty())
            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-6">Alojamientos</h2>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($standard as $hotel)
                    @include('hoteles._card', compact('hotel'))
                @endforeach
            </div>
        </section>
        @endif

        {{-- Básico tier --}}
        @if ($basic->isNotEmpty())
        <section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($basic as $hotel)
                    @include('hoteles._card', compact('hotel'))
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

@endsection
