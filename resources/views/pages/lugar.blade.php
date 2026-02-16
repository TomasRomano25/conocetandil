@extends('layouts.app')

@section('title', $lugar->title . ' - Conoce Tandil')

@section('content')
    @php
        $allImages = collect();
        if ($lugar->image) {
            $allImages->push($lugar->image);
        }
        foreach ($lugar->images as $img) {
            if ($img->path !== $lugar->image) {
                $allImages->push($img->path);
            }
        }
        $imageCount = $allImages->count();
    @endphp

    {{-- ========================================
         DESKTOP GALLERY (md+)
         ======================================== --}}
    @if ($imageCount > 0)
        <section class="hidden md:block py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-3 gap-3 h-[500px]">
                    {{-- Primary image --}}
                    <div class="col-span-2 relative rounded-2xl overflow-hidden shadow-xl cursor-pointer" onclick="LugarLightbox.open(0)">
                        <img src="{{ asset('storage/' . $allImages->first()) }}" alt="{{ $lugar->title }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @if ($imageCount > 1)
                            <div class="absolute top-4 right-4 bg-black/60 text-white text-sm px-3 py-1 rounded-full backdrop-blur-sm">
                                <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $imageCount }} fotos
                            </div>
                        @endif
                    </div>
                    {{-- Right column thumbnails --}}
                    <div class="flex flex-col gap-3">
                        @foreach ($allImages->slice(1, 3) as $index => $imagePath)
                            <div class="relative flex-1 rounded-2xl overflow-hidden shadow-lg cursor-pointer" onclick="LugarLightbox.open({{ $index + 1 }})">
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $lugar->title }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                @if ($loop->last && $imageCount > 4)
                                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">+{{ $imageCount - 4 }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @if ($imageCount === 1)
                            <div class="flex-1 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ========================================
         MOBILE CAROUSEL (<md)
         ======================================== --}}
    @if ($imageCount > 0)
        <section class="md:hidden relative">
            <div id="mobile-carousel" class="relative h-80 overflow-hidden bg-gray-900">
                <div id="carousel-track" class="flex h-full transition-transform duration-300 ease-out" style="width: {{ $imageCount * 100 }}%;">
                    @foreach ($allImages as $imagePath)
                        <div class="h-full flex-shrink-0" style="width: {{ 100 / $imageCount }}%;">
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $lugar->title }}"
                                class="w-full h-full object-cover" onclick="LugarLightbox.open({{ $loop->index }})">
                        </div>
                    @endforeach
                </div>
                {{-- Image counter --}}
                <div class="absolute top-4 right-4 bg-black/60 text-white text-sm px-3 py-1 rounded-full backdrop-blur-sm">
                    <span id="carousel-current">1</span>/{{ $imageCount }}
                </div>
                {{-- Prev/Next arrows --}}
                @if ($imageCount > 1)
                    <button onclick="MobileCarousel.prev()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button onclick="MobileCarousel.next()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                @endif
                {{-- Dots --}}
                @if ($imageCount > 1 && $imageCount <= 10)
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                        @for ($i = 0; $i < $imageCount; $i++)
                            <button onclick="MobileCarousel.goTo({{ $i }})"
                                class="carousel-dot w-2 h-2 rounded-full transition {{ $i === 0 ? 'bg-white' : 'bg-white/50' }}"></button>
                        @endfor
                    </div>
                @endif
            </div>
        </section>
    @elseif (!$imageCount)
        {{-- No images placeholder --}}
        <section class="bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 h-48 md:h-64 flex items-center justify-center">
            <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </section>
    @endif

    {{-- ========================================
         TITLE BLOCK
         ======================================== --}}
    <section class="border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <div class="flex flex-wrap items-center gap-3 mb-3">
                @if ($lugar->category)
                    <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-sm font-semibold px-4 py-1 rounded-full">{{ $lugar->category }}</span>
                @endif
                @if ($lugar->rating)
                    <div class="flex items-center gap-1.5">
                        @php $fullStars = floor($lugar->rating); $halfStar = ($lugar->rating - $fullStars) >= 0.5; @endphp
                        @for ($i = 0; $i < 5; $i++)
                            @if ($i < $fullStars)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @elseif ($halfStar && $i === $fullStars)
                                <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20">
                                    <defs><linearGradient id="halfGrad"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#D1D5DB"/></linearGradient></defs>
                                    <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endif
                        @endfor
                        <span class="text-sm font-semibold text-gray-700 ml-1">{{ number_format($lugar->rating, 1) }}</span>
                    </div>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-[#1A1A1A] mb-3">{{ $lugar->title }}</h1>

            <a href="{{ $lugar->google_maps_url }}" target="_blank" rel="noopener"
                class="inline-flex items-center gap-2 text-[#2D6A4F] hover:text-[#52B788] font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $lugar->direction }}
            </a>
        </div>
    </section>

    {{-- ========================================
         INFO BLOCK
         ======================================== --}}
    @if ($lugar->opening_hours || $lugar->phone || $lugar->website)
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Info cards --}}
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if ($lugar->opening_hours)
                            <div class="bg-gray-50 rounded-lg p-4 flex items-start gap-3">
                                <div class="bg-[#2D6A4F]/10 rounded-lg p-2 flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Horarios</p>
                                    <p class="text-sm text-gray-600">{{ $lugar->opening_hours }}</p>
                                </div>
                            </div>
                        @endif
                        @if ($lugar->phone)
                            <div class="bg-gray-50 rounded-lg p-4 flex items-start gap-3">
                                <div class="bg-[#2D6A4F]/10 rounded-lg p-2 flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">Teléfono</p>
                                    <p class="text-sm text-gray-600">{{ $lugar->phone }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- Action buttons --}}
                    <div class="flex flex-row md:flex-col gap-3 md:w-64 flex-shrink-0">
                        <a href="{{ $lugar->google_maps_directions_url }}" target="_blank" rel="noopener"
                            class="flex-1 md:flex-none bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-3 px-6 rounded-lg transition text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Cómo Llegar
                        </a>
                        @if ($lugar->phone)
                            <a href="tel:{{ $lugar->phone }}"
                                class="flex-1 md:flex-none border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-semibold py-3 px-6 rounded-lg transition text-center flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Contactar
                            </a>
                        @endif
                        @if ($lugar->website)
                            <a href="{{ $lugar->website }}" target="_blank" rel="noopener"
                                class="flex-1 md:flex-none border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-semibold py-3 px-6 rounded-lg transition text-center flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                Sitio Web
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @else
        {{-- Minimal action bar when no info --}}
        <section class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ $lugar->google_maps_directions_url }}" target="_blank" rel="noopener"
                    class="inline-flex bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-3 px-6 rounded-lg transition items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cómo Llegar
                </a>
            </div>
        </section>
    @endif

    {{-- ========================================
         DESCRIPTION
         ======================================== --}}
    <section class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-4">Sobre este lugar</h2>
                {{-- Desktop: full description --}}
                <div class="hidden md:block prose prose-lg text-gray-600 leading-relaxed">
                    {!! nl2br(e($lugar->description)) !!}
                </div>
                {{-- Mobile: collapsible description --}}
                <div class="md:hidden">
                    <div id="description-container" class="relative overflow-hidden" style="max-height: 150px;">
                        <div class="prose prose-lg text-gray-600 leading-relaxed">
                            {!! nl2br(e($lugar->description)) !!}
                        </div>
                        <div id="description-fade" class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                    </div>
                    <button id="description-toggle" onclick="toggleDescription()" class="mt-3 text-[#2D6A4F] hover:text-[#52B788] font-semibold text-sm transition">
                        Leer más
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================
         PROMOTION BANNER
         ======================================== --}}
    @if ($lugar->hasPromotion())
        <section class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-[#F0FFF4] to-[#E6F7ED] rounded-2xl border border-[#2D6A4F]/10 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-6">
                    <div class="bg-[#2D6A4F]/10 rounded-xl p-3 flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-[#1A1A1A] mb-1">{{ $lugar->promotion_title }}</h3>
                        @if ($lugar->promotion_description)
                            <p class="text-gray-600 text-sm">{{ $lugar->promotion_description }}</p>
                        @endif
                    </div>
                    @if ($lugar->promotion_url)
                        <a href="{{ $lugar->promotion_url }}" target="_blank" rel="noopener"
                            class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition flex-shrink-0 text-center">
                            Ver Promoción
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ========================================
         MAP PREVIEW
         ======================================== --}}
    @if ($lugar->hasCoordinates())
        <section class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-4">Ubicación</h2>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <iframe
                        src="https://maps.google.com/maps?q={{ $lugar->latitude }},{{ $lugar->longitude }}&z=15&output=embed"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full"></iframe>
                </div>
                <a href="{{ $lugar->google_maps_url }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 mt-4 text-[#2D6A4F] hover:text-[#52B788] font-semibold transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Abrir en Google Maps
                </a>
            </div>
        </section>
    @endif

    {{-- ========================================
         RELATED PLACES
         ======================================== --}}
    @if ($relatedPlaces->count())
        <section class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Otros lugares para explorar</h2>

                {{-- Desktop: 4-col grid --}}
                <div class="hidden md:grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedPlaces->take(4) as $related)
                        <a href="{{ route('lugar.show', $related) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 block">
                            <div class="h-40 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center">
                                @if ($related->cover_image)
                                    <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-[#1A1A1A] mb-1">{{ $related->title }}</h3>
                                <p class="text-[#2D6A4F] text-xs font-medium">{{ $related->direction }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Mobile: horizontal scroll --}}
                <div class="md:hidden flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory">
                    @foreach ($relatedPlaces as $related)
                        <a href="{{ route('lugar.show', $related) }}" class="flex-shrink-0 w-72 bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 block snap-start">
                            <div class="h-40 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center">
                                @if ($related->cover_image)
                                    <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-[#1A1A1A] mb-1">{{ $related->title }}</h3>
                                <p class="text-[#2D6A4F] text-xs font-medium">{{ $related->direction }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ========================================
         BACK LINK
         ======================================== --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('lugares') }}" class="inline-flex items-center gap-2 text-[#2D6A4F] hover:text-[#52B788] font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Lugares
            </a>
        </div>
    </section>

    {{-- ========================================
         STICKY BOTTOM CTA (mobile)
         ======================================== --}}
    <div id="sticky-cta" class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-2xl p-4 z-40 transform translate-y-full transition-transform duration-300">
        <a href="{{ $lugar->google_maps_directions_url }}" target="_blank" rel="noopener"
            class="block bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-3.5 rounded-lg transition text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Cómo Llegar
        </a>
    </div>

    {{-- ========================================
         LIGHTBOX
         ======================================== --}}
    <div id="lightbox" class="fixed inset-0 bg-black/95 z-50 hidden items-center justify-center" onclick="LugarLightbox.backdropClick(event)">
        <button onclick="LugarLightbox.close()" class="absolute top-4 right-4 text-white/80 hover:text-white z-10 p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div id="lightbox-counter" class="absolute top-4 left-4 text-white/80 text-sm"></div>
        @if ($imageCount > 1)
            <button onclick="LugarLightbox.prev()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white p-2 z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button onclick="LugarLightbox.next()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white p-2 z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[90vh] object-contain">
    </div>

    {{-- ========================================
         JAVASCRIPT
         ======================================== --}}
    <script>
    (function() {
        // Image data
        var images = @json($allImages->values());
        var imageBaseUrl = '{{ asset('storage') }}/';

        // ── Lightbox ──
        window.LugarLightbox = (function() {
            var currentIndex = 0;
            var lightbox = document.getElementById('lightbox');
            var lightboxImage = document.getElementById('lightbox-image');
            var lightboxCounter = document.getElementById('lightbox-counter');

            function show(index) {
                currentIndex = index;
                lightboxImage.src = imageBaseUrl + images[index];
                lightboxCounter.textContent = (index + 1) + ' / ' + images.length;
            }

            return {
                open: function(index) {
                    show(index);
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                },
                close: function() {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    document.body.style.overflow = '';
                },
                prev: function() {
                    show((currentIndex - 1 + images.length) % images.length);
                },
                next: function() {
                    show((currentIndex + 1) % images.length);
                },
                backdropClick: function(e) {
                    if (e.target === lightbox) {
                        LugarLightbox.close();
                    }
                }
            };
        })();

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            var lightbox = document.getElementById('lightbox');
            if (lightbox.classList.contains('hidden')) return;
            if (e.key === 'Escape') LugarLightbox.close();
            if (e.key === 'ArrowLeft') LugarLightbox.prev();
            if (e.key === 'ArrowRight') LugarLightbox.next();
        });

        // ── Mobile Carousel ──
        window.MobileCarousel = (function() {
            var current = 0;
            var total = images.length;
            var track = document.getElementById('carousel-track');
            var counterEl = document.getElementById('carousel-current');
            var dots = document.querySelectorAll('.carousel-dot');

            function update() {
                if (!track) return;
                track.style.transform = 'translateX(-' + (current * (100 / total)) + '%)';
                if (counterEl) counterEl.textContent = current + 1;
                dots.forEach(function(dot, i) {
                    dot.classList.toggle('bg-white', i === current);
                    dot.classList.toggle('bg-white/50', i !== current);
                });
            }

            // Touch support
            var startX = 0, startY = 0, isDragging = false;
            var carousel = document.getElementById('mobile-carousel');
            if (carousel) {
                carousel.addEventListener('touchstart', function(e) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    isDragging = true;
                }, { passive: true });
                carousel.addEventListener('touchend', function(e) {
                    if (!isDragging) return;
                    isDragging = false;
                    var diffX = startX - e.changedTouches[0].clientX;
                    var diffY = startY - e.changedTouches[0].clientY;
                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                        if (diffX > 0 && current < total - 1) { current++; update(); }
                        else if (diffX < 0 && current > 0) { current--; update(); }
                    }
                }, { passive: true });
            }

            return {
                prev: function() { if (current > 0) { current--; update(); } },
                next: function() { if (current < total - 1) { current++; update(); } },
                goTo: function(i) { current = i; update(); }
            };
        })();

        // ── Collapsible Description ──
        window.toggleDescription = function() {
            var container = document.getElementById('description-container');
            var fade = document.getElementById('description-fade');
            var toggle = document.getElementById('description-toggle');
            if (!container) return;

            if (container.style.maxHeight === 'none') {
                container.style.maxHeight = '150px';
                fade.style.display = '';
                toggle.textContent = 'Leer más';
            } else {
                container.style.maxHeight = 'none';
                fade.style.display = 'none';
                toggle.textContent = 'Leer menos';
            }
        };

        // ── Sticky CTA ──
        (function() {
            var stickyCta = document.getElementById('sticky-cta');
            if (!stickyCta) return;
            var lastScroll = 0;
            var shown = false;

            window.addEventListener('scroll', function() {
                var scrollY = window.scrollY;
                if (scrollY > 500 && scrollY > lastScroll) {
                    if (!shown) {
                        stickyCta.style.transform = 'translateY(0)';
                        shown = true;
                    }
                } else if (scrollY < lastScroll) {
                    if (shown) {
                        stickyCta.style.transform = 'translateY(100%)';
                        shown = false;
                    }
                }
                lastScroll = scrollY;
            }, { passive: true });
        })();
    })();
    </script>
@endsection
