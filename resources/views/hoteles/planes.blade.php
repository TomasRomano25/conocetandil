@extends('layouts.app')
@section('title', 'Planes para hoteles — Conoce Tandil')

@section('content')

<section class="min-h-screen bg-gray-50 py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-[#1A1A1A]">Elegí el plan para tu hotel</h1>
            <p class="text-gray-500 mt-2">Seleccioná el plan que mejor se adapte a las necesidades de tu alojamiento.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
            @php $isDiamond = $plan->tier === 3; @endphp
            <div class="rounded-2xl border-2 p-7 relative bg-white {{ $isDiamond ? 'border-amber-400 shadow-xl' : 'border-gray-200' }}">
                @if ($isDiamond)
                    <span class="absolute -top-3 left-6 bg-amber-400 text-white text-xs font-bold px-3 py-1 rounded-full">✦ Más completo</span>
                @endif
                <p class="text-xs font-bold {{ $isDiamond ? 'text-amber-500' : 'text-[#2D6A4F]' }} uppercase tracking-widest mb-2">{{ $plan->tierLabel() }}</p>
                <p class="text-3xl font-bold text-[#1A1A1A] mb-1">{{ $plan->formattedPrice() }}</p>
                <p class="text-gray-400 text-sm mb-6">por año</p>

                @if ($plan->description)
                    <p class="text-gray-600 text-sm mb-5">{{ $plan->description }}</p>
                @endif

                <ul class="space-y-2.5 mb-8">
                    @php
                        $features = match($plan->tier) {
                            1 => ['Foto de portada', 'Descripción del hotel', 'Formulario de contacto'],
                            2 => ['Hasta 5 imágenes', 'Descripción + servicios', 'Estrellas y horarios', 'Formulario de contacto'],
                            3 => ['Galería de 20 imágenes con descripciones', 'Sección de habitaciones', 'Página con pestañas', 'Servicios detallados', 'Posición destacada'],
                            default => [],
                        };
                    @endphp
                    @foreach ($features as $f)
                    <li class="flex items-start gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 {{ $isDiamond ? 'text-amber-500' : 'text-[#2D6A4F]' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $f }}
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('hoteles.owner.create', $plan) }}"
                    class="block text-center font-bold py-3 px-4 rounded-xl transition {{ $isDiamond ? 'bg-amber-400 hover:bg-amber-500 text-white' : 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white' }}">
                    Elegir {{ $plan->name }}
                </a>
            </div>
            @endforeach
        </div>

        <p class="text-center text-sm text-gray-400 mt-8">
            ¿Tenés dudas? <a href="{{ route('contacto') }}" class="text-[#2D6A4F] hover:underline">Contactanos</a>
        </p>
    </div>
</section>

@endsection
