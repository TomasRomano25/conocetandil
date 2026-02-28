@extends('layouts.admin')
@section('title', 'Planes de Hotel')
@section('header', 'Planes de Hotel')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Plan list --}}
    <div class="lg:col-span-2 space-y-4">
        @foreach ($plans as $plan)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" id="plan-{{ $plan->id }}">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-[#1A1A1A]">{{ $plan->name }}</h3>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $plan->tier === 3 ? 'bg-amber-100 text-amber-700' : 'bg-[#2D6A4F]/10 text-[#2D6A4F]' }}">
                            Tier {{ $plan->tier }} · {{ $plan->tierLabel() }}
                        </span>
                        @if (! $plan->is_active)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Inactivo</span>
                        @endif
                    </div>
                    <p class="text-xl font-bold text-[#2D6A4F] mt-1">{{ $plan->formattedPrice() }} <span class="text-sm text-gray-400 font-normal">/ año</span></p>
                </div>
                <button onclick="toggleEditPlan({{ $plan->id }})"
                    class="text-sm text-[#2D6A4F] hover:underline font-semibold flex-shrink-0">
                    Editar
                </button>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="{{ $plan->has_services ? 'text-green-500' : 'text-gray-300' }}">●</span> Servicios
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="{{ $plan->has_rooms ? 'text-green-500' : 'text-gray-300' }}">●</span> Habitaciones
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="{{ $plan->has_gallery_captions ? 'text-green-500' : 'text-gray-300' }}">●</span> Captions galería
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <span class="{{ $plan->is_featured ? 'text-amber-500' : 'text-gray-300' }}">●</span> Destacado
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-600">
                            <span class="{{ $plan->is_popular ? 'text-pink-500' : 'text-gray-300' }}">●</span> Más elegido
                </div>
                <div class="flex items-center gap-1.5 text-gray-600">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Máx. {{ $plan->max_images }} imágenes
                </div>
            </div>

            {{-- Edit form --}}
            <div id="edit-plan-{{ $plan->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                <form action="{{ route('admin.hotel-planes.update', $plan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                            <input type="text" name="name" value="{{ $plan->name }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Precio</label>
                            <input type="number" name="price" value="{{ $plan->price }}" min="0" step="0.01" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tier</label>
                            <select name="tier"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                <option value="1" @selected($plan->tier === 1)>1 — Básico</option>
                                <option value="2" @selected($plan->tier === 2)>2 — Estándar</option>
                                <option value="3" @selected($plan->tier === 3)>3 — Diamante</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Máx. imágenes</label>
                            <input type="number" name="max_images" value="{{ $plan->max_images }}" min="1" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Duración (meses)</label>
                            <input type="number" name="duration_months" value="{{ $plan->duration_months }}" min="1" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Orden</label>
                            <input type="number" name="sort_order" value="{{ $plan->sort_order }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                        <textarea name="description" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ $plan->description }}</textarea>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="has_services" value="1" {{ $plan->has_services ? 'checked' : '' }}
                                class="rounded border-gray-300 text-[#2D6A4F]">
                            Servicios
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="has_rooms" value="1" {{ $plan->has_rooms ? 'checked' : '' }}
                                class="rounded border-gray-300 text-[#2D6A4F]">
                            Habitaciones
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="has_gallery_captions" value="1" {{ $plan->has_gallery_captions ? 'checked' : '' }}
                                class="rounded border-gray-300 text-[#2D6A4F]">
                            Captions
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_featured" value="1" {{ $plan->is_featured ? 'checked' : '' }}
                                class="rounded border-gray-300 text-amber-500">
                            Destacado
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}
                                class="rounded border-gray-300 text-pink-500">
                            Más elegido
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}
                                class="rounded border-gray-300 text-[#2D6A4F]">
                            Activo
                        </label>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg text-sm transition">
                            Guardar cambios
                        </button>
                        <button type="button" onclick="toggleEditPlan({{ $plan->id }})"
                            class="text-gray-500 text-sm hover:text-gray-700">Cancelar</button>
                        <form action="{{ route('admin.hotel-planes.destroy', $plan) }}" method="POST" class="ml-auto"
                            onsubmit="return confirm('¿Eliminar el plan {{ $plan->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Create plan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="font-bold text-[#1A1A1A] mb-4">Nuevo plan</h3>
        <form action="{{ route('admin.hotel-planes.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                <input type="text" name="name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Slug *</label>
                <input type="text" name="slug" required placeholder="ej: premium"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Precio *</label>
                <input type="number" name="price" min="0" step="0.01" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tier *</label>
                <select name="tier" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    <option value="1">1 — Básico</option>
                    <option value="2">2 — Estándar</option>
                    <option value="3">3 — Diamante</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Máx. imágenes *</label>
                <input type="number" name="max_images" min="1" value="1" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Duración (meses) *</label>
                <input type="number" name="duration_months" min="1" value="12" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                <textarea name="description" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <label class="flex items-center gap-1.5"><input type="checkbox" name="has_services" value="1" class="rounded"> Servicios</label>
                <label class="flex items-center gap-1.5"><input type="checkbox" name="has_rooms" value="1" class="rounded"> Habitaciones</label>
                <label class="flex items-center gap-1.5"><input type="checkbox" name="has_gallery_captions" value="1" class="rounded"> Captions</label>
                <label class="flex items-center gap-1.5"><input type="checkbox" name="is_featured" value="1" class="rounded"> Destacado</label>
                <label class="flex items-center gap-1.5"><input type="checkbox" name="is_popular" value="1" class="rounded"> Más elegido</label>
            </div>
            <button type="submit"
                class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 rounded-lg text-sm transition">
                Crear plan
            </button>
        </form>
    </div>
</div>

<script>
function toggleEditPlan(id) {
    const el = document.getElementById('edit-plan-' + id);
    el.classList.toggle('hidden');
}
</script>
@endsection
