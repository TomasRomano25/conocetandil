@extends('layouts.app')

@section('title', 'Contacto - Conoce Tandil')

@section('content')
    {{-- Header --}}
    @php $contactoImage = $contactoBanner->image ?? null; @endphp
    <section class="relative text-white py-20 overflow-hidden {{ $contactoImage ? '' : 'bg-[#2D6A4F]' }}">
        @if ($contactoImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $contactoImage) }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F]/75 to-[#1A1A1A]/80"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F] to-[#1A1A1A] opacity-90"></div>
        @endif
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ $contactoBanner->title ?? 'Contacto' }}</h1>
            <p class="text-gray-200 max-w-xl mx-auto">{{ $contactoBanner->subtitle ?? '¿Tenés alguna consulta? Escribinos y te respondemos a la brevedad.' }}</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                {{-- Contact Form --}}
                <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Envianos un mensaje</h2>

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($form)
                        <form action="{{ route('formulario.submit', $form->slug) }}" method="POST" class="space-y-5">
                            @csrf

                            @foreach ($form->visibleFields as $field)
                                <div>
                                    <label for="field_{{ $field->name }}"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $field->label }}
                                        @if ($field->required)<span class="text-red-500">*</span>@endif
                                    </label>

                                    @if ($field->type === 'textarea')
                                        <textarea
                                            id="field_{{ $field->name }}"
                                            name="{{ $field->name }}"
                                            rows="5"
                                            {{ $field->required ? 'required' : '' }}
                                            placeholder="{{ $field->placeholder }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] @error($field->name) border-red-400 @enderror"
                                        >{{ old($field->name) }}</textarea>
                                    @elseif ($field->type === 'select' && $field->options)
                                        <select
                                            id="field_{{ $field->name }}"
                                            name="{{ $field->name }}"
                                            {{ $field->required ? 'required' : '' }}
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white @error($field->name) border-red-400 @enderror"
                                        >
                                            <option value="">{{ $field->placeholder ?: 'Seleccioná una opción' }}</option>
                                            @foreach ($field->options as $option)
                                                <option value="{{ $option }}" @selected(old($field->name) === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input
                                            type="{{ $field->type }}"
                                            id="field_{{ $field->name }}"
                                            name="{{ $field->name }}"
                                            value="{{ old($field->name) }}"
                                            {{ $field->required ? 'required' : '' }}
                                            placeholder="{{ $field->placeholder }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#52B788] @error($field->name) border-red-400 @enderror"
                                        >
                                    @endif

                                    @error($field->name)
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach

                            <button type="submit"
                                class="w-full bg-[#2D6A4F] hover:bg-[#52B788] text-white font-bold py-3 rounded-lg transition">
                                Enviar Mensaje
                            </button>
                        </form>
                    @else
                        <p class="text-gray-500">El formulario de contacto no está disponible en este momento.</p>
                    @endif
                </div>

                {{-- Company Info & Map --}}
                <div class="space-y-8">
                    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Información de Contacto</h2>
                        <div class="space-y-4">
                            @if ($contactInfo['address'])
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Dirección</p>
                                    <p class="text-gray-600 text-sm">{{ $contactInfo['address'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['phone'])
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Teléfono</p>
                                    <p class="text-gray-600 text-sm">{{ $contactInfo['phone'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['email'])
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Email</p>
                                    <p class="text-gray-600 text-sm">{{ $contactInfo['email'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['hours'])
                            <div class="flex items-start space-x-4">
                                <svg class="w-6 h-6 text-[#2D6A4F] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-[#1A1A1A]">Horarios</p>
                                    <p class="text-gray-600 text-sm">{{ $contactInfo['hours'] }}</p>
                                </div>
                            </div>
                            @endif
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
