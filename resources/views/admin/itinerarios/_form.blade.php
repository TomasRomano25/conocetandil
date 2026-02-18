<div class="max-w-2xl space-y-6">

    <a href="{{ route('admin.itinerarios.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D6A4F] transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a itinerarios
    </a>

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf
        @if ($method === 'PUT') @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Título *</label>
            <input type="text" name="title" value="{{ old('title', $itinerario?->title) }}" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descripción</label>
            <textarea name="description" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('description', $itinerario?->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tip de introducción</label>
            <textarea name="intro_tip" rows="2" placeholder="Ej: Ideal para hacer en verano, llevá protector solar..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('intro_tip', $itinerario?->intro_tip) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Días mínimos *</label>
                <input type="number" name="days_min" value="{{ old('days_min', $itinerario?->days_min ?? 1) }}" min="1" max="7" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Días máximos *</label>
                <input type="number" name="days_max" value="{{ old('days_max', $itinerario?->days_max ?? 1) }}" min="1" max="7" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipo de experiencia *</label>
                <select name="type"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    @foreach(['nature'=>'Naturaleza','gastronomy'=>'Gastronomía','adventure'=>'Aventura','relax'=>'Relax','mixed'=>'Mixto'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('type', $itinerario?->type) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Temporada *</label>
                <select name="season"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                    @foreach(['all'=>'Todo el año','summer'=>'Verano','winter'=>'Invierno'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('season', $itinerario?->season) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="kid_friendly" value="0">
                <input type="checkbox" name="kid_friendly" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                    {{ old('kid_friendly', $itinerario?->kid_friendly ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Apto para niños</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="requires_car" value="0">
                <input type="checkbox" name="requires_car" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                    {{ old('requires_car', $itinerario?->requires_car) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Requiere auto</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" class="rounded border-gray-300 text-[#2D6A4F]"
                    {{ old('active', $itinerario?->active ?? true) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-gray-700">Activo</span>
            </label>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Imagen de portada</label>
            @if ($itinerario?->cover_image)
                <img src="{{ asset('storage/' . $itinerario->cover_image) }}"
                    class="h-28 w-full object-cover rounded-lg mb-2 border border-gray-200">
            @endif
            <input type="file" name="cover_image" accept="image/*"
                class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2D6A4F]/10 file:text-[#2D6A4F] hover:file:bg-[#2D6A4F]/20 file:cursor-pointer">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Orden</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $itinerario?->sort_order ?? 0) }}" min="0"
                class="w-32 border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.itinerarios.index') }}"
                class="text-gray-500 hover:text-gray-800 text-sm py-2 px-4">Cancelar</a>
            <button type="submit"
                class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                {{ $itinerario ? 'Guardar cambios' : 'Crear itinerario' }}
            </button>
        </div>
    </form>
</div>
