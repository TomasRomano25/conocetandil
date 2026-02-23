@extends('layouts.admin')

@section('title', 'Banners en Lugares')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Banners en Lugares</h1>
            <p class="text-sm text-gray-500 mt-0.5">Banners promocionales que aparecen entre las cards en la sección Lugares.</p>
        </div>
        <a href="{{ route('admin.lugar-banners.create') }}"
            class="inline-flex items-center gap-2 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Banner
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
            {{ session('success') }}
        </div>
    @endif

    @if($banners->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
            </svg>
            <p class="text-gray-500 font-medium">No hay banners creados todavía</p>
            <p class="text-gray-400 text-sm mt-1">Creá un banner para promocionar las suscripciones premium entre los lugares.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Banner</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Posición</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Estado</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Imágenes</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($banners as $banner)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center" style="background-color: {{ $banner->bg_color }}">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $banner->title }}</p>
                                        @if($banner->subtitle)
                                            <p class="text-gray-400 text-xs truncate max-w-[200px]">{{ $banner->subtitle }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 text-gray-700 font-medium">
                                    Después del lugar #{{ $banner->position }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($banner->active)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 font-semibold text-xs px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 font-semibold text-xs px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="{{ $banner->image_desktop ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                        Desktop {{ $banner->image_desktop ? '✓' : '—' }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="{{ $banner->image_mobile ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                        Mobile {{ $banner->image_mobile ? '✓' : '—' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.lugar-banners.edit', $banner) }}"
                                        class="inline-flex items-center gap-1 text-xs bg-gray-100 hover:bg-[#2D6A4F] hover:text-white text-gray-700 font-medium px-3 py-1.5 rounded-lg transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.lugar-banners.destroy', $banner) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este banner?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-500 hover:text-white text-red-600 font-medium px-3 py-1.5 rounded-lg transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Visual preview hint --}}
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-700">
            <strong>Tip:</strong> La "Posición" indica después de cuál card de lugar aparece el banner. Por ejemplo, posición 3 = el banner aparece después de la 3ª card. Podés crear múltiples banners en distintas posiciones.
        </div>
    @endif

</div>
@endsection
