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
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewGallery(event)">
                <p class="text-xs text-gray-500 mt-1">Podés seleccionar múltiples imágenes.</p>
                <div id="gallery-preview" class="mt-2 flex gap-2 flex-wrap"></div>
                @error('gallery.*') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
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
    </script>
@endsection
