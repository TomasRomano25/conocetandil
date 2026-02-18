@extends('layouts.app')
@section('title', $hotel->name . ' — Hoteles Tandil')

@section('content')

@php $tier = $hotel->plan->tier; @endphp

{{-- Hero / Cover --}}
<section class="relative bg-[#1A1A1A] text-white overflow-hidden">
    @if ($hotel->cover_image)
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url('{{ asset('storage/' . $hotel->cover_image) }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#1A1A1A]/90 via-[#1A1A1A]/40 to-transparent"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
    @endif
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <a href="{{ route('hoteles.index') }}" class="inline-flex items-center gap-1.5 text-white/60 hover:text-white text-sm mb-6 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Hoteles
        </a>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                @if ($hotel->hotel_type)
                    <span class="inline-block bg-black/30 text-white/80 text-xs font-semibold px-3 py-1 rounded-full border border-white/20 mb-3">
                        {{ $hotel->hotel_type }}
                    </span>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold">{{ $hotel->name }}</h1>
                @if ($hotel->stars)
                    <div class="flex items-center gap-1 mt-2">
                        @for ($s = 1; $s <= 5; $s++)
                            <svg class="w-4 h-4 {{ $s <= $hotel->stars ? 'text-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                        <span class="text-white/60 text-sm ml-1">{{ $hotel->stars }} estrellas</span>
                    </div>
                @endif
                <p class="text-white/70 mt-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $hotel->address }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Main content —— adapts by tier --}}
@if ($tier >= 3)
    {{-- DIAMANTE: tabbed layout --}}
    @php
    $tabs = [
        'descripcion'  => 'Descripción',
        'galeria'      => 'Galería',
        'habitaciones' => 'Habitaciones',
        'servicios'    => 'Servicios',
        'contacto'     => 'Contacto',
    ];
    $tabIcons = [
        'descripcion'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'galeria'      => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
        'habitaciones' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'servicios'    => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'contacto'     => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    ];
    @endphp

    <section class="bg-white border-b sticky top-0 z-20 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mobile: all pills visible, wrap to 2 rows — nothing hidden --}}
            <div class="sm:hidden py-3 flex flex-wrap gap-2">
                @foreach($tabs as $tab => $label)
                <button onclick="showTab('{{ $tab }}')" id="mob-tab-btn-{{ $tab }}"
                    class="mob-tab-btn flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border transition
                        {{ $tab === 'descripcion'
                            ? 'bg-[#2D6A4F] text-white border-[#2D6A4F]'
                            : 'bg-gray-50 text-gray-500 border-gray-200 hover:border-[#2D6A4F] hover:text-[#2D6A4F]' }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcons[$tab] }}"/>
                    </svg>
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Desktop: classic underline tab nav --}}
            <nav class="hidden sm:flex" id="hotel-tabs-nav">
                @foreach($tabs as $tab => $label)
                <button onclick="showTab('{{ $tab }}')" id="tab-btn-{{ $tab }}"
                    class="tab-btn px-5 py-4 text-sm font-semibold border-b-2 transition
                        {{ $tab === 'descripcion' ? 'border-[#2D6A4F] text-[#2D6A4F]' : 'border-transparent text-gray-500 hover:text-[#2D6A4F]' }}">
                    {{ $label }}
                </button>
                @endforeach
            </nav>

        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Tab: Descripción --}}
        <div id="tab-descripcion" class="tab-content">
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! nl2br(e($hotel->description)) !!}
            </div>
            @if ($hotel->checkin_time || $hotel->checkout_time)
            <div class="mt-8">
                <div class="bg-gray-50 rounded-xl p-5 inline-block">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Check-in / Check-out</p>
                    @if ($hotel->checkin_time)
                        <p class="text-sm text-gray-700"><span class="font-semibold">Check-in:</span> {{ $hotel->checkin_time }}</p>
                    @endif
                    @if ($hotel->checkout_time)
                        <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Check-out:</span> {{ $hotel->checkout_time }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Tab: Galería --}}
        <div id="tab-galeria" class="tab-content hidden">
            @if ($hotel->images->count())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($hotel->images as $image)
                    <div class="group">
                        <div class="aspect-video rounded-xl overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->caption ?? $hotel->name }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                        @if ($image->caption)
                            <p class="text-sm text-gray-600 mt-2 text-center">{{ $image->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-12">No hay imágenes en la galería.</p>
            @endif
        </div>

        {{-- Tab: Habitaciones --}}
        <div id="tab-habitaciones" class="tab-content hidden">
            @if ($hotel->rooms->count())
                <div class="space-y-6">
                    @foreach ($hotel->rooms as $room)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm flex flex-col md:flex-row">
                        @if ($room->image)
                            <div class="md:w-48 h-40 md:h-auto flex-shrink-0">
                                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-5 flex-1">
                            <div class="flex items-start justify-between gap-3 flex-wrap">
                                <h3 class="font-bold text-[#1A1A1A] text-lg">{{ $room->name }}</h3>
                                @if ($room->price_per_night)
                                    <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-sm font-bold px-3 py-1 rounded-full">
                                        {{ $room->formattedPrice() }}
                                    </span>
                                @endif
                            </div>
                            @if ($room->capacity)
                                <p class="text-sm text-gray-500 mt-1">Capacidad: {{ $room->capacity }} {{ $room->capacity === 1 ? 'persona' : 'personas' }}</p>
                            @endif
                            @if ($room->description)
                                <p class="text-gray-600 text-sm mt-3">{{ $room->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-12">No hay habitaciones registradas.</p>
            @endif
        </div>

        {{-- Tab: Servicios --}}
        <div id="tab-servicios" class="tab-content hidden">
            @if ($hotel->services && count($hotel->services))
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($hotel->services as $service)
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                        <svg class="w-5 h-5 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ $service }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-12">No hay servicios registrados.</p>
            @endif
        </div>

        {{-- Tab: Contacto --}}
        <div id="tab-contacto" class="tab-content hidden">
            @include('hoteles._contact_form')
        </div>
    </div>

    <script>
    function showTab(tab) {
        // Hide all content panels
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        // Reset desktop underline tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[#2D6A4F]', 'text-[#2D6A4F]');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        const desktopBtn = document.getElementById('tab-btn-' + tab);
        if (desktopBtn) {
            desktopBtn.classList.add('border-[#2D6A4F]', 'text-[#2D6A4F]');
            desktopBtn.classList.remove('border-transparent', 'text-gray-500');
        }

        // Reset mobile pill tabs
        document.querySelectorAll('.mob-tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#2D6A4F]', 'text-white', 'border-[#2D6A4F]');
            btn.classList.add('bg-gray-50', 'text-gray-500', 'border-gray-200');
        });
        const mobBtn = document.getElementById('mob-tab-btn-' + tab);
        if (mobBtn) {
            mobBtn.classList.add('bg-[#2D6A4F]', 'text-white', 'border-[#2D6A4F]');
            mobBtn.classList.remove('bg-gray-50', 'text-gray-500', 'border-gray-200');
        }

        // Show selected panel
        document.getElementById('tab-' + tab).classList.remove('hidden');
    }
    </script>

@elseif ($tier === 2)
    {{-- ESTÁNDAR: gallery + services + contact --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        {{-- Description --}}
        <section>
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! nl2br(e($hotel->description)) !!}
            </div>
        </section>

        {{-- Info row --}}
        @if ($hotel->checkin_time || $hotel->checkout_time)
        <section>
            <div class="bg-gray-50 rounded-xl p-5 inline-block">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Horarios</p>
                @if ($hotel->checkin_time)
                    <p class="text-sm text-gray-700"><span class="font-semibold">Check-in:</span> {{ $hotel->checkin_time }}</p>
                @endif
                @if ($hotel->checkout_time)
                    <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Check-out:</span> {{ $hotel->checkout_time }}</p>
                @endif
            </div>
        </section>
        @endif

        {{-- Services --}}
        @if ($hotel->services && count($hotel->services))
        <section>
            <h2 class="text-xl font-bold text-[#1A1A1A] mb-4">Servicios</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($hotel->services as $service)
                <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-medium text-gray-700">{{ $service }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Gallery --}}
        @if ($hotel->images->count())
        <section>
            <h2 class="text-xl font-bold text-[#1A1A1A] mb-4">Galería</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($hotel->images as $image)
                <div class="aspect-video rounded-xl overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $hotel->name }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Contact form --}}
        <section>
            <h2 class="text-xl font-bold text-[#1A1A1A] mb-6">Contactar al hotel</h2>
            @include('hoteles._contact_form')
        </section>

    </div>

@else
    {{-- BÁSICO: cover + description + contact --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

        <section class="prose prose-lg max-w-none text-gray-700">
            {!! nl2br(e($hotel->description)) !!}
        </section>

        <section>
            <h2 class="text-xl font-bold text-[#1A1A1A] mb-6">Contactar al hotel</h2>
            @include('hoteles._contact_form')
        </section>

    </div>
@endif

@endsection
