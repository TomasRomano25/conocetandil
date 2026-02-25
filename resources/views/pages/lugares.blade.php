@extends('layouts.app')

@section('title', 'Lugares - Conoce Tandil')

@section('content')

    {{-- ═══ PAGE HEADER ═══ --}}
    @php $bannerImage = $lugaresBanner->image ?? null; @endphp
    <section class="relative overflow-hidden flex items-end" style="background-color: #0F1F16; min-height: 42vh;">
        @if ($bannerImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $bannerImage) }}')"></div>
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
                {{ $lugaresBanner->title ?? 'Lugares para Visitar' }}
            </h1>
            <p class="text-white/50 text-base md:text-lg max-w-xl leading-relaxed">
                {{ $lugaresBanner->subtitle ?? 'Explorá todos los rincones que hacen de Tandil un destino único.' }}
            </p>
        </div>
    </section>

    {{-- ═══ FILTER BAR ═══ --}}
    <div class="bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-[68px] z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <form action="{{ route('lugares') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Buscar lugares..."
                        class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition-all bg-white"
                        autocomplete="off">
                </div>
                <select name="category"
                    class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] bg-white transition-all sm:w-52">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-[#111827] hover:bg-[#2D6A4F] text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 flex-shrink-0">
                    Filtrar
                </button>
                @if($q !== '' || $category !== '')
                    <a href="{{ route('lugares') }}"
                        class="flex items-center justify-center gap-1.5 text-gray-400 hover:text-gray-700 text-sm font-medium transition-colors px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- ═══ GRID SECTION ═══ --}}
    <section class="py-12 md:py-16 bg-[#FAFAF8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Results summary --}}
            @if($q !== '' || $category !== '')
                <div class="mb-8 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-[#9CA3AF]">{{ $lugares->count() }} {{ $lugares->count() === 1 ? 'resultado' : 'resultados' }}</span>
                    @if($q !== '')
                        <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">"{{ $q }}"</span>
                    @endif
                    @if($category !== '')
                        <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-semibold px-3 py-1 rounded-full">{{ $category }}</span>
                    @endif
                </div>
            @endif

            @if($lugares->isEmpty())
                <div class="text-center py-24">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <p class="text-[#111827] font-bold text-lg mb-2">No se encontraron lugares</p>
                    <p class="text-[#9CA3AF] text-sm mb-6">Probá con otra búsqueda o eliminá los filtros activos</p>
                    <a href="{{ route('lugares') }}"
                        class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#245C43] text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                        Ver todos los lugares
                    </a>
                </div>
            @else
                @php $lugarCount = 0; @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($lugares as $lugar)
                        @php $lugarCount++; @endphp

                        <a href="{{ route('lugar.show', $lugar) }}"
                            class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="relative h-52 overflow-hidden bg-gray-100">
                                @if ($lugar->cover_image)
                                    @php $fp = $lugar->cover_focal_point; @endphp
                                    <img src="{{ asset('storage/' . $lugar->cover_image) }}" alt="{{ $lugar->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                        style="object-position: {{ $fp['x'] }}% {{ $fp['y'] }}%">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[#0F1F16] to-[#2D6A4F]/50 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                @endif

                                @if ($lugar->category)
                                    <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-gray-700 text-[0.68rem] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                        {{ $lugar->category }}
                                    </span>
                                @endif

                                @if ($lugar->is_premium)
                                    <div class="absolute top-3 right-3 flex items-center gap-1 bg-amber-400 text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-sm">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        Premium
                                    </div>
                                @endif
                            </div>

                            <div class="p-5">
                                <h3 class="text-base font-bold text-[#111827] mb-1.5 group-hover:text-[#2D6A4F] transition-colors leading-snug">
                                    {{ $lugar->title }}
                                </h3>
                                <p class="text-[#9CA3AF] text-xs font-medium flex items-center gap-1.5 mb-3">
                                    <svg class="w-3.5 h-3.5 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $lugar->direction }}
                                </p>
                                <p class="text-[#6B7280] text-sm leading-relaxed line-clamp-2">{{ Str::limit($lugar->description, 110) }}</p>
                                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <span class="text-[#2D6A4F] text-sm font-semibold">Ver lugar</span>
                                    <svg class="w-4 h-4 text-[#2D6A4F] group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </div>
                            </div>
                        </a>

                        {{-- Promotional banner after Nth card --}}
                        @if(isset($lugarBanners[$lugarCount]))
                            @php $banner = $lugarBanners[$lugarCount]; @endphp
                            <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                                @include('partials.lugar-banner', ['banner' => $banner])
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection
