@extends('layouts.admin')
@section('title', 'Planes Premium')
@section('header', 'Planes de Membresía')

@section('content')
<div class="space-y-6">

    {{-- Plan list --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-[#1A1A1A]">Planes activos</h2>
        </div>

        @if ($plans->isEmpty())
        <div class="text-center py-12 text-gray-400">Sin planes. Creá uno abajo.</div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach ($plans as $plan)
            <div class="px-6 py-5" id="plan-{{ $plan->id }}">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="font-bold text-[#1A1A1A]">{{ $plan->name }}</span>
                            @if ($plan->active)
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Activo</span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-500 font-semibold px-2 py-0.5 rounded-full">Inactivo</span>
                            @endif
                            @if ($plan->is_popular)
                                <span class="text-xs bg-[#2D6A4F]/10 text-[#2D6A4F] font-semibold px-2 py-0.5 rounded-full">★ Más elegido</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 flex items-center gap-2 flex-wrap">
                            <span>{{ $plan->durationLabel() }}</span>
                            <span>·</span>
                            @if ($plan->hasSale())
                                <s class="text-gray-400">{{ $plan->formattedPrice() }}</s>
                                <span class="text-amber-600 font-semibold">{{ $plan->formattedEffectivePrice() }}</span>
                                @if ($plan->sale_label)
                                    <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $plan->sale_label }}</span>
                                @endif
                            @else
                                <span>{{ $plan->formattedPrice() }}</span>
                            @endif
                            <span>·</span>
                            <span>{{ $plan->orders()->count() }} pedidos</span>
                        </p>
                    </div>
                    <button onclick="toggleEdit({{ $plan->id }})"
                        class="text-sm text-[#2D6A4F] font-semibold hover:underline">
                        Editar
                    </button>
                </div>

                {{-- Inline edit form --}}
                <div id="edit-{{ $plan->id }}" class="hidden mt-5 pt-5 border-t border-gray-100">
                    <form method="POST" action="{{ route('admin.planes.update', $plan) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre</label>
                                <input type="text" name="name" value="{{ $plan->name }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Precio (ARS)</label>
                                <input type="number" name="price" value="{{ $plan->price }}" step="0.01" min="0" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Duración</label>
                                <div class="flex gap-2">
                                    <input type="number" name="duration_months" value="{{ $plan->duration_months }}" min="1" required
                                        class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                    <select name="duration_unit"
                                        class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                                        <option value="months" @selected($plan->duration_unit === 'months')>Meses</option>
                                        <option value="weeks"  @selected($plan->duration_unit === 'weeks')>Semanas</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Orden</label>
                                <input type="number" name="sort_order" value="{{ $plan->sort_order }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                                <textarea name="description" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ $plan->description }}</textarea>
                            </div>
                        </div>

                        {{-- Descuento --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-wide mb-3">Precio especial / Descuento</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Precio con descuento (ARS)</label>
                                    <input type="number" name="sale_price" value="{{ $plan->sale_price }}" step="0.01" min="0" placeholder="Dejar vacío para quitar descuento"
                                        class="w-full border border-amber-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Etiqueta del descuento</label>
                                    <input type="text" name="sale_label" value="{{ $plan->sale_label }}" placeholder="Ej: Black Friday, -20%"
                                        class="w-full border border-amber-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                                </div>
                            </div>
                            @if ($plan->hasSale())
                            <p class="text-xs text-amber-600 mt-2">
                                Descuento activo: <strong>{{ $plan->formattedPrice() }}</strong> → <strong>{{ $plan->formattedEffectivePrice() }}</strong>
                                @if ($plan->sale_label) · <span class="italic">{{ $plan->sale_label }}</span>@endif
                            </p>
                            @endif
                        </div>

                        {{-- Beneficios --}}
                        <div class="bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-bold text-[#2D6A4F] uppercase tracking-wide">Beneficios del plan</p>
                                <button type="button" onclick="addFeature({{ $plan->id }})"
                                    class="text-xs font-semibold text-[#2D6A4F] hover:text-[#1A1A1A] flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Agregar
                                </button>
                            </div>
                            <ul id="features-list-{{ $plan->id }}" class="space-y-2">
                                @forelse ($plan->features ?? [] as $i => $feature)
                                <li class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-[#52B788] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <input type="text" name="features[]" value="{{ $feature }}"
                                        class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                                    <button type="button" onclick="this.closest('li').remove()"
                                        class="text-gray-300 hover:text-red-500 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </li>
                                @empty
                                <li class="text-xs text-gray-400 italic">Sin beneficios aún. Hacé clic en "Agregar".</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="flex items-center gap-4 flex-wrap">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="active" value="1" {{ $plan->active ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-[#2D6A4F]">
                                Activo (visible en planes)
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-[#2D6A4F]">
                                Más elegido
                            </label>
                            <div class="flex items-center gap-3 ml-auto">
                                <button type="button" onclick="toggleEdit({{ $plan->id }})"
                                    class="text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                                <button type="submit"
                                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                                    Guardar
                                </button>
                                <form action="{{ route('admin.planes.destroy', $plan) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar el plan «{{ $plan->name }}»? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Create new plan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-[#1A1A1A] mb-5">Crear nuevo plan</h2>
        <form method="POST" action="{{ route('admin.planes.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre *</label>
                    <input type="text" name="name" required placeholder="Ej: 2 Años"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Slug *</label>
                    <input type="text" name="slug" required placeholder="Ej: 2-anos"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Precio (ARS) *</label>
                    <input type="number" name="price" step="0.01" min="0" required placeholder="29999"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Duración *</label>
                    <div class="flex gap-2">
                        <input type="number" name="duration_months" min="1" required placeholder="12"
                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                        <select name="duration_unit"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                            <option value="months">Meses</option>
                            <option value="weeks">Semanas</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Orden</label>
                    <input type="number" name="sort_order" value="99"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                    <input type="text" name="description" placeholder="Breve descripción"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
            </div>
            <button type="submit"
                class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                Crear plan
            </button>
        </form>
    </div>

</div>

<script>
function toggleEdit(id) {
    document.getElementById('edit-' + id).classList.toggle('hidden');
}

function addFeature(planId) {
    const list = document.getElementById('features-list-' + planId);
    // Quitar el placeholder vacío si existe
    const empty = list.querySelector('li.italic-placeholder');
    if (empty) empty.remove();

    const li = document.createElement('li');
    li.className = 'flex items-center gap-2';
    li.innerHTML = `
        <svg class="w-3.5 h-3.5 text-[#52B788] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <input type="text" name="features[]" placeholder="Ej: Acceso completo al planificador"
            class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white" autofocus>
        <button type="button" onclick="this.closest('li').remove()"
            class="text-gray-300 hover:text-red-500 transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    list.appendChild(li);
    li.querySelector('input').focus();
}
</script>
@endsection
