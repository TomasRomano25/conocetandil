@extends('layouts.app')

@section('title', 'Guías - Conoce Tandil')

@section('content')

    {{-- ═══ PAGE HEADER ═══ --}}
    @php $guiasImage = $guiasBanner->image ?? null; @endphp
    <section class="relative overflow-hidden flex items-end" style="background-color: #0F1F16; min-height: 42vh;">
        @if ($guiasImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $guiasImage) }}')"></div>
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(15,31,22,0.35) 0%, rgba(15,31,22,0.85) 100%);"></div>
        @else
            <div class="absolute inset-0" style="background: radial-gradient(ellipse at 75% 25%, rgba(45,106,79,0.3) 0%, transparent 55%);"></div>
        @endif
        <div class="absolute -right-32 -top-32 w-[500px] h-[500px] rounded-full border border-white/[0.04] pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14 pt-32">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-4 rounded-full bg-[#52B788]"></div>
                <span class="text-[#52B788] text-xs font-semibold uppercase tracking-[0.18em]">Tandil · Buenos Aires</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white leading-[0.95] tracking-tight mb-4">
                {{ $guiasBanner->title ?? 'Guías de Tandil' }}
            </h1>
            <p class="text-white/50 text-base md:text-lg max-w-xl leading-relaxed">
                {{ $guiasBanner->subtitle ?? 'Conseguí la guía perfecta para tu próxima aventura en las sierras.' }}
            </p>
        </div>
    </section>

    {{-- ═══ PRODUCTS GRID ═══ --}}
    <section class="py-16 md:py-20 bg-[#FAFAF8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section intro --}}
            <div class="mb-12">
                <span class="text-[#2D6A4F] text-xs font-semibold uppercase tracking-[0.15em] mb-3 block">Colección editorial</span>
                <h2 class="text-3xl md:text-4xl font-black text-[#111827] tracking-tight leading-[1.05]">Explorá Tandil con nuestras guías</h2>
                <p class="text-[#6B7280] text-base mt-3 max-w-lg leading-relaxed">Cada guía está cuidadosamente preparada por expertos locales para que aproveches al máximo tu visita.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([
                    ['title' => 'Guía de Senderismo', 'price' => '$4.500', 'tag' => 'Aventura', 'desc' => 'Todos los senderos de Tandil con mapas, dificultad, tiempos estimados y consejos de seguridad.'],
                    ['title' => 'Guía Gastronómica', 'price' => '$3.800', 'tag' => 'Gastronomía', 'desc' => 'Los mejores restaurantes, queserías, cervecerías artesanales y productores locales.'],
                    ['title' => 'Guía de Aventura', 'price' => '$5.200', 'tag' => 'Deportes', 'desc' => 'Rappel, escalada, mountain bike y todas las actividades de adrenalina disponibles en la zona.'],
                    ['title' => 'Mapa Turístico Ilustrado', 'price' => '$2.500', 'tag' => 'Cartografía', 'desc' => 'Mapa desplegable con ilustraciones artísticas de todos los puntos de interés de Tandil.'],
                    ['title' => 'Guía de Historia y Cultura', 'price' => '$3.500', 'tag' => 'Cultura', 'desc' => 'Recorrido por la historia de Tandil, sus museos, monumentos y patrimonio cultural.'],
                    ['title' => 'Pack Completo Tandil', 'price' => '$12.000', 'tag' => 'Pack', 'desc' => 'Incluye todas las guías + mapa ilustrado + acceso a contenido digital exclusivo.', 'featured' => true],
                ] as $guia)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        {{-- Image area --}}
                        <div class="relative h-52 overflow-hidden bg-gray-100">
                            <div class="w-full h-full bg-gradient-to-br from-[#0F1F16] to-[#2D6A4F]/60 flex items-center justify-center">
                                <svg class="w-14 h-14 text-white/15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            {{-- Tag --}}
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-gray-700 text-[0.68rem] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                {{ $guia['tag'] }}
                            </span>
                            @if (!empty($guia['featured']))
                                <div class="absolute top-3 right-3 flex items-center gap-1 bg-amber-400 text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-sm">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    Best Seller
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-base font-bold text-[#111827] mb-1 group-hover:text-[#2D6A4F] transition-colors leading-snug">
                                {{ $guia['title'] }}
                            </h3>
                            <p class="text-[#6B7280] text-sm leading-relaxed mb-4 flex-1">{{ $guia['desc'] }}</p>
                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <span class="text-[#111827] text-lg font-black tracking-tight">{{ $guia['price'] }}</span>
                                <button class="bg-[#2D6A4F] hover:bg-[#245C43] text-white font-semibold text-sm px-4 py-2 rounded-xl transition-colors flex items-center gap-1.5">
                                    Comprar
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
