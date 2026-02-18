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
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-[#1A1A1A]">{{ $plan->name }}</span>
                            @if ($plan->active)
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Activo</span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-500 font-semibold px-2 py-0.5 rounded-full">Inactivo</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $plan->durationLabel() }} · {{ $plan->formattedPrice() }} · {{ $plan->orders()->count() }} pedidos</p>
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
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Duración (meses)</label>
                                <input type="number" name="duration_months" value="{{ $plan->duration_months }}" min="1" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
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
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="active" value="1" {{ $plan->active ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-[#2D6A4F]">
                                Activo (visible en planes)
                            </label>
                            <div class="flex gap-3 ml-auto">
                                <button type="button" onclick="toggleEdit({{ $plan->id }})"
                                    class="text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                                <button type="submit"
                                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                                    Guardar
                                </button>
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
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Duración (meses) *</label>
                    <input type="number" name="duration_months" min="1" required placeholder="24"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
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
</script>
@endsection
