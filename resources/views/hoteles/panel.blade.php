@extends('layouts.app')
@section('title', 'Mi Hotel — Conoce Tandil')

@section('content')

<section class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex items-start justify-between flex-wrap gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#1A1A1A]">Panel de mi hotel</h1>
                <p class="text-gray-500 mt-1">Gestioná tu presencia en el directorio.</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('hoteles.owner.edit') }}"
                    class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar hotel
                </a>
                @if ($hotel->status === 'active')
                <a href="{{ route('hoteles.show', $hotel) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ver en directorio
                </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status banner --}}
        @if ($hotel->status === 'pending')
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Pendiente de revisión</p>
                    <p class="text-xs text-amber-700 mt-0.5">Revisaremos tu hotel y lo publicaremos dentro de las próximas 24 horas.</p>
                </div>
            </div>
        @elseif ($hotel->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4 mb-6 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Hotel rechazado</p>
                    <p class="text-xs text-red-700 mt-0.5">
                        @if ($hotel->order?->admin_notes)
                            Motivo: {{ $hotel->order->admin_notes }} —
                        @endif
                        Editá la información y volvé a enviar.
                    </p>
                </div>
            </div>
        @endif

        {{-- Stats row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-[#2D6A4F]">{{ $viewsToday }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Vistas hoy</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-[#2D6A4F]">{{ $viewsWeek }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Vistas 7 días</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-[#1A1A1A]">{{ $viewsTotal }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Vistas totales</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-[#1A1A1A]">{{ $contactsTotal }}</p>
                <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Consultas recibidas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Hotel info card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tu hotel</p>
                            <h2 class="text-xl font-bold text-[#1A1A1A] truncate">{{ $hotel->name }}</h2>
                            @if ($hotel->hotel_type)
                                <p class="text-xs text-[#2D6A4F] font-semibold mt-0.5">{{ $hotel->hotel_type }}</p>
                            @endif
                            <p class="text-gray-500 text-sm mt-1">{{ $hotel->address }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold flex-shrink-0
                            {{ $hotel->status === 'active' ? 'bg-green-100 text-green-700' : ($hotel->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $hotel->statusLabel() }}
                        </span>
                    </div>

                    @if ($hotel->cover_image)
                        <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}"
                            class="w-full h-40 object-cover rounded-xl mb-4">
                    @endif

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        @if ($hotel->phone)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Teléfono</p>
                            <p class="font-medium text-[#1A1A1A]">{{ $hotel->phone }}</p>
                        </div>
                        @endif
                        @if ($hotel->email)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Email de contacto</p>
                            <p class="font-medium text-[#1A1A1A] truncate">{{ $hotel->email }}</p>
                        </div>
                        @endif
                        @if ($hotel->checkin_time || $hotel->checkout_time)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Check-in / Check-out</p>
                            <p class="font-medium text-[#1A1A1A]">{{ $hotel->checkin_time ?? '—' }} / {{ $hotel->checkout_time ?? '—' }}</p>
                        </div>
                        @endif
                        @if ($hotel->stars)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Estrellas</p>
                            <p class="font-medium text-[#1A1A1A]">{{ str_repeat('★', $hotel->stars) }}</p>
                        </div>
                        @endif
                    </div>

                    @if ($hotel->services && count($hotel->services))
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400 mb-2">Servicios</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($hotel->services as $service)
                                <span class="bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-medium px-2 py-0.5 rounded-full">{{ $service }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Gallery overview --}}
                @if ($hotel->images->count())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-bold text-[#1A1A1A]">Galería</p>
                        <span class="text-xs text-gray-400">{{ $hotel->images->count() }} / {{ $hotel->plan->max_images }} imágenes</span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        @foreach ($hotel->images->take(8) as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Galería"
                                class="h-20 w-20 object-cover rounded-lg">
                        @endforeach
                        @if ($hotel->images->count() > 8)
                            <div class="h-20 w-20 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-bold text-gray-400">
                                +{{ $hotel->images->count() - 8 }}
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('hoteles.owner.edit') }}" class="block mt-3 text-xs text-[#2D6A4F] hover:underline font-semibold">
                        Administrar imágenes →
                    </a>
                </div>
                @endif

                {{-- Recent contacts --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-bold text-[#1A1A1A]">Consultas recientes</p>
                        <span class="text-xs text-gray-400">{{ $contactsTotal }} en total</span>
                    </div>

                    @if ($recentContacts->isEmpty())
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <p class="text-sm">Aún no recibiste consultas.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentContacts as $contact)
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#1A1A1A]">{{ $contact->sender_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $contact->sender_email }}
                                            @if ($contact->sender_phone)
                                                · {{ $contact->sender_phone }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $contact->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $contact->message }}</p>
                                <a href="mailto:{{ $contact->sender_email }}" class="inline-block mt-2 text-xs text-[#2D6A4F] font-semibold hover:underline">
                                    Responder →
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Side column --}}
            <div class="space-y-5">

                {{-- Plan card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Plan activo</p>
                    <p class="font-bold text-[#1A1A1A] text-base">{{ $hotel->plan->name }}</p>
                    <p class="text-xs text-[#2D6A4F] font-semibold mb-3">{{ $hotel->plan->tierLabel() }}</p>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Precio</span>
                            <span class="font-semibold">{{ $hotel->plan->formattedPrice() }}/año</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Imágenes</span>
                            <span class="font-semibold">{{ $hotel->images->count() }} / {{ $hotel->plan->max_images }}</span>
                        </div>
                        @if ($hotel->expires_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Vence</span>
                            <span class="font-semibold {{ $hotel->expires_at->isPast() ? 'text-red-600' : ($hotel->expires_at->diffInDays() < 30 ? 'text-amber-600' : 'text-[#1A1A1A]') }}">
                                {{ $hotel->expires_at->format('d/m/Y') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Pending order --}}
                @if ($hotel->order && $hotel->order->status === 'pending')
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-2">Pago pendiente</p>
                    <p class="text-sm text-amber-800 mb-3">Tu transferencia está siendo verificada. Te notificaremos cuando se confirme.</p>
                    @if (!$hotel->order->transfer_reference)
                    <a href="{{ route('hoteles.owner.checkout', $hotel->order) }}"
                        class="block text-center text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white py-2.5 px-4 rounded-xl transition">
                        Completar pago
                    </a>
                    @else
                    <p class="text-xs text-amber-700">Comprobante: <strong>{{ $hotel->order->transfer_reference }}</strong></p>
                    @endif
                </div>
                @endif

                {{-- Quick links --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Acciones rápidas</p>
                    <div class="space-y-2">
                        <a href="{{ route('hoteles.owner.edit') }}"
                            class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Editar información
                        </a>
                        @if ($hotel->status === 'active')
                        <a href="{{ route('hoteles.show', $hotel) }}" target="_blank"
                            class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Ver página del hotel
                        </a>
                        @endif
                        <a href="{{ route('hoteles.index') }}"
                            class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Explorar el directorio
                        </a>
                        <a href="{{ route('contacto') }}"
                            class="flex items-center gap-3 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">
                            <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Soporte
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
