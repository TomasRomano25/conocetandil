@extends('layouts.app')

@section('title', 'Contacto - Conoce Tandil')

@section('content')

    {{-- ═══ PAGE HEADER ═══ --}}
    @php $contactoImage = $contactoBanner->image ?? null; @endphp
    <section class="relative overflow-hidden flex items-end" style="background-color: #0F1F16; min-height: 38vh;">
        @if ($contactoImage)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ asset('storage/' . $contactoImage) }}')"></div>
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(15,31,22,0.35) 0%, rgba(15,31,22,0.85) 100%);"></div>
        @else
            <div class="absolute inset-0" style="background: radial-gradient(ellipse at 70% 30%, rgba(45,106,79,0.25) 0%, transparent 60%);"></div>
        @endif
        <div class="absolute -right-24 -top-24 w-[400px] h-[400px] rounded-full border border-white/[0.04] pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-14 pt-28">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-4 rounded-full bg-[#52B788]"></div>
                <span class="text-[#52B788] text-xs font-semibold uppercase tracking-[0.18em]">Estamos para ayudarte</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white leading-[0.95] tracking-tight mb-4">
                {{ $contactoBanner->title ?? 'Contacto' }}
            </h1>
            <p class="text-white/50 text-base md:text-lg max-w-xl leading-relaxed">
                {{ $contactoBanner->subtitle ?? '¿Tenés alguna consulta? Escribinos y te respondemos a la brevedad.' }}
            </p>
        </div>
    </section>

    {{-- ═══ CONTENT ═══ --}}
    <section class="py-16 md:py-20 bg-[#FAFAF8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">

                {{-- Contact Form — wider column --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                        <h2 class="text-xl font-bold text-[#111827] mb-6">Envianos un mensaje</h2>

                        @if (session('success'))
                            <div class="bg-[#2D6A4F]/8 border border-[#2D6A4F]/20 text-[#2D6A4F] px-4 py-3 rounded-xl mb-6 text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($form)
                            <form id="contact-form" action="{{ route('formulario.submit', $form->slug) }}" method="POST" class="space-y-5">
                                @csrf
                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-contact">

                                @foreach ($form->visibleFields as $field)
                                    <div>
                                        <label for="field_{{ $field->name }}"
                                            class="block text-xs font-semibold text-[#374151] uppercase tracking-[0.08em] mb-2">
                                            {{ $field->label }}
                                            @if ($field->required)<span class="text-red-400 ml-0.5">*</span>@endif
                                        </label>

                                        @if ($field->type === 'textarea')
                                            <textarea
                                                id="field_{{ $field->name }}"
                                                name="{{ $field->name }}"
                                                rows="5"
                                                {{ $field->required ? 'required' : '' }}
                                                placeholder="{{ $field->placeholder }}"
                                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition-all resize-none @error($field->name) border-red-300 @enderror"
                                            >{{ old($field->name) }}</textarea>
                                        @elseif ($field->type === 'select' && $field->options)
                                            <select
                                                id="field_{{ $field->name }}"
                                                name="{{ $field->name }}"
                                                {{ $field->required ? 'required' : '' }}
                                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] bg-white transition-all @error($field->name) border-red-300 @enderror"
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
                                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition-all @error($field->name) border-red-300 @enderror"
                                            >
                                        @endif

                                        @error($field->name)
                                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach

                                @error('captcha')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror

                                <button type="submit"
                                    class="w-full bg-[#111827] hover:bg-[#2D6A4F] text-white font-semibold py-3.5 rounded-xl transition-colors text-sm flex items-center justify-center gap-2 group">
                                    Enviar Mensaje
                                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </button>
                            </form>
                            @if(\App\Models\Configuration::get('recaptcha_site_key'))
                            <script>
                            document.getElementById('contact-form').addEventListener('submit', function(e) {
                                e.preventDefault();
                                var form = this;
                                grecaptcha.ready(function() {
                                    grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'contact'}).then(function(token) {
                                        document.getElementById('g-recaptcha-contact').value = token;
                                        form.submit();
                                    });
                                });
                            });
                            </script>
                            @endif
                        @else
                            <p class="text-[#9CA3AF] text-sm">El formulario de contacto no está disponible en este momento.</p>
                        @endif
                    </div>
                </div>

                {{-- Info sidebar --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Contact info card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7">
                        <h2 class="text-base font-bold text-[#111827] mb-5">Información de Contacto</h2>
                        <div class="space-y-4">
                            @if ($contactInfo['address'])
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/8 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#374151] uppercase tracking-[0.08em] mb-0.5">Dirección</p>
                                    <p class="text-[#6B7280] text-sm leading-relaxed">{{ $contactInfo['address'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['phone'])
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/8 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#374151] uppercase tracking-[0.08em] mb-0.5">Teléfono</p>
                                    <p class="text-[#6B7280] text-sm">{{ $contactInfo['phone'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['email'])
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/8 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#374151] uppercase tracking-[0.08em] mb-0.5">Email</p>
                                    <p class="text-[#6B7280] text-sm">{{ $contactInfo['email'] }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($contactInfo['hours'])
                            <div class="flex items-start gap-3.5">
                                <div class="w-9 h-9 rounded-xl bg-[#2D6A4F]/8 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-[#374151] uppercase tracking-[0.08em] mb-0.5">Horarios</p>
                                    <p class="text-[#6B7280] text-sm leading-relaxed">{{ $contactInfo['hours'] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Map placeholder --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="h-56 bg-gradient-to-br from-[#0F1F16] to-[#2D6A4F]/40 flex flex-col items-center justify-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-white/70 font-semibold text-sm">Tandil, Buenos Aires</p>
                                <p class="text-white/30 text-xs mt-1">Mapa disponible próximamente</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
