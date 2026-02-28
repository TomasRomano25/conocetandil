@extends('layouts.app')
@section('title', 'Planes Premium — Conoce Tandil')

@section('content')

{{-- Hero --}}
<section class="bg-[#1A1A1A] text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/70 to-[#1A1A1A]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <span class="inline-flex items-center gap-1.5 bg-amber-500/20 text-amber-300 text-xs font-bold px-4 py-1.5 rounded-full border border-amber-500/30 mb-5">
            ✦ PREMIUM
        </span>
        <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
            Elegí tu plan y<br class="hidden sm:block"> empezá a planificar mejor.
        </h1>
        <p class="text-gray-300 text-lg max-w-xl mx-auto">
            Acceso completo al planificador de itinerarios, curado por días, tipo de experiencia y mucho más.
        </p>
    </div>
</section>

{{-- Plans grid --}}
<section class="py-16 bg-gray-50">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    @if ($plans->isEmpty())
        <div class="text-center py-20 text-gray-500">No hay planes disponibles en este momento.</div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($plans as $plan)
        @php
            $popular = $plan->is_popular;
            $onSale  = $plan->hasSale();
            $pct     = $onSale ? round((1 - $plan->effective_price / (float)$plan->price) * 100) : 0;
        @endphp
        <div class="flex flex-col bg-white rounded-2xl overflow-hidden border-2 shadow-sm
            {{ $popular ? 'border-[#2D6A4F] shadow-lg' : 'border-gray-200' }}">

            {{-- Top ribbon: siempre presente, misma altura --}}
            <div class="h-7 flex items-center justify-center text-xs font-bold tracking-widest uppercase
                {{ $popular ? 'bg-[#2D6A4F] text-white' : ($onSale ? 'bg-amber-500 text-white' : 'bg-transparent') }}">
                @if ($popular) ★ Más elegido
                @elseif ($onSale) 🏷 {{ $plan->sale_label ?? 'Oferta especial' }}
                @endif
            </div>

            <div class="p-5 flex flex-col flex-1">

                {{-- Nombre y descripción: altura fija con clamp --}}
                <div class="mb-4 min-h-[3.5rem]">
                    <h3 class="font-bold text-[#1A1A1A] text-base leading-tight">{{ $plan->name }}</h3>
                    @if ($plan->description)
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed line-clamp-2">{{ $plan->description }}</p>
                    @endif
                </div>

                {{-- Precio: bloque de altura fija --}}
                <div class="mb-1">
                    @if ($onSale)
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                            <s class="text-gray-400 text-xs">{{ $plan->formattedPrice() }}</s>
                            <span class="bg-amber-100 text-amber-700 text-xs font-bold px-1.5 py-0.5 rounded-md">-{{ $pct }}%</span>
                        </div>
                    @else
                        <span class="text-2xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                    @endif
                </div>

                {{-- Duración: siempre presente --}}
                <p class="text-xs text-gray-400 mb-1">/ {{ $plan->durationLabel() }}</p>

                {{-- Precio por mes: reservar espacio siempre --}}
                <div class="h-4 mb-4">
                    @if ($plan->duration_months > 1 && $plan->duration_unit === 'months')
                        <p class="text-xs text-[#52B788] font-semibold">
                            ≈ ${{ number_format($plan->effective_price / $plan->duration_months, 0, ',', '.') }}/mes
                        </p>
                    @endif
                </div>

                {{-- Features: flex-1 para empujar el botón al fondo --}}
                <ul class="space-y-2 flex-1 mb-5">
                    @forelse ($plan->features ?? [] as $feature)
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 {{ $popular ? 'text-[#2D6A4F]' : 'text-gray-400' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @empty
                    @endforelse
                </ul>

                <a href="{{ route('membership.checkout', $plan->slug) }}"
                    class="block text-center font-bold py-2.5 rounded-xl transition text-sm
                        {{ $popular
                            ? 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white'
                            : 'border border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white' }}">
                    Suscribirme
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Guarantee note --}}
    <div class="mt-12 text-center text-sm text-gray-400">
        <p>💳 Pago por transferencia bancaria. Tu acceso se activa dentro de las 24 hs de confirmada la transferencia.</p>
        <p class="mt-1">¿Dudas?
            <a href="{{ route('contacto') }}" class="text-[#2D6A4F] hover:underline font-medium">Contactanos</a>
        </p>
    </div>
</div>
</section>

@endsection
