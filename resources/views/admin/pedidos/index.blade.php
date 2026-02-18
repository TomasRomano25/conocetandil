@extends('layouts.admin')
@section('title', 'Pedidos')
@section('header', 'Pedidos')

@section('content')

{{-- Filters --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @foreach([''=>'Todos', 'pending'=>'Pendientes', 'completed'=>'Completados', 'cancelled'=>'Cancelados'] as $val => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
        class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
            {{ request('status', '') === $val
                ? 'bg-[#2D6A4F] text-white border-[#2D6A4F]'
                : 'bg-white text-gray-600 border-gray-200 hover:border-[#2D6A4F]' }}">
        {{ $label }}
        @if ($val === 'pending' && $pendingCount > 0)
            <span class="ml-1 bg-amber-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
        @endif
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if ($orders->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-3xl mb-3">📦</p>
            <p class="font-semibold">No hay pedidos todavía</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">#</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Usuario</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Plan</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Comprobante</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Estado</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Fecha</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($orders as $order)
                @php
                    $colors = ['pending'=>'amber','completed'=>'green','cancelled'=>'red'];
                    $c = $colors[$order->status] ?? 'gray';
                @endphp
                <tr class="hover:bg-gray-50 {{ $order->isPending() ? 'bg-amber-50/30' : '' }}">
                    <td class="px-5 py-3 font-mono text-gray-400">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-[#1A1A1A]">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium">{{ $order->plan->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->plan->durationLabel() }}</p>
                    </td>
                    <td class="px-5 py-3 font-semibold text-[#2D6A4F]">{{ $order->plan->formattedPrice() }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $order->transfer_reference ?: '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            bg-{{ $c }}-100 text-{{ $c }}-700">
                            {{ $order->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.pedidos.show', $order) }}"
                            class="text-[#2D6A4F] hover:text-[#1A1A1A] font-semibold text-xs">
                            Ver →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
