@extends('layouts.admin')

@section('title', 'Lugares')
@section('header', 'Lugares')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">Gestioná los lugares turísticos de Tandil.</p>
        <a href="{{ route('admin.lugares.create') }}" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-4 rounded-lg transition">
            + Nuevo Lugar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Dirección</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Destacado</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($lugares as $lugar)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if ($lugar->image)
                                <img src="{{ asset('storage/' . $lugar->image) }}" alt="{{ $lugar->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-[#1A1A1A]">{{ $lugar->title }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm hidden md:table-cell">{{ $lugar->direction }}</td>
                        <td class="px-6 py-4">
                            @if ($lugar->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Sí</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $lugar->order }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.lugares.edit', $lugar) }}" class="text-[#2D6A4F] hover:text-[#52B788] font-medium text-sm">Editar</a>
                            <form method="POST" action="{{ route('admin.lugares.destroy', $lugar) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este lugar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay lugares registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lugares->links() }}
    </div>
@endsection
