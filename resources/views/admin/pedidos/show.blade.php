@extends('layouts.admin')
@section('title', 'Pedido #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))
@section('header', 'Pedido #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Back --}}
    <a href="{{ route('admin.pedidos.index') }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
        ← Volver a pedidos
    </a>

    {{-- Status banner --}}
    @php
        $colors = ['pending'=>'amber','completed'=>'green','cancelled'=>'red'];
        $c = $colors[$order->status] ?? 'gray';
    @endphp
    <div class="bg-{{ $c }}-50 border border-{{ $c }}-200 rounded-xl px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">
                @if ($order->isPending()) 🕐
                @elseif ($order->isCompleted()) ✅
                @else ❌ @endif
            </span>
            <div>
                <p class="font-bold text-{{ $c }}-800">{{ $order->statusLabel() }}</p>
                @if ($order->isCompleted())
                    <p class="text-xs text-{{ $c }}-600">Completado el {{ $order->completed_at->format('d/m/Y H:i') }}</p>
                @elseif ($order->isPending())
                    <p class="text-xs text-{{ $c }}-600">Esperando verificación de la transferencia.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- User info --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Usuario</p>
            <p class="font-bold text-[#1A1A1A] text-base">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $order->user->email }}</p>
            @if ($order->user->isPremium())
                <span class="inline-block mt-3 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                    ✦ Premium activo hasta {{ $order->user->premium_expires_at->format('d/m/Y') }}
                </span>
            @else
                <span class="inline-block mt-3 text-xs font-semibold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">
                    Sin Premium
                </span>
            @endif
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.usuarios.edit', $order->user) }}"
                    class="text-sm text-[#2D6A4F] hover:underline font-medium">
                    Ver perfil del usuario →
                </a>
            </div>
        </div>

        {{-- Plan + payment --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Plan y pago</p>
            <p class="font-bold text-[#1A1A1A] text-base">{{ $order->plan->name }}</p>
            <p class="text-sm text-gray-500">{{ $order->plan->durationLabel() }} de acceso Premium</p>
            <p class="text-2xl font-bold text-[#2D6A4F] mt-3">{{ $order->plan->formattedPrice() }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100 text-sm">
                <p class="text-gray-500">Comprobante de transferencia</p>
                <p class="font-semibold text-[#1A1A1A] mt-1">
                    {{ $order->transfer_reference ?: 'No proporcionado' }}
                </p>
            </div>
            <div class="mt-3 text-xs text-gray-400">
                Pedido el {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    {{-- Admin notes --}}
    @if ($order->admin_notes)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Notas internas</p>
        <p class="text-sm text-gray-700">{{ $order->admin_notes }}</p>
    </div>
    @endif

    {{-- Actions --}}
    @if ($order->isPending())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Acciones</p>

        {{-- Complete --}}
        <form method="POST" action="{{ route('admin.pedidos.complete', $order) }}"
            onsubmit="return confirm('¿Confirmar la transferencia y activar Premium para {{ $order->user->name }}?')">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nota interna (opcional)</label>
                <input type="text" name="admin_notes" placeholder="Ej: Transferencia verificada en Homebanking"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition">
                ✅ Marcar como completado y activar Premium
            </button>
        </form>

        <div class="border-t border-gray-100 pt-4">
            <form method="POST" action="{{ route('admin.pedidos.cancel', $order) }}"
                onsubmit="return confirm('¿Cancelar este pedido?')">
                @csrf
                <input type="hidden" name="admin_notes" value="">
                <button type="submit"
                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Cancelar pedido
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
