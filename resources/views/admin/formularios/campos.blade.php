@extends('layouts.admin')

@section('title', 'Campos - ' . $formulario->name)
@section('header', 'Campos del formulario')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Back --}}
    <a href="{{ route('admin.formularios.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a formularios
    </a>

    {{-- Form info --}}
    <div class="bg-[#2D6A4F]/5 border border-[#2D6A4F]/20 rounded-xl px-5 py-4">
        <p class="font-bold text-[#2D6A4F]">{{ $formulario->name }}</p>
        <p class="text-sm text-gray-500">{{ $formulario->description }}</p>
    </div>

    {{-- Fields list --}}
    <div id="fields-container" class="space-y-3">
        @foreach ($formulario->fields as $field)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden field-row" data-id="{{ $field->id }}">
            <div class="px-5 py-3 border-b border-gray-50 flex items-center gap-3 bg-gray-50/50 cursor-grab active:cursor-grabbing handle select-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                </svg>
                <span class="text-xs font-mono text-gray-400">{{ $field->name }}</span>
                <span class="text-xs font-semibold bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full ml-auto">{{ $field->type }}</span>
                @if (! $field->visible)
                    <span class="text-xs font-semibold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Oculto</span>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.formularios.campos.update', [$formulario, $field]) }}"
                class="px-5 py-4">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Etiqueta (label)</label>
                        <input type="text" name="label" value="{{ old('label', $field->label) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Placeholder</label>
                        <input type="text" name="placeholder" value="{{ old('placeholder', $field->placeholder) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                </div>

                <div class="flex items-center gap-6 mt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="visible" value="0">
                        <input type="checkbox" name="visible" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                            {{ $field->visible ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Visible</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="required" value="0">
                        <input type="checkbox" name="required" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                            {{ $field->required ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Obligatorio</span>
                    </label>
                    <button type="submit"
                        class="ml-auto bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 text-center">Arrastrá los campos para cambiar el orden.</p>

</div>

<script>
// Simple drag-and-drop reorder using HTML5 drag events
(function () {
    const container = document.getElementById('fields-container');
    let dragging = null;

    container.querySelectorAll('.handle').forEach(handle => {
        const row = handle.closest('.field-row');
        row.setAttribute('draggable', true);

        row.addEventListener('dragstart', e => {
            dragging = row;
            row.classList.add('opacity-50');
        });
        row.addEventListener('dragend', e => {
            dragging.classList.remove('opacity-50');
            dragging = null;
            saveOrder();
        });
        row.addEventListener('dragover', e => {
            e.preventDefault();
            if (dragging && dragging !== row) {
                const rect = row.getBoundingClientRect();
                const mid  = rect.top + rect.height / 2;
                if (e.clientY < mid) {
                    container.insertBefore(dragging, row);
                } else {
                    container.insertBefore(dragging, row.nextSibling);
                }
            }
        });
    });

    function saveOrder() {
        const ids = [...container.querySelectorAll('.field-row')].map(r => r.dataset.id);
        fetch('{{ route('admin.formularios.campos.reorder', $formulario) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ order: ids }),
        });
    }
})();
</script>
@endsection
