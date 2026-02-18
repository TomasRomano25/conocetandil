@extends('layouts.admin')
@section('title', 'Hoteles')
@section('header', 'Hoteles')

@section('content')
    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-2 mb-6">
        @foreach(['all' => 'Todos', 'pending' => 'Pendientes', 'active' => 'Activos', 'rejected' => 'Rechazados', 'suspended' => 'Suspendidos'] as $val => $label)
            @php $current = request('status', 'all'); @endphp
            <a href="{{ route('admin.hoteles.index', $val !== 'all' ? ['status' => $val] : []) }}"
                class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
                    {{ $current === $val || ($val === 'all' && !request('status')) ? 'bg-[#2D6A4F] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
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
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Hotel</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Propietario</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Plan</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Estado</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Fecha</th>
                    <th class="py-3 px-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($hotels as $hotel)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <p class="font-semibold text-[#1A1A1A]">{{ $hotel->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $hotel->address }}</p>
                    </td>
                    <td class="py-3 px-4">
                        <p class="text-gray-700">{{ $hotel->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $hotel->user->email }}</p>
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $hotel->plan->tier === 3 ? 'bg-amber-100 text-amber-700' : 'bg-[#2D6A4F]/10 text-[#2D6A4F]' }}">
                            {{ $hotel->plan->name }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @php $color = $hotel->statusColor(); @endphp
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ $hotel->statusLabel() }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $hotel->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 text-right">
                        <a href="{{ route('admin.hoteles.show', $hotel) }}"
                            class="text-[#2D6A4F] hover:underline font-semibold text-xs">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-400">No hay hoteles.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if ($hotels->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $hotels->links() }}
        </div>
        @endif
    </div>
@endsection
