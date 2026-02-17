@extends('layouts.admin')

@section('title', 'Menú de Navegación')
@section('header', 'Menú de Navegación')

@section('content')
    <p class="text-gray-600 mb-6">
        Arrastrá los ítems para reordenarlos. Podés mostrar u ocultar cada sección del menú principal.
    </p>

    <div id="nav-list" class="space-y-3 max-w-2xl">
        @foreach ($navItems as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4"
                 data-id="{{ $item->id }}">

                {{-- Drag handle --}}
                <div class="cursor-grab text-gray-400 hover:text-gray-600 drag-handle flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                    </svg>
                </div>

                {{-- Item info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-[#1A1A1A]">{{ $item->label }}</span>
                        <span class="text-xs font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded">/{{ $item->key }}</span>
                    </div>
                </div>

                {{-- Visibility toggle --}}
                <button onclick="toggleVisibility({{ $item->id }}, this)"
                    class="flex-shrink-0 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ $item->is_visible ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                    data-visible="{{ $item->is_visible ? '1' : '0' }}">
                    {{ $item->is_visible ? 'Visible' : 'Oculto' }}
                </button>

                {{-- Edit label button --}}
                <button onclick="openModal({{ $item->id }})"
                    class="flex-shrink-0 text-[#2D6A4F] hover:text-[#52B788] font-medium text-sm transition-colors">
                    Editar
                </button>
            </div>
        @endforeach
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm">
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-[#1A1A1A]">Editar etiqueta</h3>
                    <div>
                        <label for="modal-label" class="block text-sm font-medium text-gray-700 mb-1">
                            Texto del menú
                        </label>
                        <input type="text" name="label" id="modal-label" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_visible" id="modal-visible" value="1"
                                class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                            <span class="text-sm text-gray-700">Visible en el menú</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                    <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-900 text-sm">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Drag-to-reorder ──────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('nav-list');
            if (window.Sortable) {
                new Sortable(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function () {
                        const order = Array.from(el.children).map(item => item.dataset.id);
                        axios.post('{{ route("admin.nav.reorder") }}', { order })
                            .catch(err => alert('Error al reordenar: ' + err.message));
                    }
                });
            }
        });

        const navItems = @json($navItems->keyBy('id'));

        // ── Modal ────────────────────────────────────────────────────────────
        function openModal(id) {
            const item = navItems[id];
            const form = document.getElementById('edit-form');
            form.action = '{{ url("admin/nav") }}/' + id;
            document.getElementById('modal-label').value   = item.label || '';
            document.getElementById('modal-visible').checked = !!item.is_visible;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        document.getElementById('edit-modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

        // ── Quick visibility toggle ──────────────────────────────────────────
        function toggleVisibility(id, btn) {
            const item = navItems[id];
            const newVal = !item.is_visible;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("admin/nav") }}/' + id;
            form.innerHTML =
                '@csrf @method("PUT")' +
                '<input type="hidden" name="label" value="' + item.label.replace(/"/g, '&quot;') + '">' +
                (newVal ? '<input type="hidden" name="is_visible" value="1">' : '');
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
