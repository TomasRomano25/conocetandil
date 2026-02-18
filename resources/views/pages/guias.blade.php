@extends('layouts.app')

@section('title', 'Guías - Conoce Tandil')

@section('content')
    {{-- Header --}}
    @php $guiasImage = $guiasBanner->image ?? null; @endphp
    <section class="relative text-white py-20 overflow-hidden {{ $guiasImage ? '' : 'bg-[#2D6A4F]' }}">
        @if ($guiasImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $guiasImage) }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/75 to-[#1A1A1A]/80"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F] to-[#1A1A1A] opacity-90"></div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ $guiasBanner->title ?? 'Guías de Tandil' }}</h1>
            <p class="text-gray-200 max-w-xl mx-auto">{{ $guiasBanner->subtitle ?? 'Conseguí la guía perfecta para tu próxima aventura en las sierras.' }}</p>
        </div>
    </section>

    {{-- Products Grid --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach([
                    ['title' => 'Guía de Senderismo', 'price' => '$4.500', 'desc' => 'Todos los senderos de Tandil con mapas, dificultad, tiempos estimados y consejos de seguridad.'],
                    ['title' => 'Guía Gastronómica', 'price' => '$3.800', 'desc' => 'Los mejores restaurantes, queserías, cervecerías artesanales y productores locales.'],
                    ['title' => 'Guía de Aventura', 'price' => '$5.200', 'desc' => 'Rappel, escalada, mountain bike y todas las actividades de adrenalina disponibles en la zona.'],
                    ['title' => 'Mapa Turístico Ilustrado', 'price' => '$2.500', 'desc' => 'Mapa desplegable con ilustraciones artísticas de todos los puntos de interés de Tandil.'],
                    ['title' => 'Guía de Historia y Cultura', 'price' => '$3.500', 'desc' => 'Recorrido por la historia de Tandil, sus museos, monumentos y patrimonio cultural.'],
                    ['title' => 'Pack Completo Tandil', 'price' => '$12.000', 'desc' => 'Incluye todas las guías + mapa ilustrado + acceso a contenido digital exclusivo.'],
                ] as $guia)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 flex flex-col">
                        <div class="h-48 bg-gradient-to-br from-[#1A1A1A]/10 to-[#2D6A4F]/20 flex items-center justify-center">
                            <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-lg font-bold text-[#1A1A1A] mb-1">{{ $guia['title'] }}</h3>
                            <p class="text-[#2D6A4F] text-xl font-bold mb-2">{{ $guia['price'] }}</p>
                            <p class="text-gray-600 text-sm mb-4 flex-1">{{ $guia['desc'] }}</p>
                            <button class="w-full bg-[#52B788] hover:bg-[#2D6A4F] text-white font-bold py-3 rounded-lg transition">
                                Comprar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
