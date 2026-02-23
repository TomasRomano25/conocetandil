@extends('layouts.app')

@section('title', 'Lugares - Conoce Tandil')

@section('content')
    {{-- Header --}}
    @php $bannerImage = $lugaresBanner->image ?? null; @endphp
    <section class="relative text-white overflow-hidden py-16 {{ $bannerImage ? '' : 'bg-[#2D6A4F]' }}">
        @if ($bannerImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $bannerImage) }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/75 to-[#1A1A1A]/80"></div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ $lugaresBanner->title ?? 'Lugares para Visitar' }}</h1>
            <p class="text-gray-200 max-w-xl mx-auto">{{ $lugaresBanner->subtitle ?? 'Explorá todos los rincones que hacen de Tandil un destino único.' }}</p>
        </div>
    </section>

    {{-- Search/Filter Bar --}}
    <section class="bg-white shadow-sm border-b sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('lugares') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Buscar lugares..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent"
                    autocomplete="off">
                <select name="category"
                    class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent bg-white">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white px-6 py-2.5 rounded-lg transition font-semibold text-sm">
                    Filtrar
                </button>
                @if($q !== '' || $category !== '')
                    <a href="{{ route('lugares') }}"
                        class="flex items-center justify-center gap-1.5 text-gray-500 hover:text-[#2D6A4F] text-sm font-medium transition px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- Lugares Grid --}}
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Results summary --}}
            @if($q !== '' || $category !== '')
                <div class="mb-6 flex flex-wrap items-center gap-2 text-sm text-gray-600">
                    <span>{{ $lugares->count() }} {{ $lugares->count() === 1 ? 'resultado' : 'resultados' }}</span>
                    @if($q !== '')
                        <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] font-medium px-2.5 py-0.5 rounded-full">
                            "{{ $q }}"
                        </span>
                    @endif
                    @if($category !== '')
                        <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] font-medium px-2.5 py-0.5 rounded-full">
                            {{ $category }}
                        </span>
                    @endif
                </div>
            @endif

            @if($lugares->isEmpty())
                {{-- Empty state --}}
                <div class="text-center py-20">
                    <svg class="w-14 h-14 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium mb-1">No se encontraron lugares</p>
                    <p class="text-gray-400 text-sm mb-5">Probá con otra búsqueda o eliminá los filtros</p>
                    <a href="{{ route('lugares') }}"
                        class="inline-flex items-center gap-1.5 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                        Ver todos los lugares
                    </a>
                </div>
            @else
                {{-- Grid with banners injected between cards --}}
                @php $lugarCount = 0; @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($lugares as $lugar)
                        @php $lugarCount++; @endphp

                        {{-- Lugar card --}}
                        <a href="{{ route('lugar.show', $lugar) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 block group">
                            <div class="relative h-48 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center overflow-hidden">
                                @if ($lugar->cover_image)
                                    <img src="{{ asset('storage/' . $lugar->cover_image) }}" alt="{{ $lugar->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                                @if ($lugar->is_premium)
                                    <div class="absolute top-3 right-3 flex items-center gap-1 bg-amber-400 text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-md">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                                        Premium
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($lugar->category)
                                    <span class="text-[#2D6A4F] text-xs font-bold uppercase tracking-wide">{{ $lugar->category }}</span>
                                @endif
                                <h3 class="text-lg font-bold text-[#1A1A1A] mt-1 mb-1 group-hover:text-[#2D6A4F] transition-colors">{{ $lugar->title }}</h3>
                                <p class="text-[#2D6A4F] text-sm font-medium mb-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $lugar->direction }}
                                </p>
                                <p class="text-gray-600 text-sm">{{ Str::limit($lugar->description, 120) }}</p>
                            </div>
                        </a>

                        {{-- Promotional banner after Nth card (spans full grid width) --}}
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
