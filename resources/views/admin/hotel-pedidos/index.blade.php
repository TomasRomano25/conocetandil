@extends('layouts.admin')
@section('title', 'Pedidos de Hotel')
@section('header', 'Pedidos de Hotel')

@section('content')
    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-2 mb-6">
        @foreach(['all' => 'Todos', 'pending' => 'Pendientes', 'completed' => 'Completados', 'cancelled' => 'Cancelados'] as $val => $label)
            <a href="{{ route('admin.hotel-pedidos.index', $val !== 'all' ? ['status' => $val] : []) }}"
                class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
                    {{ (request('status', 'all') === $val || ($val === 'all' && !request('status'))) ? 'bg-[#2D6A4F] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
                @if ($val === 'pending' && $pendingCount > 0)
                    <span class="ml-1 bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">#</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Hotel</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Propietario</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Plan</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Monto</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Estado</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Fecha</th>
                    <th class="py-3 px-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-400 font-mono">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-3 px-4 font-semibold text-[#1A1A1A]">{{ $order->hotel->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $order->user->name }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $order->plan->tier === 3 ? 'bg-amber-100 text-amber-700' : 'bg-[#2D6A4F]/10 text-[#2D6A4F]' }}">
                            {{ $order->plan->name }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-semibold">{{ $order->plan->formattedPrice() }}</td>
                    <td class="py-3 px-4">
                        @php $color = $order->statusColor(); @endphp
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ $order->statusLabel() }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 text-right">
                        <a href="{{ route('admin.hotel-pedidos.show', $order) }}"
                            class="text-[#2D6A4F] hover:underline font-semibold text-xs">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-gray-400">No hay pedidos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if ($orders->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
@endsection
