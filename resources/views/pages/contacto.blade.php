@extends('layouts.app')

@section('title', 'Contacto - Conoce Tandil')

@section('content')
    {{-- Header --}}
    <section class="bg-[#2D6A4F] text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Contacto</h1>
            <p class="text-gray-200 max-w-xl mx-auto">¿Tenés alguna consulta? Escribinos y te respondemos a la brevedad.</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                {{-- Contact Form --}}
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Envianos un mensaje</h2>
                    <form action="#" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" id="name" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788]" placeholder="Tu nombre completo">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788]" placeholder="tu@email.com">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="tel" id="phone" name="phone" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788]" placeholder="(249) 444-0000">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                            <textarea id="message" name="message" rows="5" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788]" placeholder="Escribí tu consulta..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#52B788] text-white font-bold py-3 rounded-lg transition">
                            Enviar Mensaje
                        </button>
                    </form>
                </div>

                {{-- Company Info & Map --}}
                <div class="space-y-8">
                    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Información de Contacto</h2>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Dirección</p>
                                    <p class="text-gray-600 text-sm">9 de Julio 555, Tandil, Buenos Aires, Argentina</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Teléfono</p>
                                    <p class="text-gray-600 text-sm">(0249) 444-1234</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Email</p>
                                    <p class="text-gray-600 text-sm">info@conocetandil.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Map Placeholder --}}
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                        <div class="h-64 bg-gradient-to-br from-[#2D6A4F]/10 to-[#52B788]/10 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-[#2D6A4F]/40 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <p class="text-[#2D6A4F]/60 font-medium">Mapa de Tandil</p>
                                <p class="text-gray-400 text-sm">Se integrará Google Maps próximamente</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
