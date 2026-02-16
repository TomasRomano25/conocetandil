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
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                @if ($lugar->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $lugar->image) }}" alt="{{ $lugar->title }}" class="h-32 rounded-lg object-cover">
                        <p class="text-xs text-gray-500 mt-1">Imagen actual. Subí una nueva para reemplazarla.</p>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewImage(event)">
                <img id="preview" class="mt-2 h-32 rounded-lg object-cover hidden">
                @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Existing Gallery Images --}}
            @if ($lugar->images->count())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Galería actual</label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach ($lugar->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Galería" class="h-24 w-24 rounded-lg object-cover">
                                <label class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition" title="Eliminar">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Hacé click en la X para marcar imágenes a eliminar.</p>
                </div>
            @endif

            <div>
                <label for="gallery" class="block text-sm font-medium text-gray-700 mb-1">Agregar imágenes a la galería</label>
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewGallery(event)">
                <div id="gallery-preview" class="mt-2 flex gap-2 flex-wrap"></div>
                @error('gallery.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
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

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        }

        function previewGallery(event) {
            const container = document.getElementById('gallery-preview');
            container.innerHTML = '';
            Array.from(event.target.files).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'h-24 w-24 rounded-lg object-cover';
                container.appendChild(img);
            });
        }

        document.querySelectorAll('input[name="delete_images[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const container = this.closest('.relative');
                if (this.checked) {
                    container.classList.add('opacity-40', 'ring-2', 'ring-red-500', 'rounded-lg');
                } else {
                    container.classList.remove('opacity-40', 'ring-2', 'ring-red-500', 'rounded-lg');
                }
            });
        });
    </script>
@endsection
