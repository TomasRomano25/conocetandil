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

{{-- Filter tabs --}}
<section class="bg-white shadow-sm border-b sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex gap-2 overflow-x-auto">
        <a href="{{ route('hoteles.index') }}"
            class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition {{ ! request('tier') ? 'bg-[#2D6A4F] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Todos
        </a>
        <a href="{{ route('hoteles.index', ['tier' => 1]) }}"
            class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('tier') == 1 ? 'bg-[#2D6A4F] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Básico
        </a>
        <a href="{{ route('hoteles.index', ['tier' => 2]) }}"
            class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('tier') == 2 ? 'bg-[#2D6A4F] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Estándar
        </a>
        <a href="{{ route('hoteles.index', ['tier' => 3]) }}"
            class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition {{ request('tier') == 3 ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            ✦ Diamante
        </a>
    </div>
</section>

{{-- Grid --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($hotels->isEmpty())
            <div class="text-center py-20">
                <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-gray-500 font-medium mb-1">No hay hoteles disponibles</p>
                <p class="text-gray-400 text-sm mb-5">Todavía no hay hoteles registrados en esta categoría.</p>
                <a href="{{ route('hoteles.index') }}" class="text-[#2D6A4F] hover:underline text-sm font-semibold">Ver todos</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($hotels as $hotel)
                <a href="{{ route('hoteles.show', $hotel) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 group block">
                    {{-- Cover image --}}
                    <div class="relative h-48 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center overflow-hidden">
                        @if ($hotel->cover_image)
                            <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        @endif

                        {{-- Tier badge --}}
                        @if ($hotel->plan->tier === 3)
                            <div class="absolute top-3 right-3 flex items-center gap-1 bg-amber-400 text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-md">
                                ✦ Diamante
                            </div>
                        @elseif ($hotel->plan->tier === 2)
                            <div class="absolute top-3 right-3 bg-[#2D6A4F] text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-md">
                                Estándar
                            </div>
                        @endif

                        {{-- Stars --}}
                        @if ($hotel->stars)
                            <div class="absolute bottom-3 left-3 flex gap-0.5">
                                @for ($s = 1; $s <= 5; $s++)
                                    <svg class="w-3.5 h-3.5 {{ $s <= $hotel->stars ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-[#1A1A1A] mb-1 group-hover:text-[#2D6A4F] transition-colors">{{ $hotel->name }}</h3>
                        <p class="text-[#2D6A4F] text-sm font-medium mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $hotel->address }}
                        </p>
                        @if ($hotel->short_description)
                            <p class="text-gray-600 text-sm">{{ Str::limit($hotel->short_description, 100) }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
