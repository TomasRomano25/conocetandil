@extends('layouts.admin')
@section('title', 'Pedido Hotel #' . str_pad($hotelOrder->id, 5, '0', STR_PAD_LEFT))
@section('header', 'Pedido #' . str_pad($hotelOrder->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Order summary --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-[#1A1A1A]">Pedido #{{ str_pad($hotelOrder->id, 5, '0', STR_PAD_LEFT) }}</h2>
            @php $color = $hotelOrder->statusColor(); @endphp
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700">
                {{ $hotelOrder->statusLabel() }}
            </span>
        </div>
        <dl class="space-y-3 text-sm">
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Hotel</dt>
                <dd class="col-span-2 font-semibold">
                    <a href="{{ route('admin.hoteles.show', $hotelOrder->hotel) }}" class="text-[#2D6A4F] hover:underline">
                        {{ $hotelOrder->hotel->name }}
                    </a>
                </dd>
            </div>
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Propietario</dt>
                <dd class="col-span-2">{{ $hotelOrder->user->name }} — {{ $hotelOrder->user->email }}</dd>
            </div>
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Plan</dt>
                <dd class="col-span-2 font-semibold">{{ $hotelOrder->plan->name }} · {{ $hotelOrder->plan->durationLabel() }}</dd>
            </div>
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Monto</dt>
                <dd class="col-span-2 font-bold text-[#1A1A1A]">{{ $hotelOrder->plan->formattedPrice() }}</dd>
            </div>
            @if ($hotelOrder->transfer_reference)
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Comprobante</dt>
                <dd class="col-span-2 font-mono">{{ $hotelOrder->transfer_reference }}</dd>
            </div>
            @endif
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Fecha</dt>
                <dd class="col-span-2">{{ $hotelOrder->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            @if ($hotelOrder->completed_at)
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Completado</dt>
                <dd class="col-span-2">{{ $hotelOrder->completed_at->format('d/m/Y H:i') }}</dd>
            </div>
            @endif
            @if ($hotelOrder->admin_notes)
            <div class="grid grid-cols-3">
                <dt class="text-gray-500">Nota</dt>
                <dd class="col-span-2">{{ $hotelOrder->admin_notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Actions --}}
    @if ($hotelOrder->isPending())
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <form action="{{ route('admin.hotel-pedidos.complete', $hotelOrder) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
            @csrf
            <p class="font-semibold text-[#1A1A1A] text-sm">Completar pedido</p>
            <p class="text-xs text-gray-500">Marca el pago como recibido y activa el hotel automáticamente.</p>
            <textarea name="admin_notes" rows="2" placeholder="Nota (opcional)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                ✓ Completar y activar hotel
            </button>
        </form>

        <form action="{{ route('admin.hotel-pedidos.cancel', $hotelOrder) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-3">
            @csrf
            <p class="font-semibold text-[#1A1A1A] text-sm">Cancelar pedido</p>
            <p class="text-xs text-gray-500">Cancela el pedido. El hotel queda en estado pendiente.</p>
            <textarea name="admin_notes" rows="2" placeholder="Motivo (opcional)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition text-sm">
                ✗ Cancelar pedido
            </button>
        </form>
    </div>
    @endif

    <a href="{{ route('admin.hotel-pedidos.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-[#2D6A4F] text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver a pedidos
    </a>
</div>
@endsection
