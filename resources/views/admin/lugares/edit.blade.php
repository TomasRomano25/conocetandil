@extends('layouts.admin')

@section('title', 'Editar Lugar')
@section('header', 'Editar Lugar')

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.lugares.update', $lugar) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $lugar->title) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="direction" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                <input type="text" name="direction" id="direction" value="{{ old('direction', $lugar->direction) }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('direction') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('description', $lugar->description) }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen principal</label>
                @if ($lugar->image)
                    <p class="text-xs text-[#2D6A4F] font-medium mb-1">Hacé click en la imagen para ajustar el encuadre (qué parte se muestra al recortar)</p>
                    <div class="relative h-44 w-full rounded-xl overflow-hidden bg-gray-100 cursor-crosshair mb-2" id="focal-current-container">
                        <img src="{{ asset('storage/' . $lugar->image) }}" alt="{{ $lugar->title }}"
                            class="absolute inset-0 w-full h-full object-cover pointer-events-none"
                            id="focal-current-img"
                            style="object-position: {{ $lugar->image_focal_x ?? 50 }}% {{ $lugar->image_focal_y ?? 50 }}%">
                        <div class="absolute inset-0" id="focal-current-area"></div>
                        <div id="focal-current-dot"
                            class="absolute w-6 h-6 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow-lg pointer-events-none z-10"
                            style="left: {{ $lugar->image_focal_x ?? 50 }}%; top: {{ $lugar->image_focal_y ?? 50 }}%; background: rgba(45,106,79,0.75);">
                            <div class="absolute inset-1 rounded-full border border-white/60"></div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Subí una nueva imagen para reemplazarla.</p>
                @endif
                <input type="hidden" name="image_focal_x" id="image_focal_x" value="{{ old('image_focal_x', $lugar->image_focal_x ?? 50) }}">
                <input type="hidden" name="image_focal_y" id="image_focal_y" value="{{ old('image_focal_y', $lugar->image_focal_y ?? 50) }}">
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <div id="preview-container" class="mt-2 relative h-44 w-full rounded-xl overflow-hidden bg-gray-100 cursor-crosshair hidden">
                    <img id="preview" src="" alt="" class="absolute inset-0 w-full h-full object-cover pointer-events-none"
                        style="object-position: 50% 50%">
                    <div class="absolute inset-0" id="focal-preview-area"></div>
                    <div id="focal-preview-dot"
                        class="absolute w-6 h-6 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow-lg pointer-events-none z-10"
                        style="left: 50%; top: 50%; background: rgba(45,106,79,0.75);">
                        <div class="absolute inset-1 rounded-full border border-white/60"></div>
                    </div>
                    <p class="absolute bottom-2 left-0 right-0 text-center pointer-events-none">
                        <span class="text-white text-xs bg-black/50 px-2 py-1 rounded-full">Click para ajustar encuadre</span>
                    </p>
                </div>
                @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Existing Gallery Images (drag to reorder) --}}
            @if ($lugar->images->count())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galería actual</label>
                    <p class="text-xs text-gray-500 mb-2">Arrastrá las imágenes para reordenarlas. La primera es la que se muestra en el catálogo.</p>
                    <div id="gallery-sort" class="flex gap-3 flex-wrap select-none">
                        @foreach ($lugar->images as $image)
                            <div class="relative group w-28 h-28 rounded-xl overflow-hidden shadow-sm border-2 border-transparent cursor-grab active:cursor-grabbing transition-all"
                                draggable="true" data-id="{{ $image->id }}">
                                {{-- Image fills container --}}
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Galería"
                                    class="absolute inset-0 w-full h-full object-cover pointer-events-none"
                                    style="object-position: {{ $image->focal_x ?? 50 }}% {{ $image->focal_y ?? 50 }}%">
                                {{-- Hover overlay with drag icon --}}
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all pointer-events-none flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition drop-shadow pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </div>
                                {{-- Principal badge --}}
                                <span class="principal-badge absolute bottom-1 left-1 bg-[#2D6A4F] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md hidden z-10">
                                    Principal
                                </span>
                                {{-- Focal point button --}}
                                <button type="button"
                                    onclick="openFocalModal({{ $image->id }}, '{{ asset('storage/' . $image->path) }}', {{ $image->focal_x ?? 50 }}, {{ $image->focal_y ?? 50 }})"
                                    class="absolute bottom-1 right-1 bg-black/60 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition z-10" title="Ajustar encuadre">
                                    <svg class="w-3 h-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                                        <path stroke-linecap="round" stroke-width="2" d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
                                    </svg>
                                </button>
                                {{-- Delete --}}
                                <label class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition z-10" title="Eliminar">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only delete-checkbox">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </label>
                                {{-- Hidden order + focal inputs --}}
                                <input type="hidden" name="gallery_order[]" value="{{ $image->id }}">
                                <input type="hidden" name="existing_focal_x[{{ $image->id }}]" id="gfx-{{ $image->id }}" value="{{ $image->focal_x ?? 50 }}">
                                <input type="hidden" name="existing_focal_y[{{ $image->id }}]" id="gfy-{{ $image->id }}" value="{{ $image->focal_y ?? 50 }}">
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Hacé click en la X para marcar imágenes a eliminar.</p>
                </div>
            @endif

            <div>
                <label for="gallery" class="block text-sm font-medium text-gray-700 mb-1">Agregar imágenes a la galería</label>
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <p class="text-xs text-gray-500 mt-1">Podés arrastrar las nuevas imágenes para ordenarlas antes de guardar.</p>
                <div id="new-gallery-sort" class="mt-2 flex gap-2 flex-wrap select-none"></div>
                @error('gallery.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $lugar->order) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('order') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center">
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $lugar->featured) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                        <span class="ml-2 text-sm text-gray-700">Destacado</span>
                    </label>
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium', $lugar->is_premium) ? 'checked' : '' }}
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
                    <input type="text" name="category" id="category" value="{{ old('category', $lugar->category) }}" placeholder="Ej: Restaurante, Senderismo, Hotel..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (0-5)</label>
                    <input type="number" name="rating" id="rating" value="{{ old('rating', $lugar->rating) }}" step="0.1" min="0" max="5" placeholder="4.5"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('rating') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Contact --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $lugar->phone) }}" placeholder="(249) 444-1234"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Sitio Web</label>
                    <input type="text" name="website" id="website" value="{{ old('website', $lugar->website) }}" placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('website') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Opening Hours --}}
            <div>
                <label for="opening_hours" class="block text-sm font-medium text-gray-700 mb-1">Horarios</label>
                <input type="text" name="opening_hours" id="opening_hours" value="{{ old('opening_hours', $lugar->opening_hours) }}" placeholder="Lun-Vie 9:00-18:00"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('opening_hours') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Map Coordinates --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitud</label>
                    <input type="number" name="latitude" id="latitude" value="{{ old('latitude', $lugar->latitude) }}" step="0.0000001" placeholder="-37.3271680"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('latitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitud</label>
                    <input type="number" name="longitude" id="longitude" value="{{ old('longitude', $lugar->longitude) }}" step="0.0000001" placeholder="-59.1332414"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('longitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Promotion --}}
            <div class="border border-gray-200 rounded-lg p-4 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700">Promoción (opcional)</h3>
                <div>
                    <label for="promotion_title" class="block text-sm font-medium text-gray-700 mb-1">Título de la promoción</label>
                    <input type="text" name="promotion_title" id="promotion_title" value="{{ old('promotion_title', $lugar->promotion_title) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('promotion_title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="promotion_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción de la promoción</label>
                    <textarea name="promotion_description" id="promotion_description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('promotion_description', $lugar->promotion_description) }}</textarea>
                    @error('promotion_description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="promotion_url" class="block text-sm font-medium text-gray-700 mb-1">URL de la promoción</label>
                    <input type="text" name="promotion_url" id="promotion_url" value="{{ old('promotion_url', $lugar->promotion_url) }}" placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('promotion_url') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t">
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.lugares.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Focal Point Modal --}}
    <div id="focal-modal" class="hidden fixed inset-0 bg-black/75 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <div>
                    <h3 class="font-bold text-[#1A1A1A] text-base">Ajustar encuadre</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Hacé click en la parte de la imagen que querés mostrar</p>
                </div>
                <button type="button" onclick="closeFocalModal()" class="text-gray-400 hover:text-gray-700 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="relative cursor-crosshair select-none" id="focal-modal-container">
                <img id="focal-modal-img" src="" alt="" class="w-full block" style="max-height: 420px; object-fit: contain;">
                <div class="absolute inset-0" id="focal-modal-area"></div>
                <div id="focal-modal-dot"
                    class="absolute w-6 h-6 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow-lg pointer-events-none z-10 hidden"
                    style="background: rgba(45,106,79,0.75);">
                    <div class="absolute inset-1 rounded-full border border-white/60"></div>
                </div>
            </div>
            <div class="px-5 py-4 flex justify-end">
                <button type="button" onclick="closeFocalModal()"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                    Listo
                </button>
            </div>
        </div>
    </div>

    <script>
        // --- Main image preview + focal point ---
        document.getElementById('image').addEventListener('change', function (e) {
            const preview = document.getElementById('preview');
            const container = document.getElementById('preview-container');
            const file = e.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                container.classList.remove('hidden');
                // Reset focal point to center for new image
                document.getElementById('image_focal_x').value = 50;
                document.getElementById('image_focal_y').value = 50;
                preview.style.objectPosition = '50% 50%';
                const dot = document.getElementById('focal-preview-dot');
                dot.style.left = '50%'; dot.style.top = '50%';
            }
        });

        // Focal click on existing current image
        (function () {
            const area = document.getElementById('focal-current-area');
            if (!area) return;
            area.addEventListener('click', function (e) {
                const rect = area.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width * 100).toFixed(1);
                const y = ((e.clientY - rect.top) / rect.height * 100).toFixed(1);
                document.getElementById('image_focal_x').value = x;
                document.getElementById('image_focal_y').value = y;
                const dot = document.getElementById('focal-current-dot');
                dot.style.left = x + '%'; dot.style.top = y + '%';
                const img = document.getElementById('focal-current-img');
                img.style.objectPosition = x + '% ' + y + '%';
            });
        })();

        // Focal click on new image preview
        (function () {
            const area = document.getElementById('focal-preview-area');
            if (!area) return;
            area.addEventListener('click', function (e) {
                const rect = area.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width * 100).toFixed(1);
                const y = ((e.clientY - rect.top) / rect.height * 100).toFixed(1);
                document.getElementById('image_focal_x').value = x;
                document.getElementById('image_focal_y').value = y;
                const dot = document.getElementById('focal-preview-dot');
                dot.style.left = x + '%'; dot.style.top = y + '%';
                const img = document.getElementById('preview');
                img.style.objectPosition = x + '% ' + y + '%';
            });
        })();

        // --- Gallery focal point modal ---
        let _focalImageId = null;
        window.openFocalModal = function (imageId, src, fx, fy) {
            _focalImageId = imageId;
            const modal = document.getElementById('focal-modal');
            const img = document.getElementById('focal-modal-img');
            const dot = document.getElementById('focal-modal-dot');
            img.src = src;
            img.onload = function () {
                // Position dot after image loads
                positionModalDot(fx, fy);
                dot.classList.remove('hidden');
            };
            if (img.complete && img.naturalWidth) {
                positionModalDot(fx, fy);
                dot.classList.remove('hidden');
            }
            modal.classList.remove('hidden');
        };

        window.closeFocalModal = function () {
            document.getElementById('focal-modal').classList.add('hidden');
            _focalImageId = null;
        };

        document.getElementById('focal-modal').addEventListener('click', function (e) {
            if (e.target === this) closeFocalModal();
        });

        function positionModalDot(fx, fy) {
            const img = document.getElementById('focal-modal-img');
            const rect = img.getBoundingClientRect();
            const container = document.getElementById('focal-modal-container');
            const cRect = container.getBoundingClientRect();
            const dot = document.getElementById('focal-modal-dot');
            // object-fit: contain — calculate the actual image rect inside the container
            const naturalW = img.naturalWidth, naturalH = img.naturalHeight;
            const containerW = rect.width, containerH = rect.height;
            const scale = Math.min(containerW / naturalW, containerH / naturalH);
            const imgW = naturalW * scale, imgH = naturalH * scale;
            const offX = (containerW - imgW) / 2;
            const offY = (containerH - imgH) / 2;
            const px = offX + (fx / 100) * imgW;
            const py = offY + (fy / 100) * imgH;
            dot.style.left = px + 'px';
            dot.style.top = py + 'px';
        }

        document.getElementById('focal-modal-area').addEventListener('click', function (e) {
            if (!_focalImageId) return;
            const img = document.getElementById('focal-modal-img');
            const rect = img.getBoundingClientRect();
            // object-fit: contain calculations
            const naturalW = img.naturalWidth, naturalH = img.naturalHeight;
            const containerW = rect.width, containerH = rect.height;
            const scale = Math.min(containerW / naturalW, containerH / naturalH);
            const imgW = naturalW * scale, imgH = naturalH * scale;
            const offX = (containerW - imgW) / 2;
            const offY = (containerH - imgH) / 2;
            const clickX = e.clientX - rect.left;
            const clickY = e.clientY - rect.top;
            // Clamp to image bounds
            const imgX = Math.max(0, Math.min(clickX - offX, imgW));
            const imgY = Math.max(0, Math.min(clickY - offY, imgH));
            const fx = parseFloat((imgX / imgW * 100).toFixed(1));
            const fy = parseFloat((imgY / imgH * 100).toFixed(1));
            // Update hidden inputs
            const xInput = document.getElementById('gfx-' + _focalImageId);
            const yInput = document.getElementById('gfy-' + _focalImageId);
            if (xInput) xInput.value = fx;
            if (yInput) yInput.value = fy;
            // Move dot
            const dot = document.getElementById('focal-modal-dot');
            dot.style.left = (offX + fx / 100 * imgW) + 'px';
            dot.style.top  = (offY + fy / 100 * imgH) + 'px';
            dot.classList.remove('hidden');
            // Update thumbnail object-position live
            const card = document.querySelector('[data-id="' + _focalImageId + '"]');
            if (card) {
                const thumb = card.querySelector('img');
                if (thumb) thumb.style.objectPosition = fx + '% ' + fy + '%';
            }
        });

        // --- Delete checkbox visual feedback ---
        document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const card = this.closest('[draggable]');
                card.classList.toggle('opacity-40', this.checked);
                card.classList.toggle('ring-2', this.checked);
                card.classList.toggle('ring-red-500', this.checked);
                card.classList.toggle('rounded-lg', this.checked);
            });
        });

        // --- Drag-sort for existing gallery ---
        const existingSort = document.getElementById('gallery-sort');
        if (existingSort) {
            initDragSort(existingSort);
            updatePrincipalBadge(existingSort);
        }

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

            // If no existing gallery, new previews also show Principal on first
            initDragSort(newSort, true);
            updatePrincipalBadge(existingSort || newSort);
        }

        // Before submit, rebuild gallery input files in the new order
        document.querySelector('form').addEventListener('submit', function () {
            if (newFiles.length === 0) return;
            const cards = newSort.querySelectorAll('[draggable]');
            const dt = new DataTransfer();
            cards.forEach(card => {
                dt.items.add(newFiles[parseInt(card.dataset.newIdx)]);
            });
            galleryInput.files = dt.files;
        });

        // --- Drag sort utility ---
        function initDragSort(container, updateFirstBadgeFromExternal) {
            let dragSrc = null;

            container.addEventListener('dragstart', e => {
                dragSrc = e.target.closest('[draggable]');
                if (!dragSrc) return;
                setTimeout(() => dragSrc.classList.add('opacity-50'), 0);
            });

            container.addEventListener('dragend', () => {
                container.querySelectorAll('[draggable]').forEach(el => {
                    el.classList.remove('opacity-50', 'border-[#52B788]', '!border-[#52B788]');
                });
                // Update principal badge: if this is existingSort, use it; if new uploads exist, first of existing still wins
                updatePrincipalBadge(existingSort && existingSort.children.length ? existingSort : newSort);
                if (existingSort && existingSort.children.length && newSort.children.length) {
                    updatePrincipalBadge(newSort); // hide badges on new ones if existing has items
                }
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
