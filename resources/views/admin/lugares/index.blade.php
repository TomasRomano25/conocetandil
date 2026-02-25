@extends('layouts.admin')

@section('title', 'Lugares')
@section('header', 'Lugares')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <form method="GET" action="{{ route('admin.lugares.index') }}" class="flex gap-2 flex-1 max-w-sm">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por título o dirección..."
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white px-4 py-2 rounded-lg text-sm transition">
                Buscar
            </button>
            @if($search ?? false)
                <a href="{{ route('admin.lugares.index') }}" class="py-2 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">✕</a>
            @endif
        </form>
        <div class="flex gap-2">
            <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition text-sm">
                Importar JSON
            </button>
            <a href="{{ route('admin.lugares.create') }}" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-4 rounded-lg transition">
                + Nuevo Lugar
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Import Modal --}}
    <div id="import-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-[#1A1A1A] mb-1">Importar Lugares desde JSON</h2>
            <p class="text-sm text-gray-500 mb-4">
                Si un lugar con el mismo <strong>título</strong> ya existe, sus datos serán actualizados. Los lugares nuevos se crearán automáticamente.
            </p>
            <form method="POST" action="{{ route('admin.lugares.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Archivo JSON</label>
                    <input type="file" name="json_file" accept=".json,.txt" required
                        class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                        class="py-2 px-4 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-4 rounded-lg text-sm transition">
                        Importar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Dirección</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Destacado</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Premium</th>
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
                        <td class="px-6 py-4">
                            @if ($lugar->is_premium)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                                    Premium
                                </span>
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No hay lugares registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lugares->links() }}
    </div>
@endsection
