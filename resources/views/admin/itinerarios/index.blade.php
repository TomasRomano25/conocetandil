@extends('layouts.admin')
@section('title', 'Itinerarios Premium')
@section('header', 'Itinerarios Premium')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-gray-500 text-sm">Creá y gestioná los itinerarios del módulo Premium.</p>
        <a href="{{ route('admin.itinerarios.create') }}"
            class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
            + Nuevo Itinerario
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if ($itineraries->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400">
                <p class="font-medium">No hay itinerarios todavía.</p>
                <a href="{{ route('admin.itinerarios.create') }}" class="text-[#2D6A4F] text-sm mt-2 inline-block">Crear el primero →</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="px-6 py-3 text-left">Título</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Días</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Tipo</th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">Temporada</th>
                        <th class="px-4 py-3 text-center">Items</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($itineraries as $itin)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-[#1A1A1A]">{{ $itin->title }}</td>
                        <td class="px-4 py-4 text-gray-500 hidden md:table-cell">
                            {{ $itin->days_min === $itin->days_max ? $itin->days_min . ' día(s)' : "{$itin->days_min}–{$itin->days_max} días" }}
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell">
                            <span class="text-xs font-semibold bg-[#2D6A4F]/10 text-[#2D6A4F] px-2.5 py-1 rounded-full">{{ ucfirst($itin->type) }}</span>
                        </td>
                        <td class="px-4 py-4 text-gray-500 text-xs hidden md:table-cell">
                            {{ ['summer'=>'Verano','winter'=>'Invierno','all'=>'Todo el año'][$itin->season] ?? $itin->season }}
                        </td>
                        <td class="px-4 py-4 text-center text-gray-500">{{ $itin->items_count }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $itin->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $itin->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.itinerarios.items', $itin) }}"
                                class="text-xs font-semibold border border-[#2D6A4F]/30 text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white px-3 py-1.5 rounded-lg transition">
                                Actividades
                            </a>
                            <a href="{{ route('admin.itinerarios.edit', $itin) }}"
                                class="text-xs font-semibold border border-gray-300 text-gray-600 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.itinerarios.destroy', $itin) }}"
                                onsubmit="return confirm('¿Eliminar este itinerario?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
