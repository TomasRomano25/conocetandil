@extends('layouts.app')
@section('title', 'Mi Hotel — Conoce Tandil')

@section('content')

<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#1A1A1A]">Mi hotel</h1>
            <p class="text-gray-500 mt-1">Gestioná tu presencia en el directorio de hoteles.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 mb-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tu hotel</p>
                    <h2 class="text-xl font-bold text-[#1A1A1A]">{{ $hotel->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $hotel->address }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    {{ $hotel->status === 'active' ? 'bg-green-100 text-green-700' : ($hotel->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ $hotel->statusLabel() }}
                </span>
            </div>

            @if ($hotel->status === 'active')
                <div class="mt-5 pt-5 border-t border-gray-100 flex items-center gap-4 flex-wrap text-sm">
                    <a href="{{ route('hoteles.show', $hotel) }}" target="_blank"
                        class="flex items-center gap-1.5 text-[#2D6A4F] hover:underline font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Ver en el directorio
                    </a>
                    @if ($hotel->expires_at)
                        <span class="text-gray-400">Vigente hasta: {{ $hotel->expires_at->format('d/m/Y') }}</span>
                    @endif
                </div>
            @elseif ($hotel->status === 'pending')
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-700">
                        Tu hotel está pendiente de revisión. Lo publicaremos dentro de las próximas 24 horas.
                    </div>
                </div>
            @elseif ($hotel->status === 'rejected')
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                        Tu hotel fue rechazado.
                        @if ($hotel->order?->admin_notes)
                            Motivo: {{ $hotel->order->admin_notes }}
                        @endif
                        Podés editar la información y volver a enviar.
                    </div>
                </div>
            @endif
        </div>

        {{-- Plan info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7 mb-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Plan activo</p>
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="font-bold text-[#1A1A1A]">{{ $hotel->plan->name }} ({{ $hotel->plan->tierLabel() }})</p>
                    <p class="text-sm text-gray-500">{{ $hotel->plan->formattedPrice() }} / año · hasta {{ $hotel->plan->max_images }} imagen{{ $hotel->plan->max_images > 1 ? 'es' : '' }}</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('hoteles.owner.edit') }}"
                class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                Editar hotel
            </a>
            <a href="{{ route('hoteles.index') }}"
                class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                Ver directorio
            </a>
        </div>

    </div>
</section>

@endsection
