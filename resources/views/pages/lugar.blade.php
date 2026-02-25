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
        $heroImage  = $allImages->first();
        $sideImages = $allImages->slice(1, 2);
        $isPremiumLocked = $lugar->is_premium
            && !(auth()->check() && auth()->user()->isPremium());
    @endphp

    {{-- ========================================
         BREADCRUMB / BACK NAV
         ======================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-2">
        <a href="{{ route('lugares') }}"
            class="inline-flex items-center gap-1.5 text-gray-500 hover:text-[#2D6A4F] font-medium transition-colors text-sm group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a Lugares
        </a>
    </div>

    {{-- ========================================
         GALLERY
         ======================================== --}}
    @if ($imageCount > 0)
        <section class="mb-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Desktop: Airbnb-style grid --}}
                @if ($imageCount === 1)
                    {{-- Single image: full width --}}
                    <div class="hidden md:block relative rounded-2xl overflow-hidden cursor-pointer group" style="height: 500px;"
                         onclick="LugarLightbox.open(0)">
                        <img src="{{ asset('storage/' . $heroImage) }}" alt="{{ $lugar->title }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                @else
                    {{-- Multi-image: 2/3 + 1/3 grid --}}
                    <div class="hidden md:grid grid-cols-3 gap-2 rounded-2xl overflow-hidden relative" style="height: 500px;">
                        {{-- Main image (2/3 width) --}}
                        <div class="col-span-2 relative overflow-hidden cursor-pointer group" onclick="LugarLightbox.open(0)">
                            <img src="{{ asset('storage/' . $heroImage) }}" alt="{{ $lugar->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.02]">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors duration-300"></div>
                        </div>
                        {{-- Side images (1/3 width) --}}
                        <div class="grid {{ $imageCount >= 3 ? 'grid-rows-2' : 'grid-rows-1' }} gap-2">
                            @foreach ($sideImages as $sideIdx => $imagePath)
                                @php $realIndex = $sideIdx + 1; @endphp
                                <div class="relative overflow-hidden cursor-pointer group" onclick="LugarLightbox.open({{ $realIndex }})">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $lugar->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/15 transition-colors duration-300"></div>
                                    {{-- Overlay "+N fotos" on last visible tile if more exist --}}
                                    @if ($sideIdx === 1 && $imageCount > 3)
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center pointer-events-none">
                                            <span class="text-white font-bold text-lg">+{{ $imageCount - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        {{-- "Ver todas las fotos" floating button --}}
                        <button onclick="LugarLightbox.open(0)"
                            class="absolute bottom-4 right-4 flex items-center gap-2 bg-white/90 hover:bg-white text-[#1A1A1A] font-semibold text-sm py-2.5 px-4 rounded-xl shadow-lg backdrop-blur-sm transition-all duration-200 hover:shadow-xl z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ver todas las fotos ({{ $imageCount }})
                        </button>
                    </div>
                @endif

                {{-- Mobile: swipeable full-width gallery --}}
                <div class="md:hidden relative rounded-2xl overflow-hidden" style="height: 280px;">
                    <div id="mobile-gallery-track"
                        class="flex h-full transition-transform duration-300 ease-out will-change-transform"
                        style="width: {{ $imageCount * 100 }}%;">
                        @foreach ($allImages as $mIdx => $imagePath)
                            <div class="h-full flex-shrink-0" style="width: {{ 100 / $imageCount }}%;">
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $lugar->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    {{-- Fullscreen button --}}
                    <button onclick="LugarLightbox.open(window._mobileSlide || 0)"
                        class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white p-1.5 rounded-lg hover:bg-black/70 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
                        </svg>
                    </button>
                    @if ($imageCount > 1)
                        {{-- Image counter --}}
                        <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-full font-medium">
                            <span id="mobile-gallery-counter">1</span>/{{ $imageCount }}
                        </div>
                        {{-- Dot navigation --}}
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            @foreach ($allImages as $dotIdx => $imagePath)
                                <button onclick="mobileGotoSlide({{ $dotIdx }})"
                                    class="mobile-dot rounded-full transition-all duration-300 {{ $dotIdx === 0 ? 'bg-white w-4 h-1.5' : 'bg-white/50 w-1.5 h-1.5' }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    @else
        {{-- No images placeholder --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-[#2D6A4F]/10 to-[#52B788]/10 rounded-2xl flex items-center justify-center" style="height: 300px;">
                <svg class="w-16 h-16 text-[#2D6A4F]/25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    @endif

    {{-- ========================================
         TITLE + TWO-COLUMN LAYOUT
         ======================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-14">

            {{-- ── LEFT COLUMN ── --}}
            <div class="flex-1 min-w-0">

                {{-- Title Block --}}
                <div class="mb-7">
                    {{-- Category + Rating row --}}
                    <div class="flex flex-wrap items-center gap-2.5 mb-3">
                        @if ($lugar->category)
                            <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                {{ $lugar->category }}
                            </span>
                        @endif
                        @if ($lugar->is_premium)
                            <span class="flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                                Solo Premium
                            </span>
                        @endif
                        @if ($lugar->rating)
                            <div class="flex items-center gap-1">
                                @php $fullStars = floor($lugar->rating); $halfStar = ($lugar->rating - $fullStars) >= 0.5; @endphp
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i < $fullStars)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @elseif ($halfStar && $i === $fullStars)
                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                                            <defs><linearGradient id="hg"><stop offset="50%" stop-color="#FBBF24"/><stop offset="50%" stop-color="#D1D5DB"/></linearGradient></defs>
                                            <path fill="url(#hg)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                                <span class="text-sm font-semibold text-gray-600 ml-0.5">{{ number_format($lugar->rating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-[#1A1A1A] leading-tight mb-4 tracking-tight">
                        {{ $lugar->title }}
                    </h1>

                    <a href="{{ $lugar->google_maps_url }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 text-gray-500 hover:text-[#2D6A4F] transition-colors group">
                        <svg class="w-4 h-4 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm font-medium group-hover:underline underline-offset-2">{{ $lugar->direction }}</span>
                    </a>

                </div>

                <hr class="border-gray-200 mb-8">

                @if ($isPremiumLocked)
                    {{-- ── Premium Gate ── --}}
                    <div class="mb-10">
                        {{-- Blurred description preview --}}
                        <div class="relative">
                            <div class="blur-sm pointer-events-none select-none" aria-hidden="true">
                                <h2 class="text-xl font-bold text-[#1A1A1A] mb-4">Sobre este lugar</h2>
                                <p class="text-gray-600 leading-[1.85] text-[0.9375rem]">
                                    {{ Str::limit($lugar->description, 220) }}
                                </p>
                            </div>
                            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                        </div>

                        {{-- Lock card --}}
                        <div class="mt-6 bg-white border border-amber-200 rounded-2xl shadow-lg p-8 text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-[#1A1A1A] mb-2">Contenido exclusivo Premium</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6 max-w-sm mx-auto">
                                Suscribite a <strong>Conoce Tandil Premium</strong> para acceder a la información completa de este lugar, itinerarios exclusivos y mucho más.
                            </p>
                            <a href="{{ route('membership.planes') }}"
                                class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 hover:shadow-lg mb-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                                Ver planes Premium
                            </a>
                            @guest
                                <p class="text-gray-400 text-xs mt-2">
                                    ¿Ya sos Premium?
                                    <a href="{{ route('login') }}" class="text-[#2D6A4F] hover:underline font-semibold">Iniciá sesión</a>
                                </p>
                            @endguest
                        </div>
                    </div>
                @else
                    {{-- ── Description ── --}}
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-[#1A1A1A] mb-4">Sobre este lugar</h2>
                        {{-- Desktop --}}
                        <div class="hidden md:block">
                            <div class="text-gray-600 leading-[1.85] text-[0.9375rem] max-w-prose space-y-4">
                                {!! nl2br(e($lugar->description)) !!}
                            </div>
                        </div>
                        {{-- Mobile: collapsible --}}
                        <div class="md:hidden">
                            <div id="description-container" class="relative overflow-hidden" style="max-height: 160px;">
                                <div class="text-gray-600 leading-[1.85] text-[0.9375rem]">
                                    {!! nl2br(e($lugar->description)) !!}
                                </div>
                                <div id="description-fade" class="absolute bottom-0 left-0 right-0 h-14 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                            </div>
                            <button id="description-toggle" onclick="toggleDescription()"
                                class="mt-3 inline-flex items-center gap-1 text-[#2D6A4F] hover:text-[#52B788] font-semibold text-sm transition-colors">
                                <span id="description-toggle-text">Leer más</span>
                                <svg id="description-toggle-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ── Promotion Banner ── --}}
                    @if ($lugar->hasPromotion())
                        <div class="mb-10">
                            <div class="bg-gradient-to-r from-[#F0FFF4] to-[#E6F7ED] rounded-2xl border border-[#2D6A4F]/15 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <div class="bg-[#2D6A4F]/15 rounded-xl p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-bold text-[#1A1A1A] mb-0.5">{{ $lugar->promotion_title }}</h3>
                                    @if ($lugar->promotion_description)
                                        <p class="text-gray-600 text-sm leading-relaxed">{{ $lugar->promotion_description }}</p>
                                    @endif
                                </div>
                                @if ($lugar->promotion_url)
                                    <a href="{{ $lugar->promotion_url }}" target="_blank" rel="noopener"
                                        class="bg-[#2D6A4F] hover:bg-[#1A4A35] text-white font-semibold py-2.5 px-5 rounded-xl transition-all duration-200 text-sm flex-shrink-0 hover:shadow-md">
                                        Ver Promoción
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- ── Map ── --}}
                    @if ($lugar->hasCoordinates())
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-[#1A1A1A] mb-4">Ubicación</h2>
                            <div class="rounded-2xl overflow-hidden shadow-md ring-1 ring-gray-200">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $lugar->latitude }},{{ $lugar->longitude }}&z=15&output=embed"
                                    width="100%" height="360" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="w-full block"></iframe>
                            </div>
                            <a href="{{ $lugar->google_maps_url }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-1.5 mt-3 text-[#2D6A4F] hover:text-[#52B788] font-semibold transition-colors text-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Abrir en Google Maps
                            </a>
                        </div>
                    @endif
                @endif

            </div>{{-- /left column --}}

            {{-- ── RIGHT COLUMN: Info Card ── --}}
            <div class="lg:w-[360px] xl:w-[400px] flex-shrink-0">
                <div class="lg:sticky lg:top-24 space-y-4">

                @if ($isPremiumLocked)
                    {{-- Premium upsell card (right column) --}}
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl shadow-md p-6 text-center">
                        <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                        </div>
                        <h3 class="font-bold text-[#1A1A1A] mb-1">Lugar Premium</h3>
                        <p class="text-gray-500 text-sm mb-4 leading-relaxed">Accedé a todos los detalles, información de contacto, mapa y más.</p>
                        <a href="{{ route('membership.planes') }}"
                            class="block w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl transition text-sm mb-2">
                            Suscribirme a Premium
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="block w-full border border-gray-300 text-gray-600 hover:border-[#2D6A4F] hover:text-[#2D6A4F] font-semibold py-2.5 rounded-xl transition text-sm">
                                Ya tengo cuenta
                            </a>
                        @endguest
                    </div>
                @else
                    {{-- Info Card --}}
                    <div class="bg-white rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.10)] border border-gray-100 overflow-hidden">
                        {{-- Card header --}}
                        <div class="bg-gradient-to-br from-[#2D6A4F] to-[#1f5540] px-5 py-4">
                            <p class="text-[#74d4a4] text-[0.65rem] font-bold uppercase tracking-[0.12em] mb-0.5">Información práctica</p>
                            <h3 class="text-white text-base font-bold leading-snug">{{ $lugar->title }}</h3>
                        </div>

                        {{-- Card body: always open --}}
                        <div id="info-card-body">
                            <div class="px-5 py-5 space-y-4 border-b border-gray-100">

                                {{-- Address --}}
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Dirección</p>
                                        <p class="text-sm text-gray-700 font-medium leading-snug">{{ $lugar->direction }}</p>
                                    </div>
                                </div>

                                @if ($lugar->opening_hours)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Horarios</p>
                                            <p class="text-sm text-gray-700 font-medium leading-snug">{{ $lugar->opening_hours }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($lugar->phone)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Teléfono</p>
                                            <a href="tel:{{ $lugar->phone }}" class="text-sm text-[#2D6A4F] font-semibold hover:text-[#52B788] transition-colors">{{ $lugar->phone }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if ($lugar->website)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Sitio web</p>
                                            <a href="{{ $lugar->website }}" target="_blank" rel="noopener"
                                                class="text-sm text-[#2D6A4F] font-semibold hover:text-[#52B788] transition-colors truncate block">
                                                {{ $lugar->website }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($lugar->rating)
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Valoración</p>
                                            <p class="text-sm text-gray-700 font-bold">{{ number_format($lugar->rating, 1) }} <span class="text-gray-400 font-normal">/ 5.0</span></p>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            {{-- CTA Buttons --}}
                            <div class="px-5 py-5 space-y-3">
                                <a href="{{ $lugar->google_maps_directions_url }}" target="_blank" rel="noopener"
                                    class="flex items-center justify-center gap-2 w-full bg-[#2D6A4F] hover:bg-[#1A4A35] text-white font-bold py-3.5 rounded-xl transition-all duration-200 hover:shadow-lg active:scale-[0.98] text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Cómo Llegar
                                </a>
                                @if ($lugar->phone)
                                    <a href="tel:{{ $lugar->phone }}"
                                        class="flex items-center justify-center gap-2 w-full border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white font-bold py-3 rounded-xl transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        Llamar
                                    </a>
                                @endif
                                @if ($lugar->website)
                                    <a href="{{ $lugar->website }}" target="_blank" rel="noopener"
                                        class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-600 hover:border-[#2D6A4F] hover:text-[#2D6A4F] font-semibold py-3 rounded-xl transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Visitar sitio web
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                @endif

                </div>
            </div>{{-- /right column --}}

        </div>
    </section>

    {{-- ========================================
         RELATED PLACES
         ======================================== --}}
    @if ($relatedPlaces->count())
        <section class="bg-gray-50 border-t border-gray-100 py-14">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-end justify-between mb-8">
                    <div>
                        <p class="text-[#2D6A4F] text-[0.65rem] font-bold uppercase tracking-[0.12em] mb-1.5">Seguí explorando</p>
                        <h2 class="text-2xl md:text-3xl font-bold text-[#1A1A1A] tracking-tight">Otros lugares para explorar</h2>
                    </div>
                    <a href="{{ route('lugares') }}"
                        class="hidden md:inline-flex items-center gap-1.5 text-[#2D6A4F] hover:text-[#52B788] font-semibold text-sm transition-colors group">
                        Ver todos
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Desktop grid --}}
                <div class="hidden md:grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($relatedPlaces->take(4) as $related)
                        <a href="{{ route('lugar.show', $related) }}"
                            class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:-translate-y-1 block">
                            <div class="relative h-48 bg-gradient-to-br from-[#2D6A4F]/12 to-[#52B788]/12 overflow-hidden">
                                @if ($related->cover_image)
                                    <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-[#2D6A4F]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                @endif
                                @if ($related->category)
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-white/90 backdrop-blur-sm text-[#2D6A4F] text-[0.65rem] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                            {{ $related->category }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-[#1A1A1A] text-sm mb-1.5 group-hover:text-[#2D6A4F] transition-colors leading-snug">{{ $related->title }}</h3>
                                <p class="text-gray-500 text-xs flex items-center gap-1">
                                    <svg class="w-3 h-3 text-[#52B788] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $related->direction }}</span>
                                </p>
                                @if ($related->rating)
                                    <div class="flex items-center gap-1 mt-2">
                                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs font-semibold text-gray-600">{{ number_format($related->rating, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Mobile: horizontal scroll --}}
                <div class="-mx-4 px-4 md:hidden">
                    <div class="flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
                        @foreach ($relatedPlaces as $related)
                            <a href="{{ route('lugar.show', $related) }}"
                                class="group flex-shrink-0 w-64 bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 snap-start block">
                                <div class="relative h-44 bg-gradient-to-br from-[#2D6A4F]/12 to-[#52B788]/12 overflow-hidden">
                                    @if ($related->cover_image)
                                        <img src="{{ asset('storage/' . $related->cover_image) }}" alt="{{ $related->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-[#2D6A4F]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                    @endif
                                    @if ($related->category)
                                        <div class="absolute top-2.5 left-2.5">
                                            <span class="bg-white/90 backdrop-blur-sm text-[#2D6A4F] text-[0.6rem] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">{{ $related->category }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3.5">
                                    <h3 class="font-bold text-[#1A1A1A] text-sm mb-1 leading-snug">{{ $related->title }}</h3>
                                    <p class="text-gray-500 text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3 text-[#52B788] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="truncate">{{ $related->direction }}</span>
                                    </p>
                                    @if ($related->rating)
                                        <div class="flex items-center gap-1 mt-1.5">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="text-xs font-semibold text-gray-600">{{ number_format($related->rating, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile: See all link --}}
                <div class="md:hidden mt-4">
                    <a href="{{ route('lugares') }}" class="inline-flex items-center gap-1.5 text-[#2D6A4F] hover:text-[#52B788] font-semibold text-sm transition-colors">
                        Ver todos los lugares
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </div>
        </section>
    @endif

    {{-- ========================================
         LIGHTBOX
         ======================================== --}}
    <div id="lightbox" class="fixed inset-0 bg-black/97 z-50 hidden flex-col"
         onclick="LugarLightbox.backdropClick(event)">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" onclick="event.stopPropagation()">
            <div id="lightbox-counter" class="text-white/60 text-sm font-medium tabular-nums"></div>
            <button onclick="LugarLightbox.close()"
                class="text-white/60 hover:text-white transition-colors p-2 rounded-xl hover:bg-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        {{-- Main image area --}}
        <div class="flex-1 flex items-center justify-center relative px-12 md:px-20 min-h-0" onclick="event.stopPropagation()">
            @if ($imageCount > 1)
                <button onclick="LugarLightbox.prev()"
                    class="absolute left-3 md:left-6 text-white/50 hover:text-white p-3 rounded-full hover:bg-white/10 transition-all duration-200 z-10">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="LugarLightbox.next()"
                    class="absolute right-3 md:right-6 text-white/50 hover:text-white p-3 rounded-full hover:bg-white/10 transition-all duration-200 z-10">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endif
            <img id="lightbox-image" src="" alt=""
                class="max-w-full max-h-full object-contain rounded-lg select-none"
                style="max-height: calc(100vh - 160px);">
        </div>
        {{-- Thumbnail strip --}}
        @if ($imageCount > 1)
            <div class="flex-shrink-0 px-4 py-4" onclick="event.stopPropagation()">
                <div class="flex gap-2 overflow-x-auto justify-center pb-1">
                    @foreach ($allImages as $lbIdx => $imagePath)
                        <button onclick="LugarLightbox.show({{ $lbIdx }})"
                            data-lb-thumb="{{ $lbIdx }}"
                            class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 transition-all duration-200 {{ $lbIdx === 0 ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-80' }}">
                            <img src="{{ asset('storage/' . $imagePath) }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- ========================================
         JAVASCRIPT
         ======================================== --}}
    <script>
    (function () {
        var images = @json($allImages->values());
        var imageBaseUrl = '{{ asset('storage') }}/';
        window._mobileSlide = 0;

        // ── Lightbox ──────────────────────────────────
        window.LugarLightbox = (function () {
            var current = 0;
            var lb = document.getElementById('lightbox');
            var lbImg = document.getElementById('lightbox-image');
            var lbCounter = document.getElementById('lightbox-counter');

            function syncThumbs(index) {
                document.querySelectorAll('[data-lb-thumb]').forEach(function (btn) {
                    var i = parseInt(btn.getAttribute('data-lb-thumb'), 10);
                    if (i === index) {
                        btn.classList.add('border-white', 'opacity-100');
                        btn.classList.remove('border-transparent', 'opacity-50', 'hover:opacity-80');
                    } else {
                        btn.classList.remove('border-white', 'opacity-100');
                        btn.classList.add('border-transparent', 'opacity-50', 'hover:opacity-80');
                    }
                });
            }

            return {
                show: function (index) {
                    current = ((index % images.length) + images.length) % images.length;
                    lbImg.src = imageBaseUrl + images[current];
                    if (lbCounter) lbCounter.textContent = (current + 1) + ' / ' + images.length;
                    syncThumbs(current);
                },
                open: function (index) {
                    this.show(index);
                    lb.classList.remove('hidden');
                    lb.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                },
                close: function () {
                    lb.classList.add('hidden');
                    lb.classList.remove('flex');
                    document.body.style.overflow = '';
                },
                prev: function () { this.show(current - 1); },
                next: function () { this.show(current + 1); },
                backdropClick: function (e) {
                    if (e.target === lb) this.close();
                }
            };
        })();

        // Keyboard navigation
        document.addEventListener('keydown', function (e) {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === 'Escape') LugarLightbox.close();
            if (e.key === 'ArrowLeft') LugarLightbox.prev();
            if (e.key === 'ArrowRight') LugarLightbox.next();
        });

        // ── Mobile swipe gallery ──────────────────────
        (function () {
            var track = document.getElementById('mobile-gallery-track');
            if (!track || images.length <= 1) return;
            var total = images.length;
            var current = 0;
            var startX = 0;

            function gotoSlide(index) {
                current = Math.max(0, Math.min(index, total - 1));
                window._mobileSlide = current;
                var pct = current * (100 / total);
                track.style.transform = 'translateX(-' + pct + '%)';
                var counter = document.getElementById('mobile-gallery-counter');
                if (counter) counter.textContent = current + 1;
                document.querySelectorAll('.mobile-dot').forEach(function (dot, i) {
                    if (i === current) {
                        dot.classList.remove('bg-white/50', 'w-1.5');
                        dot.classList.add('bg-white', 'w-4');
                    } else {
                        dot.classList.remove('bg-white', 'w-4');
                        dot.classList.add('bg-white/50', 'w-1.5');
                    }
                });
            }

            window.mobileGotoSlide = gotoSlide;

            track.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
            }, { passive: true });

            track.addEventListener('touchend', function (e) {
                var diff = startX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 45) {
                    gotoSlide(diff > 0 ? current + 1 : current - 1);
                }
            }, { passive: true });
        })();

        // ── Collapsible Description (mobile) ─────────
        window.toggleDescription = function () {
            var container = document.getElementById('description-container');
            var fade = document.getElementById('description-fade');
            var text = document.getElementById('description-toggle-text');
            var icon = document.getElementById('description-toggle-icon');
            if (!container) return;
            if (container.style.maxHeight === 'none') {
                container.style.maxHeight = '160px';
                if (fade) fade.style.display = '';
                if (text) text.textContent = 'Leer más';
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                container.style.maxHeight = 'none';
                if (fade) fade.style.display = 'none';
                if (text) text.textContent = 'Leer menos';
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        };


})();
    </script>
@endsection
