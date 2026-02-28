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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-start">
        @foreach ($plans as $plan)
        @php
            $popular = $plan->is_popular;
            $onSale  = $plan->hasSale();
        @endphp
        <div class="relative flex flex-col bg-white rounded-2xl border-2 {{ $popular ? 'border-[#2D6A4F] shadow-2xl scale-[1.02]' : 'border-gray-200 shadow-sm' }} overflow-hidden transition-transform">

            {{-- Badges top bar --}}
            @if ($popular)
                <div class="bg-[#2D6A4F] text-white text-xs font-bold text-center py-1.5 tracking-widest uppercase">
                    ★ Más elegido
                </div>
            @elseif ($onSale)
                <div class="bg-amber-500 text-white text-xs font-bold text-center py-1.5 tracking-widest uppercase">
                    🏷 {{ $plan->sale_label ?? 'Oferta especial' }}
                </div>
            @endif

            <div class="p-6 flex flex-col flex-1">

                <h3 class="text-lg font-bold text-[#1A1A1A] mb-1">{{ $plan->name }}</h3>
                @if ($plan->description)
                    <p class="text-xs text-gray-400 mb-4 leading-relaxed">{{ $plan->description }}</p>
                @endif

                {{-- Pricing block --}}
                <div class="mb-5">
                    @if ($onSale)
                        {{-- Discount badge --}}
                        @php
                            $pct = round((1 - $plan->effective_price / (float)$plan->price) * 100);
                        @endphp
                        <div class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full mb-2">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293l-7-7A1 1 0 0010 2H4a2 2 0 00-2 2v6a1 1 0 00.293.707l7 7a1 1 0 001.414 0l7-7a1 1 0 000-1.414zM6 7a1 1 0 110-2 1 1 0 010 2z" clip-rule="evenodd"/></svg>
                            −{{ $pct }}% OFF
                            @if ($plan->sale_label) · {{ $plan->sale_label }}@endif
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                            <s class="text-gray-400 text-sm line-through">{{ $plan->formattedPrice() }}</s>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">/ {{ $plan->durationLabel() }}</p>
                        @if ($plan->duration_months > 1 && $plan->duration_unit === 'months')
                            <p class="text-xs text-[#52B788] font-semibold mt-1">
                                ≈ ${{ number_format($plan->effective_price / $plan->duration_months, 0, ',', '.') }} por mes
                            </p>
                        @endif
                    @else
                        <span class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedEffectivePrice() }}</span>
                        <span class="text-sm text-gray-400 ml-1">/ {{ $plan->durationLabel() }}</span>
                        @if ($plan->duration_months > 1 && $plan->duration_unit === 'months')
                            <p class="text-xs text-gray-400 mt-0.5">
                                ≈ ${{ number_format($plan->effective_price / $plan->duration_months, 0, ',', '.') }} por mes
                            </p>
                        @endif
                    @endif
                </div>

                @if ($plan->features)
                <ul class="space-y-2 mb-6 flex-1">
                    @foreach ($plan->features as $feature)
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 {{ $popular ? 'text-[#2D6A4F]' : 'text-gray-400' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @else
                    <div class="flex-1"></div>
                @endif

                <a href="{{ route('membership.checkout', $plan->slug) }}"
                    class="block text-center font-bold py-3 rounded-xl transition text-sm mt-auto
                        {{ $popular
                            ? 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white'
                            : ($onSale
                                ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                : 'bg-gray-100 hover:bg-[#2D6A4F] hover:text-white text-[#1A1A1A]') }}">
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
