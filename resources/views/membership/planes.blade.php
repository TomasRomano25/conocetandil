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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($plans as $plan)
        @php $popular = $plan->duration_months === 6; @endphp
        <div class="relative flex flex-col bg-white rounded-2xl border-2 {{ $popular ? 'border-[#2D6A4F] shadow-xl' : 'border-gray-200 shadow-sm' }} overflow-hidden">
            @if ($popular)
                <div class="absolute top-0 inset-x-0 bg-[#2D6A4F] text-white text-xs font-bold text-center py-1.5 tracking-widest uppercase">
                    Más elegido
                </div>
            @endif

            <div class="p-6 {{ $popular ? 'pt-9' : '' }} flex flex-col flex-1">
                <h3 class="text-lg font-bold text-[#1A1A1A] mb-1">{{ $plan->name }}</h3>
                <p class="text-xs text-gray-400 mb-4">{{ $plan->description }}</p>

                <div class="mb-5">
                    <span class="text-3xl font-bold text-[#2D6A4F]">{{ $plan->formattedPrice() }}</span>
                    <span class="text-sm text-gray-400 ml-1">/ {{ $plan->durationLabel() }}</span>
                    @if ($plan->duration_months > 1)
                    <p class="text-xs text-gray-400 mt-0.5">
                        ≈ ${{ number_format($plan->price / $plan->duration_months, 0, ',', '.') }} por mes
                    </p>
                    @endif
                </div>

                @if ($plan->features)
                <ul class="space-y-2 mb-6 flex-1">
                    @foreach ($plan->features as $feature)
                    <li class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-[#2D6A4F] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif

                @auth
                    <a href="{{ route('membership.checkout', $plan->slug) }}"
                        class="block text-center font-bold py-3 rounded-xl transition text-sm
                            {{ $popular
                                ? 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white'
                                : 'bg-gray-100 hover:bg-[#2D6A4F] hover:text-white text-[#1A1A1A]' }}">
                        Suscribirme
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-center font-bold py-3 rounded-xl transition text-sm
                            {{ $popular
                                ? 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white'
                                : 'bg-gray-100 hover:bg-[#2D6A4F] hover:text-white text-[#1A1A1A]' }}">
                        Iniciar sesión para suscribirme
                    </a>
                @endauth
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
