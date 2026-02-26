@extends('layouts.admin')
@section('title', 'Actividades — ' . $itinerario->title)
@section('header', 'Actividades del Itinerario')

@section('content')
<div class="space-y-6">

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.itinerarios.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="text-gray-300">|</span>
        <h2 class="font-bold text-[#1A1A1A]">{{ $itinerario->title }}</h2>
        <span class="text-xs text-gray-400">{{ $itinerario->days_min }}–{{ $itinerario->days_max }} días</span>
        <a href="{{ route('admin.itinerarios.edit', $itinerario) }}"
            class="ml-auto text-xs border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
            Editar datos
        </a>
    </div>

    {{-- Existing items grouped by day --}}
    @php $byDay = $itinerario->items->groupBy('day'); @endphp
    @foreach ($byDay as $day => $dayItems)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 bg-[#2D6A4F]/5 border-b border-gray-100 flex items-center gap-2">
            <span class="w-7 h-7 bg-[#2D6A4F] text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $day }}</span>
            <span class="font-bold text-sm text-[#1A1A1A]">Día {{ $day }}</span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach ($dayItems->sortBy('sort_order') as $item)
            <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {{ \App\Models\Itinerary::timeBlockIcon($item->time_block) }}
                                {{ \App\Models\Itinerary::timeBlockLabel($item->time_block) }}
                            </span>
                            @if ($item->duration_minutes)
                                <span class="text-xs text-gray-400">{{ $item->formattedDuration() }}</span>
                            @endif
                            @if ($item->estimated_cost)
                                <span class="text-xs text-gray-400">· {{ $item->estimated_cost }}</span>
                            @endif
                        </div>
                        <p class="font-semibold text-[#1A1A1A] text-sm">{{ $item->displayTitle() }}</p>
                        @if ($item->why_order)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->why_order }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button onclick="openEditModal({{ $item->id }})"
                            class="text-xs border border-gray-300 text-gray-600 px-2.5 py-1.5 rounded-lg hover:bg-gray-50 transition">
                            Editar
                        </button>
                        <form method="POST" action="{{ route('admin.itinerarios.items.destroy', [$itinerario, $item]) }}"
                            onsubmit="return confirm('¿Eliminar esta actividad?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs border border-red-200 text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition">
                                ✕
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Travel connector in admin list --}}
            @if (!$loop->last && $item->travel_minutes_to_next)
            <div class="flex items-center gap-3 px-5 py-1.5 bg-gray-50/80 border-t border-dashed border-gray-200">
                <div class="flex-1 border-t border-dashed border-gray-300"></div>
                <span class="text-xs text-gray-400 font-medium flex items-center gap-1">
                    {{ $item->travelIcon() }} {{ $item->travelLabel() }} al siguiente
                </span>
                <div class="flex-1 border-t border-dashed border-gray-300"></div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Add new item form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
            <h3 class="font-bold text-sm text-[#1A1A1A]">+ Agregar actividad</h3>
        </div>
        <form method="POST" action="{{ route('admin.itinerarios.items.store', $itinerario) }}"
            class="px-5 py-4 space-y-4">
            @csrf

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Lugar</label>
                    <select name="lugar_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                        <option value="">Sin lugar asociado</option>
                        @foreach ($lugares as $lugar)
                            <option value="{{ $lugar->id }}">{{ $lugar->title }}{{ $lugar->category ? ' (' . $lugar->category . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Día *</label>
                    <input type="number" name="day" value="{{ ($byDay->keys()->max() ?? 0) }}" min="1" max="7" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Bloque horario *</label>
                    <select name="time_block"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                        <option value="morning">🌅 Mañana</option>
                        <option value="lunch">☀️ Mediodía</option>
                        <option value="afternoon">🌤️ Tarde</option>
                        <option value="evening">🌙 Noche</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Título personalizado</label>
                    <input type="text" name="custom_title" placeholder="Dejar vacío para usar el del lugar"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Duración (min)</label>
                        <input type="number" name="duration_minutes" placeholder="90"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Costo estimado</label>
                        <input type="text" name="estimated_cost" placeholder="Gratis"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Viaje al siguiente (min)</label>
                        <input type="number" name="travel_minutes_to_next" placeholder="15" min="1" max="999"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">¿Por qué en este orden?</label>
                <input type="text" name="why_order" placeholder="Ideal ir temprano para evitar el calor..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Notas contextuales</label>
                <input type="text" name="contextual_notes" placeholder="En verano puede estar muy lleno. Ir temprano."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Saltá esto si…</label>
                    <input type="text" name="skip_if" placeholder="No tenés mucho tiempo"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Vale la pena porque…</label>
                    <input type="text" name="why_worth_it" placeholder="Vistas únicas de las sierras"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                    Agregar actividad
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit modal --}}
<div id="edit-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <h3 class="text-lg font-bold text-[#1A1A1A]">Editar actividad</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Día</label>
                        <input type="number" name="day" id="edit-day" min="1" max="7"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Bloque</label>
                        <select name="time_block" id="edit-time_block"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                            <option value="morning">🌅 Mañana</option>
                            <option value="lunch">☀️ Mediodía</option>
                            <option value="afternoon">🌤️ Tarde</option>
                            <option value="evening">🌙 Noche</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Título personalizado</label>
                    <input type="text" name="custom_title" id="edit-custom_title"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Duración (min)</label>
                        <input type="number" name="duration_minutes" id="edit-duration_minutes"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Costo estimado</label>
                        <input type="text" name="estimated_cost" id="edit-estimated_cost"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Viaje al siguiente (min)</label>
                        <input type="number" name="travel_minutes_to_next" id="edit-travel_minutes_to_next" min="1" max="999"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">¿Por qué en este orden?</label>
                    <input type="text" name="why_order" id="edit-why_order"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Notas contextuales</label>
                    <input type="text" name="contextual_notes" id="edit-contextual_notes"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Saltá esto si…</label>
                        <input type="text" name="skip_if" id="edit-skip_if"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Vale la pena porque…</label>
                        <input type="text" name="why_worth_it" id="edit-why_worth_it"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeEditModal()" class="text-gray-600 text-sm">Cancelar</button>
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const items = @json($itinerario->items->keyBy('id'));
const baseUrl = '{{ url("admin/itinerarios/{$itinerario->id}/actividades") }}';

function openEditModal(id) {
    const item = items[id];
    const form = document.getElementById('edit-form');
    form.action = baseUrl + '/' + id;
    ['day','time_block','custom_title','duration_minutes','estimated_cost',
     'why_order','contextual_notes','skip_if','why_worth_it','travel_minutes_to_next'].forEach(f => {
        const el = document.getElementById('edit-' + f);
        if (el) el.value = item[f] ?? '';
    });
    document.getElementById('edit-modal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
document.getElementById('edit-modal').addEventListener('click', e => { if (e.target === e.currentTarget) closeEditModal(); });
</script>
@endsection
