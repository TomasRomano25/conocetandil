@extends('layouts.app')
@section('title', 'Registrá tu Hotel — Conoce Tandil')

@section('content')

{{-- Hero --}}
<section class="relative bg-[#1A1A1A] text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/80 to-[#1A1A1A]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <span class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 text-xs font-bold px-4 py-1.5 rounded-full mb-6 border border-amber-500/30">
            🏨 PROPIETARIOS
        </span>
        <h1 class="text-4xl md:text-5xl font-bold mb-5 leading-tight">
            Sumá tu hotel al<br>
            <span class="text-[#52B788]">directorio de Tandil</span>
        </h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto mb-8">
            Miles de turistas buscan dónde alojarse en Tandil. Hacé que te encuentren fácil. Elegí el plan que mejor se adapta a tu hotel.
        </p>
        @auth
            <a href="{{ route('hoteles.owner.planes') }}"
                class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#52B788] text-white font-bold py-3.5 px-8 rounded-xl transition text-base">
                Registrar mi hotel
            </a>
        @else
            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#52B788] text-white font-bold py-3.5 px-8 rounded-xl transition text-base">
                    Crear cuenta y registrar hotel
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-3.5 px-8 rounded-xl transition text-base border border-white/20">
                    Ya tengo cuenta
                </a>
            </div>
        @endauth
    </div>
</section>

{{-- Plan comparison --}}
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-center text-[#1A1A1A] mb-3">Elegí el plan para tu hotel</h2>
        <p class="text-center text-gray-500 mb-10">Todos los planes tienen vigencia anual. Pagás por transferencia bancaria.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
            @php
                $isDiamond = $plan->tier === 3;
            @endphp
            <div class="rounded-2xl border-2 p-7 relative {{ $isDiamond ? 'border-amber-400 shadow-xl' : 'border-gray-200' }}">
                @if ($isDiamond)
                    <span class="absolute -top-3 left-6 bg-amber-400 text-white text-xs font-bold px-3 py-1 rounded-full">✦ Más completo</span>
                @endif

                <p class="text-xs font-bold {{ $isDiamond ? 'text-amber-500' : 'text-[#2D6A4F]' }} uppercase tracking-widest mb-2">{{ $plan->tierLabel() }}</p>
                <p class="text-2xl font-bold text-[#1A1A1A] mb-1">{{ $plan->formattedPrice() }}</p>
                <p class="text-gray-400 text-sm mb-5">por año</p>

                @if ($plan->description)
                    <p class="text-gray-600 text-sm mb-5">{{ $plan->description }}</p>
                @endif

                <ul class="space-y-2.5 mb-7">
                    @php
                        $features = match($plan->tier) {
                            1 => ['Foto de portada', 'Descripción del hotel', 'Formulario de contacto', 'Visible en el directorio'],
                            2 => ['Hasta 5 imágenes en galería', 'Descripción + servicios', 'Estrellas y horarios', 'Formulario de contacto'],
                            3 => ['Galería de 20 imágenes con descripciones', 'Sección de habitaciones', 'Página con pestañas', 'Servicios detallados', 'Posición destacada', 'Formulario de contacto'],
                            default => [],
                        };
                    @endphp
                    @foreach ($features as $feature)
                    <li class="flex items-start gap-2.5 text-sm text-gray-700">
                        <svg class="w-4 h-4 {{ $isDiamond ? 'text-amber-500' : 'text-[#2D6A4F]' }} flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @auth
                    <a href="{{ route('hoteles.owner.create', $plan) }}"
                        class="block text-center font-semibold py-2.5 px-4 rounded-xl transition text-sm {{ $isDiamond ? 'bg-amber-400 hover:bg-amber-500 text-white' : 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white' }}">
                        Elegir {{ $plan->name }}
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="block text-center font-semibold py-2.5 px-4 rounded-xl transition text-sm {{ $isDiamond ? 'bg-amber-400 hover:bg-amber-500 text-white' : 'bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white' }}">
                        Registrarme
                    </a>
                @endauth
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-[#1A1A1A] mb-3">Cómo funciona</h2>
        <p class="text-gray-500 mb-10">Tres pasos para estar en el directorio.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
            @foreach([
                ['1', '📋', 'Elegís tu plan', 'Seleccionás el plan que mejor se adapte a tu hotel y completás el formulario.'],
                ['2', '💳', 'Transferís el pago', 'Realizás la transferencia bancaria e indicás tu comprobante.'],
                ['3', '✅', 'Aparecés en el directorio', 'Revisamos tu hotel y lo publicamos en 24 horas.'],
            ] as [$num, $icon, $title, $desc])
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="w-10 h-10 bg-[#2D6A4F]/10 rounded-xl flex items-center justify-center text-xl mb-4">{{ $icon }}</div>
                <p class="text-xs font-bold text-gray-400 mb-1">Paso {{ $num }}</p>
                <p class="font-bold text-[#1A1A1A] mb-2">{{ $title }}</p>
                <p class="text-sm text-gray-500">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
