@extends('layouts.app')

@section('title', 'Lugares - Conoce Tandil')

@section('content')
    {{-- Header --}}
    <section class="bg-[#2D6A4F] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Lugares para Visitar</h1>
            <p class="text-gray-200 max-w-xl mx-auto">Explorá todos los rincones que hacen de Tandil un destino único.</p>
        </div>
    </section>

    {{-- Search/Filter Bar --}}
    <section class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" placeholder="Buscar lugares..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <select class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    <option>Todas las categorías</option>
                    <option>Naturaleza</option>
                    <option>Cultura</option>
                    <option>Gastronomía</option>
                    <option>Aventura</option>
                </select>
                <button class="bg-[#2D6A4F] hover:bg-[#52B788] text-white px-6 py-2 rounded-lg transition font-semibold">
                    Filtrar
                </button>
            </div>
        </div>
    </section>

    {{-- Lugares Grid --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($lugares as $lugar)
                    <a href="{{ route('lugar.show', $lugar) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 block">
                        <div class="h-48 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center">
                            @if ($lugar->cover_image)
                                <img src="{{ asset('storage/' . $lugar->cover_image) }}" alt="{{ $lugar->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-1">{{ $lugar->title }}</h3>
                            <p class="text-[#2D6A4F] text-sm font-medium mb-2">{{ $lugar->direction }}</p>
                            <p class="text-gray-600 text-sm">{{ Str::limit($lugar->description, 120) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
