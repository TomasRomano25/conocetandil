@extends('layouts.admin')

@section('title', 'Crear Lugar')
@section('header', 'Crear Lugar')

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.lugares.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="direction" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                <input type="text" name="direction" id="direction" value="{{ old('direction') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('direction') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewImage(event)">
                <img id="preview" class="mt-2 h-32 rounded-lg object-cover hidden">
                @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gallery" class="block text-sm font-medium text-gray-700 mb-1">Galería de imágenes</label>
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <p class="text-xs text-gray-500 mt-1">Podés arrastrar las imágenes para ordenarlas. La primera será la que aparece en el catálogo.</p>
                <div id="new-gallery-sort" class="mt-2 flex gap-2 flex-wrap select-none"></div>
                @error('gallery.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('order') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                        <span class="ml-2 text-sm text-gray-700">Destacado</span>
                    </label>
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                        <span class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                            Solo Premium
                        </span>
                    </label>
                </div>
            </div>

            {{-- Category & Rating --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="Ej: Restaurante, Senderismo, Hotel..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (0-5)</label>
                    <input type="number" name="rating" id="rating" value="{{ old('rating') }}" step="0.1" min="0" max="5" placeholder="4.5"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('rating') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Contact --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="(249) 444-1234"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Sitio Web</label>
                    <input type="text" name="website" id="website" value="{{ old('website') }}" placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('website') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Opening Hours --}}
            <div>
                <label for="opening_hours" class="block text-sm font-medium text-gray-700 mb-1">Horarios</label>
                <input type="text" name="opening_hours" id="opening_hours" value="{{ old('opening_hours') }}" placeholder="Lun-Vie 9:00-18:00"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('opening_hours') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Map Coordinates --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitud</label>
                    <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="0.0000001" placeholder="-37.3271680"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('latitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitud</label>
                    <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="0.0000001" placeholder="-59.1332414"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('longitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Promotion --}}
            <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700">Promoción (opcional)</h3>
                <div>
                    <label for="promotion_title" class="block text-sm font-medium text-gray-700 mb-1">Título de la promoción</label>
                    <input type="text" name="promotion_title" id="promotion_title" value="{{ old('promotion_title') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('promotion_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="promotion_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción de la promoción</label>
                    <textarea name="promotion_description" id="promotion_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('promotion_description') }}</textarea>
                    @error('promotion_description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="promotion_url" class="block text-sm font-medium text-gray-700 mb-1">URL de la promoción</label>
                    <input type="text" name="promotion_url" id="promotion_url" value="{{ old('promotion_url') }}" placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('promotion_url') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Crear Lugar
                </button>
                <a href="{{ route('admin.lugares.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // --- Main image preview ---
        document.getElementById('image').addEventListener('change', function (e) {
            const preview = document.getElementById('preview');
            const file = e.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        });

        // --- New gallery uploads with drag-sort preview ---
        let newFiles = [];
        const galleryInput = document.getElementById('gallery');
        const newSort = document.getElementById('new-gallery-sort');

        galleryInput.addEventListener('change', function () {
            newFiles = Array.from(this.files);
            renderNewPreviews();
        });

        function renderNewPreviews() {
            newSort.innerHTML = '';
            newFiles.forEach((file, idx) => {
                const card = document.createElement('div');
                card.className = 'relative group w-28 h-28 rounded-xl overflow-hidden shadow-sm border-2 border-transparent cursor-grab active:cursor-grabbing transition-all';
                card.draggable = true;
                card.dataset.newIdx = idx;

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'absolute inset-0 w-full h-full object-cover pointer-events-none';

                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all pointer-events-none flex items-center justify-center';
                overlay.innerHTML = `<svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition drop-shadow pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>`;

                const badge = document.createElement('span');
                badge.className = 'principal-badge absolute bottom-1 left-1 bg-[#2D6A4F] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md hidden z-10';
                badge.textContent = 'Principal';

                card.appendChild(img);
                card.appendChild(overlay);
                card.appendChild(badge);
                newSort.appendChild(card);
            });

            initDragSort(newSort);
            updatePrincipalBadge(newSort);
        }

        // Before submit, rebuild gallery input files in the dragged order
        document.querySelector('form').addEventListener('submit', function () {
            if (newFiles.length === 0) return;
            const cards = newSort.querySelectorAll('[draggable]');
            const dt = new DataTransfer();
            cards.forEach(card => {
                dt.items.add(newFiles[parseInt(card.dataset.newIdx)]);
            });
            galleryInput.files = dt.files;
        });

        function initDragSort(container) {
            let dragSrc = null;

            container.addEventListener('dragstart', e => {
                dragSrc = e.target.closest('[draggable]');
                if (!dragSrc) return;
                setTimeout(() => dragSrc.classList.add('opacity-50'), 0);
            });

            container.addEventListener('dragend', () => {
                container.querySelectorAll('[draggable]').forEach(el => {
                    el.classList.remove('opacity-50', '!border-[#52B788]');
                });
                updatePrincipalBadge(container);
            });

            container.addEventListener('dragover', e => {
                e.preventDefault();
                const target = e.target.closest('[draggable]');
                if (target && target !== dragSrc) {
                    container.querySelectorAll('[draggable]').forEach(el => el.classList.remove('!border-[#52B788]'));
                    target.classList.add('!border-[#52B788]');
                }
            });

            container.addEventListener('dragleave', e => {
                const target = e.target.closest('[draggable]');
                if (target) target.classList.remove('!border-[#52B788]');
            });

            container.addEventListener('drop', e => {
                e.preventDefault();
                const target = e.target.closest('[draggable]');
                if (target && dragSrc && target !== dragSrc && container.contains(dragSrc)) {
                    target.classList.remove('!border-[#52B788]');
                    const allCards = [...container.querySelectorAll('[draggable]')];
                    const srcIdx = allCards.indexOf(dragSrc);
                    const tgtIdx = allCards.indexOf(target);
                    container.insertBefore(dragSrc, srcIdx < tgtIdx ? target.nextSibling : target);
                }
                updatePrincipalBadge(container);
            });
        }

        function updatePrincipalBadge(container) {
            if (!container) return;
            container.querySelectorAll('.principal-badge').forEach(b => b.classList.add('hidden'));
            const first = container.querySelector('[draggable]');
            if (first) first.querySelector('.principal-badge')?.classList.remove('hidden');
        }
    </script>
@endsection
