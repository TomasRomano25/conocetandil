@extends('layouts.admin')

@section('title', 'Editar Inicio')
@section('header', 'Editar Página de Inicio')

@section('content')

    {{-- ── Hero Banner Image ───────────────────────────────────────────── --}}
    @php $hero = $sections->firstWhere('key', 'hero'); @endphp
    @if ($hero)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 max-w-2xl">
            <h2 class="text-base font-bold text-[#1A1A1A] mb-1">Imagen del hero</h2>
            <p class="text-sm text-gray-500 mb-4">
                Esta imagen se muestra como fondo de la sección principal de la página de inicio.
                Si no hay imagen, se usa el fondo de color verde.
            </p>

            {{-- Current image preview --}}
            @if ($hero->image)
                <div class="mb-4 relative inline-block">
                    <img src="{{ asset('storage/' . $hero->image) }}"
                         alt="Hero banner actual"
                         class="w-full max-w-sm h-40 object-cover rounded-lg border border-gray-200">
                    <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        Activa
                    </span>
                </div>
                {{-- Delete form --}}
                <form method="POST" action="{{ route('admin.inicio.hero-image.delete') }}"
                      onsubmit="return confirm('¿Eliminar la imagen del hero?')" class="mb-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                        Eliminar imagen actual
                    </button>
                </form>
            @else
                <div class="mb-4 w-full max-w-sm h-40 bg-gradient-to-br from-[#2D6A4F]/15 to-[#52B788]/15 rounded-lg border border-dashed border-gray-300 flex items-center justify-center">
                    <span class="text-sm text-gray-400">Sin imagen — se usa fondo verde</span>
                </div>
            @endif

            {{-- Upload form --}}
            <form method="POST" action="{{ route('admin.inicio.hero-image') }}"
                  enctype="multipart/form-data" class="flex items-center gap-3 flex-wrap">
                @csrf
                <label class="flex-1 min-w-0">
                    <input type="file" name="image" accept="image/*" required
                        class="block w-full text-sm text-gray-600
                               file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-[#2D6A4F]/10 file:text-[#2D6A4F]
                               hover:file:bg-[#2D6A4F]/20 file:cursor-pointer cursor-pointer">
                </label>
                <button type="submit"
                    class="flex-shrink-0 bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-5 rounded-lg transition text-sm">
                    {{ $hero->image ? 'Reemplazar' : 'Subir imagen' }}
                </button>
            </form>
            @error('image')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <p class="text-gray-600 mb-6">Arrastrá las secciones para reordenarlas. Podés editar el contenido y togglear la visibilidad.</p>

    <div id="sections-list" class="space-y-4">
        @foreach ($sections as $section)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4" data-id="{{ $section->id }}">
                {{-- Drag handle --}}
                <div class="cursor-grab text-gray-400 hover:text-gray-600 drag-handle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>

                {{-- Section info --}}
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-[#1A1A1A]">{{ $section->title }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">{{ $section->key }}</span>
                    </div>
                    @if ($section->subtitle)
                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($section->subtitle, 80) }}</p>
                    @endif
                </div>

                {{-- Visibility toggle --}}
                <button onclick="toggleVisibility({{ $section->id }}, this)"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $section->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}"
                    data-visible="{{ $section->is_visible ? '1' : '0' }}">
                    {{ $section->is_visible ? 'Visible' : 'Oculto' }}
                </button>

                {{-- Edit button --}}
                <button onclick="openModal({{ $section->id }})" class="text-[#2D6A4F] hover:text-[#52B788] font-medium text-sm">
                    Editar
                </button>
            </div>
        @endforeach
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-[#1A1A1A]">Editar Sección</h3>

                    <div>
                        <label for="modal-title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" name="title" id="modal-title" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>

                    <div>
                        <label for="modal-subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                        <input type="text" name="subtitle" id="modal-subtitle"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    </div>

                    <div>
                        <label for="modal-content" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                        <textarea name="content" id="modal-content" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_visible" id="modal-visible" value="1"
                                class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                            <span class="ml-2 text-sm text-gray-700">Visible en el sitio</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                    <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-900 text-sm">Cancelar</button>
                    <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('sections-list');
            if (window.Sortable) {
                new Sortable(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function () {
                        const order = Array.from(el.children).map(item => item.dataset.id);
                        axios.post('{{ route("admin.inicio.reorder") }}', { order: order })
                            .then(() => {})
                            .catch(err => alert('Error al reordenar: ' + err.message));
                    }
                });
            }
        });

        const sections = @json($sections->keyBy('id'));

        function openModal(id) {
            const section = sections[id];
            const form = document.getElementById('edit-form');
            form.action = '{{ url("admin/inicio") }}/' + id;
            document.getElementById('modal-title').value = section.title || '';
            document.getElementById('modal-subtitle').value = section.subtitle || '';
            document.getElementById('modal-content').value = section.content || '';
            document.getElementById('modal-visible').checked = section.is_visible;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function toggleVisibility(id, btn) {
            const section = sections[id];
            const newVisibility = !section.is_visible;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("admin/inicio") }}/' + id;
            form.innerHTML = '@csrf @method("PUT")' +
                '<input type="hidden" name="title" value="' + section.title.replace(/"/g, '&quot;') + '">' +
                '<input type="hidden" name="subtitle" value="' + (section.subtitle || '').replace(/"/g, '&quot;') + '">' +
                '<input type="hidden" name="content" value="' + (section.content || '').replace(/"/g, '&quot;') + '">' +
                (newVisibility ? '<input type="hidden" name="is_visible" value="1">' : '');
            document.body.appendChild(form);
            form.submit();
        }

        document.getElementById('edit-modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    </script>
@endsection
