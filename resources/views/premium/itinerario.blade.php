@extends('layouts.app')
@section('title', $itinerary->title . ' — Premium')

@section('content')

<section class="min-h-screen bg-gray-50 pb-16">

    {{-- Cover / Hero --}}
    <div class="relative bg-[#1A1A1A] text-white overflow-hidden">
        @if ($itinerary->cover_image)
            <img src="{{ asset('storage/' . $itinerary->cover_image) }}"
                class="absolute inset-0 w-full h-full object-cover opacity-40">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
        @endif
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 py-14">
            <a href="{{ route('premium.resultados') }}"
                class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a resultados
            </a>

            <div class="flex flex-wrap gap-2 mb-4">
                <span class="text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 px-3 py-1 rounded-full">✦ Premium</span>
                <span class="text-xs font-semibold bg-white/10 text-white/80 px-3 py-1 rounded-full">
                    {{ $itinerary->days_min === $itinerary->days_max
                        ? $itinerary->days_min . ' día(s)'
                        : "{$itinerary->days_min}–{$itinerary->days_max} días" }}
                </span>
                @if ($itinerary->requires_car)
                    <span class="text-xs font-semibold bg-white/10 text-white/80 px-3 py-1 rounded-full">🚗 Requiere auto</span>
                @endif
                @if ($itinerary->kid_friendly)
                    <span class="text-xs font-semibold bg-white/10 text-white/80 px-3 py-1 rounded-full">👨‍👧 Apto niños</span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-3 leading-tight">{{ $itinerary->title }}</h1>

            @if ($itinerary->description)
                <p class="text-white/75 text-lg max-w-2xl">{{ $itinerary->description }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-8 space-y-6">

        {{-- Intro tip --}}
        @if ($itinerary->intro_tip)
        <div class="bg-[#2D6A4F]/10 border border-[#2D6A4F]/20 rounded-2xl px-6 py-4 flex gap-4">
            <span class="text-2xl flex-shrink-0 mt-0.5">💡</span>
            <div>
                <p class="text-sm font-bold text-[#2D6A4F] mb-0.5">Consejo editorial</p>
                <p class="text-sm text-[#1A1A1A]">{{ $itinerary->intro_tip }}</p>
            </div>
        </div>
        @endif

        {{-- Day-by-day timeline --}}
        @foreach ($byDay as $day => $items)
        <div>
            {{-- Day header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-[#2D6A4F] flex items-center justify-center text-white font-bold text-sm">
                    D{{ $day }}
                </div>
                <h2 class="text-lg font-bold text-[#1A1A1A]">Día {{ $day }}</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Group items by time block --}}
            @php
                $blocks = $items->groupBy('time_block');
                $blockOrder = ['morning', 'lunch', 'afternoon', 'evening', 'flexible'];
            @endphp

            <div class="space-y-3">
                @foreach ($blockOrder as $blockKey)
                    @if ($blocks->has($blockKey))
                        {{-- Time block label --}}
                        <div class="flex items-center gap-2 mt-5 mb-2">
                            <span class="text-lg">{{ \App\Models\Itinerary::timeBlockIcon($blockKey) }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                {{ \App\Models\Itinerary::timeBlockLabel($blockKey) }}
                            </span>
                        </div>

                        @foreach ($blocks[$blockKey] as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            {{-- Item header --}}
                            <div class="flex">
                                {{-- Place image --}}
                                @if ($item->lugar && $item->lugar->images->isNotEmpty())
                                <div class="w-24 sm:w-36 flex-shrink-0 overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->lugar->images->first()->path) }}"
                                        class="w-full h-full object-cover">
                                </div>
                                @endif

                                {{-- Main content --}}
                                <div class="flex-1 p-5 min-w-0">
                                    <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                        <h3 class="font-bold text-[#1A1A1A] text-base leading-tight">
                                            {{ $item->displayTitle() }}
                                        </h3>
                                        <div class="flex flex-wrap gap-1.5 flex-shrink-0">
                                            @if ($item->formattedDuration())
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                                    ⏱ {{ $item->formattedDuration() }}
                                                </span>
                                            @endif
                                            @if ($item->estimated_cost)
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">
                                                    💲 {{ $item->estimated_cost }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($item->lugar)
                                        <p class="text-xs text-gray-400 mb-2">
                                            📍 {{ $item->lugar->direction ?? $item->lugar->title }}
                                        </p>
                                    @endif

                                    {{-- Why this order --}}
                                    @if ($item->why_order)
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $item->why_order }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Contextual cards --}}
                            @php
                                $extras = array_filter([
                                    'contextual_notes' => $item->contextual_notes,
                                    'skip_if'          => $item->skip_if,
                                    'why_worth_it'     => $item->why_worth_it,
                                ]);
                            @endphp

                            @if (count($extras) > 0 || $item->lugar)
                            <div class="border-t border-gray-100 px-5 py-4 space-y-3 bg-gray-50/50">

                                @if ($item->contextual_notes)
                                <div class="flex gap-3">
                                    <span class="text-base flex-shrink-0 mt-0.5">⚠️</span>
                                    <div>
                                        <p class="text-xs font-bold text-amber-700 mb-0.5">Tené en cuenta</p>
                                        <p class="text-sm text-gray-600">{{ $item->contextual_notes }}</p>
                                    </div>
                                </div>
                                @endif

                                @if ($item->skip_if)
                                <div class="flex gap-3">
                                    <span class="text-base flex-shrink-0 mt-0.5">⏭️</span>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 mb-0.5">Saltá esto si…</p>
                                        <p class="text-sm text-gray-600">{{ $item->skip_if }}</p>
                                    </div>
                                </div>
                                @endif

                                @if ($item->why_worth_it)
                                <div class="flex gap-3">
                                    <span class="text-base flex-shrink-0 mt-0.5">⭐</span>
                                    <div>
                                        <p class="text-xs font-bold text-[#2D6A4F] mb-0.5">Vale la pena porque…</p>
                                        <p class="text-sm text-gray-600">{{ $item->why_worth_it }}</p>
                                    </div>
                                </div>
                                @endif

                                @if ($item->lugar)
                                <div class="pt-1">
                                    <a href="{{ $item->lugar->google_maps_url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2D6A4F] hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Ver en Google Maps
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Footer CTA --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center mt-8">
            <p class="text-2xl mb-3">🗺️</p>
            <p class="font-bold text-[#1A1A1A] mb-1">¿Querés ver otros itinerarios?</p>
            <p class="text-sm text-gray-500 mb-5">Ajustá los filtros y encontrá el plan perfecto para tu viaje.</p>
            <a href="{{ route('premium.planner') }}"
                class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition">
                Volver al planificador
            </a>
        </div>

    </div>
</section>

@endsection
