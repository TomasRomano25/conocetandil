@extends('layouts.app')
@section('title', 'Hoteles en Tandil')

@section('content')

{{-- Hero --}}
<section class="relative bg-[#1A1A1A] text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-3">Hoteles en Tandil</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto mb-8">
            Encontrá el alojamiento perfecto para tu estadía.
        </p>

        {{-- Search bar inside hero --}}
        <form method="GET" action="{{ route('hoteles.index') }}" id="search-form" class="max-w-xl mx-auto">
            @if ($selectedType !== '')
                <input type="hidden" name="type" value="{{ $selectedType }}">
            @endif
            @foreach ($selectedAmenities as $a)
                <input type="hidden" name="amenities[]" value="{{ $a }}">
            @endforeach
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $selectedSearch }}"
                    placeholder="Buscá por nombre o dirección..."
                    class="w-full bg-white text-gray-900 pl-11 pr-14 sm:pr-28 py-3.5 rounded-2xl text-sm font-medium shadow-xl focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                {{-- Mobile: icon-only button; desktop: text button --}}
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#2D6A4F] hover:bg-[#52B788] text-white font-semibold rounded-xl transition
                           flex items-center justify-center w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2">
                    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <span class="hidden sm:inline text-sm">Buscar</span>
                </button>
            </div>
        </form>
    </div>
</section>

{{-- Filter bar --}}
<div class="bg-white border-b shadow-sm sticky top-0 z-30" id="filter-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @php
            $amenityQs = collect($selectedAmenities)->map(fn($a) => 'amenities[]=' . rawurlencode($a))->join('&');
            $searchQs  = $selectedSearch !== '' ? 'search=' . rawurlencode($selectedSearch) : '';
            $baseQs    = collect([$searchQs, $amenityQs])->filter()->join('&');
            $amenityCount = count($selectedAmenities);
        @endphp

        {{-- Mobile: pills wrap, then actions row. Desktop: single row. --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-y-2 sm:gap-x-3 py-3">

            {{-- Type pills — wrap on mobile, no horizontal scroll --}}
            <div class="flex flex-wrap gap-2 flex-1">
                <a href="{{ route('hoteles.index') }}{{ $baseQs ? '?' . $baseQs : '' }}"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition
                        {{ $selectedType === '' ? 'bg-[#2D6A4F] text-white border-[#2D6A4F]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2D6A4F] hover:text-[#2D6A4F]' }}">
                    Todos
                </a>
                @foreach ($hotelTypes as $type)
                @php $typeQs = collect([$searchQs, 'type=' . rawurlencode($type), $amenityQs])->filter()->join('&'); @endphp
                <a href="{{ route('hoteles.index') }}?{{ $typeQs }}"
                    class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition
                        {{ $selectedType === $type ? 'bg-[#2D6A4F] text-white border-[#2D6A4F]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2D6A4F] hover:text-[#2D6A4F]' }}">
                    {{ $type }}
                </a>
                @endforeach
            </div>

            {{-- Actions: Servicios + Limpiar — always in a row, below pills on mobile --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

                <button type="button" id="servicios-btn" onclick="toggleServiciosPanel()"
                    class="flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border transition
                        {{ $amenityCount > 0 ? 'bg-[#1A1A1A] text-white border-[#1A1A1A]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Servicios
                    @if ($amenityCount > 0)
                        <span class="bg-white text-[#1A1A1A] text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center leading-none">{{ $amenityCount }}</span>
                    @endif
                    <svg id="servicios-chevron" class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                @if ($selectedType !== '' || $amenityCount > 0 || $selectedSearch !== '')
                <a href="{{ route('hoteles.index') }}"
                    class="text-xs text-gray-400 hover:text-red-500 font-semibold flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpiar
                </a>
                @endif
            </div>

        </div>

        {{-- Active amenity chips --}}
        @if ($amenityCount > 0)
        <div class="flex flex-wrap gap-2 pb-3">
            @foreach ($selectedAmenities as $amenity)
            @php
                $remaining = collect($selectedAmenities)->reject(fn($a) => $a === $amenity)->values();
                $chipQs = collect([
                    $searchQs,
                    $selectedType !== '' ? 'type=' . rawurlencode($selectedType) : '',
                    $remaining->map(fn($a) => 'amenities[]=' . rawurlencode($a))->join('&'),
                ])->filter()->join('&');
            @endphp
            <a href="{{ route('hoteles.index') }}{{ $chipQs ? '?' . $chipQs : '' }}"
                class="inline-flex items-center gap-1.5 bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-red-50 hover:text-red-500 transition group">
                {{ $amenity }}
                <svg class="w-3 h-3 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- Servicios dropdown panel --}}
<div id="servicios-panel" class="hidden bg-white border-b shadow-lg z-20 relative">
    <form method="GET" action="{{ route('hoteles.index') }}" id="amenity-form">
        @if ($selectedType !== '')
            <input type="hidden" name="type" value="{{ $selectedType }}">
        @endif
        @if ($selectedSearch !== '')
            <input type="hidden" name="search" value="{{ $selectedSearch }}">
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-bold text-gray-700">Filtrar por servicios</p>
                <button type="button" onclick="toggleServiciosPanel()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-5">
                @php
                $amenityIcons = [
                    'WiFi'               => 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0',
                    'Estacionamiento'    => 'M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m4 0v9a2 2 0 01-2 2H9a2 2 0 01-2-2v-1',
                    'Desayuno incluido'  => 'M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zm4-7v3M10 1v3M14 1v3',
                    'Pileta'             => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
                    'Spa'                => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                    'Pet Friendly'       => 'M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 1.97L7 12v8m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5',
                    'Parrilla'           => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z',
                    'Aire acondicionado' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                ];
                @endphp
                @foreach ($amenityOptions as $amenity)
                @php $checked = in_array($amenity, $selectedAmenities); @endphp
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition
                    {{ $checked ? 'border-[#2D6A4F] bg-[#2D6A4F]/5' : 'border-gray-100 bg-gray-50 hover:border-gray-200' }}">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                        {{ $checked ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-4 h-4 flex-shrink-0 {{ $checked ? 'text-[#2D6A4F]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $amenityIcons[$amenity] ?? 'M5 13l4 4L19 7' }}"/>
                        </svg>
                        <span class="text-sm font-medium {{ $checked ? 'text-[#2D6A4F]' : 'text-gray-700' }} truncate">{{ $amenity }}</span>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <a href="{{ route('hoteles.index', array_filter(['type' => $selectedType, 'search' => $selectedSearch])) }}"
                    class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                    Limpiar servicios
                </a>
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#52B788] text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition">
                    Aplicar filtros
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Results --}}
<div class="bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Results count / active context --}}
        @php $hasAny = $featured->isNotEmpty() || $standard->isNotEmpty() || $basic->isNotEmpty(); @endphp
        @if ($selectedType !== '' || count($selectedAmenities) > 0 || $selectedSearch !== '')
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">
                @if ($hasAny)
                    <span class="font-semibold text-[#1A1A1A]">{{ $totalResults }}</span> {{ $totalResults === 1 ? 'hotel encontrado' : 'hoteles encontrados' }}
                @else
                    Sin resultados
                @endif
                @if ($selectedSearch !== '')
                    para "<span class="font-semibold text-[#1A1A1A]">{{ $selectedSearch }}</span>"
                @endif
            </p>
            <a href="{{ route('hoteles.index') }}" class="text-xs text-gray-400 hover:text-[#2D6A4F] font-semibold transition">
                Ver todos
            </a>
        </div>
        @endif

        @if (! $hasAny)
            <div class="text-center py-24">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-gray-700 font-semibold text-lg mb-1">No encontramos hoteles</p>
                <p class="text-gray-400 text-sm mb-6">Probá con otros filtros o buscá otro término.</p>
                <a href="{{ route('hoteles.index') }}"
                    class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#52B788] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">
                    Limpiar filtros
                </a>
            </div>
        @endif

        {{-- Destacados (Diamante tier) --}}
        @if ($featured->isNotEmpty())
        <section>
            <div class="flex items-center gap-3 mb-6">
                <span class="text-amber-400 text-lg">✦</span>
                <h2 class="text-xs font-bold uppercase tracking-widest text-amber-600">Destacados</h2>
                <div class="flex-1 h-px bg-amber-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400">Alojamientos</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($standard as $hotel)
                    @include('hoteles._card', compact('hotel'))
                @endforeach
            </div>
        </section>
        @endif

        {{-- Básico tier --}}
        @if ($basic->isNotEmpty())
        <section>
            @if ($featured->isNotEmpty() || $standard->isNotEmpty())
            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($basic as $hotel)
                    @include('hoteles._card', compact('hotel'))
                @endforeach
            </div>
        </section>
        @endif

        {{-- Propietario CTA --}}
        <div class="border border-dashed border-gray-300 rounded-2xl p-8 text-center bg-white">
            <p class="text-gray-500 text-sm mb-2">¿Tenés un alojamiento en Tandil?</p>
            <a href="{{ route('hoteles.propietarios') }}"
                class="inline-flex items-center gap-2 text-[#2D6A4F] hover:text-[#52B788] font-semibold text-sm transition">
                Registrá tu hotel en Conoce Tandil
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</div>

<script>
function toggleServiciosPanel() {
    const panel  = document.getElementById('servicios-panel');
    const btn    = document.getElementById('servicios-btn');
    const chev   = document.getElementById('servicios-chevron');
    const isOpen = !panel.classList.contains('hidden');

    if (isOpen) {
        panel.classList.add('hidden');
        chev.style.transform = '';
    } else {
        panel.classList.remove('hidden');
        chev.style.transform = 'rotate(180deg)';
        // Scroll panel into view on mobile
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// Close panel when clicking outside
document.addEventListener('click', function(e) {
    const panel = document.getElementById('servicios-panel');
    const btn   = document.getElementById('servicios-btn');
    if (!panel.classList.contains('hidden') && !panel.contains(e.target) && !btn.contains(e.target)) {
        panel.classList.add('hidden');
        document.getElementById('servicios-chevron').style.transform = '';
    }
});
</script>

@endsection
