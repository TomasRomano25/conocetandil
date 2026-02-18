@extends('layouts.admin')
@section('title', 'Hotel: ' . $hotel->name)
@section('header', 'Hotel: ' . $hotel->name)

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Status + actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-5">
            <div>
                <h2 class="text-xl font-bold text-[#1A1A1A]">{{ $hotel->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $hotel->address }}</p>
            </div>
            @php $color = $hotel->statusColor(); @endphp
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $hotel->statusLabel() }}
            </span>
        </div>

        {{-- Approve / Reject forms --}}
        @if ($hotel->isPending())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
            <form action="{{ route('admin.hoteles.approve', $hotel) }}" method="POST" class="space-y-2">
                @csrf
                <textarea name="admin_notes" rows="2" placeholder="Nota (opcional)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                    ✓ Aprobar y publicar
                </button>
            </form>
            <form action="{{ route('admin.hoteles.reject', $hotel) }}" method="POST" class="space-y-2">
                @csrf
                <textarea name="admin_notes" rows="2" placeholder="Motivo del rechazo"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                    ✗ Rechazar
                </button>
            </form>
        </div>
        @endif

        @if ($hotel->order?->admin_notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Nota admin</p>
            <p class="text-sm text-gray-700">{{ $hotel->order->admin_notes }}</p>
        </div>
        @endif
    </div>

    {{-- Hotel details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Información del hotel</p>
        <dl class="space-y-3 text-sm">
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Plan</dt>
                <dd class="col-span-2 font-semibold">{{ $hotel->plan->name }} ({{ $hotel->plan->tierLabel() }})</dd>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Propietario</dt>
                <dd class="col-span-2">{{ $hotel->user->name }} — {{ $hotel->user->email }}</dd>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Email hotel</dt>
                <dd class="col-span-2">{{ $hotel->email }}</dd>
            </div>
            @if ($hotel->phone)
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Teléfono</dt>
                <dd class="col-span-2">{{ $hotel->phone }}</dd>
            </div>
            @endif
            @if ($hotel->website)
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Web</dt>
                <dd class="col-span-2"><a href="{{ $hotel->website }}" target="_blank" class="text-[#2D6A4F] hover:underline">{{ $hotel->website }}</a></dd>
            </div>
            @endif
            @if ($hotel->stars)
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Estrellas</dt>
                <dd class="col-span-2">{{ $hotel->stars }} ★</dd>
            </div>
            @endif
            @if ($hotel->checkin_time || $hotel->checkout_time)
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Check-in/out</dt>
                <dd class="col-span-2">{{ $hotel->checkin_time }} / {{ $hotel->checkout_time }}</dd>
            </div>
            @endif
            @if ($hotel->expires_at)
            <div class="grid grid-cols-3 gap-2">
                <dt class="text-gray-500 font-medium">Vence</dt>
                <dd class="col-span-2">{{ $hotel->expires_at->format('d/m/Y') }}</dd>
            </div>
            @endif
        </dl>

        <div class="mt-5 pt-4 border-t border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Descripción</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $hotel->description }}</p>
        </div>

        @if ($hotel->services && count($hotel->services))
        <div class="mt-5 pt-4 border-t border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Servicios</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($hotel->services as $s)
                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $s }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Cover image --}}
    @if ($hotel->cover_image)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Portada</p>
        <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}" class="h-48 rounded-xl object-cover">
    </div>
    @endif

    {{-- Gallery --}}
    @if ($hotel->images->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Galería ({{ $hotel->images->count() }})</p>
        <div class="flex flex-wrap gap-3">
            @foreach ($hotel->images as $img)
            <div class="text-center">
                <img src="{{ asset('storage/' . $img->path) }}" alt="Galería" class="h-28 w-28 rounded-lg object-cover">
                @if ($img->caption)
                    <p class="text-xs text-gray-400 mt-1 max-w-[7rem] truncate">{{ $img->caption }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Rooms --}}
    @if ($hotel->rooms->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Habitaciones</p>
        <div class="space-y-3">
            @foreach ($hotel->rooms as $room)
            <div class="flex items-center gap-3 text-sm border border-gray-100 rounded-lg p-3">
                @if ($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="h-16 w-16 rounded-lg object-cover flex-shrink-0">
                @endif
                <div>
                    <p class="font-semibold text-[#1A1A1A]">{{ $room->name }}</p>
                    @if ($room->capacity) <p class="text-gray-500">{{ $room->capacity }} personas</p> @endif
                    @if ($room->price_per_night) <p class="text-[#2D6A4F] font-semibold">{{ $room->formattedPrice() }}</p> @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Danger zone --}}
    <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
        <p class="text-xs font-bold text-red-400 uppercase tracking-widest mb-3">Eliminar hotel</p>
        <form action="{{ route('admin.hoteles.destroy', $hotel) }}" method="POST"
            onsubmit="return confirm('¿Eliminar el hotel {{ $hotel->name }}? Esta acción es irreversible.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-5 rounded-lg text-sm transition">
                Eliminar hotel
            </button>
        </form>
    </div>

    <a href="{{ route('admin.hoteles.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-[#2D6A4F] text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver a hoteles
    </a>
</div>
@endsection
