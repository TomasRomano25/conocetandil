@extends('layouts.app')
@section('title', 'Planes Premium — Conoce Tandil')

@section('content')

{{-- Hero --}}
<section class="bg-[#1A1A1A] text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/60 to-[#1A1A1A]"></div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <span class="inline-flex items-center gap-1.5 bg-white/10 text-amber-300 text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 mb-6 tracking-widest uppercase">
            ✦ Premium
        </span>
        <h1 class="text-3xl md:text-5xl font-bold mb-5 leading-tight tracking-tight">
            Elegí tu plan y<br>empezá a explorar Tandil.
        </h1>
        <p class="text-gray-300 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
            Acceso completo al planificador de itinerarios, curado por días, tipo de experiencia y mucho más.
        </p>
    </div>
</section>

{{-- Plans --}}
<section class="py-16 bg-gray-50">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    @if ($plans->isEmpty())
        <div class="text-center py-20 text-gray-400">No hay planes disponibles en este momento.</div>
    @else

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $plans->count() <= 3 ? $plans->count() : '4' }} gap-4 lg:gap-5 items-stretch">
        @foreach ($plans as $plan)
        @php
            $popular = $plan->is_popular;
            $onSale  = $plan->hasSale();
            $pct     = $onSale ? round((1 - $plan->effective_price / (float) $plan->price) * 100) : 0;
        @endphp

        <div class="relative flex flex-col rounded-2xl overflow-hidden
            {{ $popular
                ? 'bg-[#1A1A1A] text-white shadow-2xl ring-1 ring-white/10'
                : 'bg-white text-[#1A1A1A] shadow-sm border border-gray-200 hover:shadow-md transition-shadow' }}">

            {{-- Ribbon: ocupa espacio fijo en todas las cards --}}
            <div class="h-8 flex items-center justify-center shrink-0">
                @if ($popular)
                    <span class="flex items-center gap-1.5 bg-[#52B788] text-white text-[10px] font-extrabold px-3 py-1 rounded-full tracking-widest uppercase">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Más elegido
                    </span>
                @elseif ($onSale)
                    <span class="flex items-center gap-1 bg-amber-500 text-white text-[10px] font-extrabold px-3 py-1 rounded-full tracking-widest uppercase">
                        −{{ $pct }}% OFF
                        @if ($plan->sale_label) · {{ $plan->sale_label }}@endif
                    </span>
                @endif
            </div>

            <div class="flex flex-col flex-1 px-6 pb-7">

                {{-- Nombre --}}
                <h3 class="text-lg font-bold mb-1 {{ $popular ? 'text-white' : 'text-[#1A1A1A]' }}">
                    {{ $plan->name }}
                </h3>

                {{-- Descripción: altura fija --}}
                <div class="h-9 mb-5">
                    @if ($plan->description)
                        <p class="text-xs leading-relaxed line-clamp-2 {{ $popular ? 'text-gray-400' : 'text-gray-500' }}">
                            {{ $plan->description }}
                        </p>
                    @endif
                </div>

                {{-- Divisor --}}
                <div class="border-t {{ $popular ? 'border-white/10' : 'border-gray-100' }} mb-5"></div>

                {{-- Precio --}}
                <div class="mb-1">
                    @if ($onSale)
                        <div class="flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl font-extrabold tracking-tight {{ $popular ? 'text-white' : 'text-[#2D6A4F]' }}">
                                {{ $plan->formattedEffectivePrice() }}
                            </span>
                            <s class="text-sm {{ $popular ? 'text-gray-500' : 'text-gray-400' }}">{{ $plan->formattedPrice() }}</s>
                        </div>
                    @else
                        <span class="text-3xl font-extrabold tracking-tight {{ $popular ? 'text-white' : 'text-[#2D6A4F]' }}">
                            {{ $plan->formattedEffectivePrice() }}
                        </span>
                    @endif
                </div>

                {{-- Duración + precio por mes: altura fija --}}
                <div class="h-9 mb-6">
                    <p class="text-xs {{ $popular ? 'text-gray-400' : 'text-gray-400' }}">/ {{ $plan->durationLabel() }}</p>
                    @if ($plan->duration_months > 1 && $plan->duration_unit === 'months')
                        <p class="text-xs font-semibold mt-0.5 {{ $popular ? 'text-[#52B788]' : 'text-[#52B788]' }}">
                            ≈ ${{ number_format($plan->effective_price / $plan->duration_months, 0, ',', '.') }} por mes
                        </p>
                    @endif
                </div>

                {{-- Features: flex-1 empuja el botón siempre al fondo --}}
                <ul class="space-y-2.5 flex-1 mb-7">
                    @forelse ($plan->features ?? [] as $feature)
                    <li class="flex items-start gap-2.5 text-sm {{ $popular ? 'text-gray-300' : 'text-gray-600' }}">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 {{ $popular ? 'text-[#52B788]' : 'text-[#52B788]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @empty
                    @endforelse
                </ul>

                {{-- CTA --}}
                <a href="{{ route('membership.checkout', $plan->slug) }}"
                    class="block text-center font-bold py-3 rounded-xl text-sm transition-all duration-200
                        {{ $popular
                            ? 'bg-[#52B788] hover:bg-[#2D6A4F] text-white'
                            : 'bg-[#1A1A1A] hover:bg-[#2D6A4F] text-white' }}">
                    Suscribirme
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @endif

    {{-- Footer note --}}
    <div class="mt-12 text-center space-y-1">
        <p class="text-sm text-gray-400">💳 Pago por transferencia bancaria. Tu acceso se activa dentro de las 24 hs.</p>
        <p class="text-sm text-gray-400">¿Dudas? <a href="{{ route('contacto') }}" class="text-[#2D6A4F] hover:underline font-medium">Contactanos</a></p>
    </div>

</div>
</section>

@endsection
