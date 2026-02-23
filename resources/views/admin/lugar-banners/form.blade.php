@extends('layouts.admin')

@section('title', $banner->exists ? 'Editar Banner' : 'Nuevo Banner')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.lugar-banners.index') }}" class="text-sm text-gray-500 hover:text-[#2D6A4F] flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver a Banners
        </a>
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $banner->exists ? 'Editar Banner' : 'Nuevo Banner' }}
        </h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-5">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $banner->exists ? route('admin.lugar-banners.update', $banner) : route('admin.lugar-banners.store') }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($banner->exists) @method('PUT') @endif

        {{-- Contenido --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h2 class="font-semibold text-gray-800 text-base border-b border-gray-100 pb-3">Contenido del Banner</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Título <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                    placeholder="Ej: Accedé a experiencias exclusivas en Tandil"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Subtítulo</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                    placeholder="Ej: Suscribite a Premium y descubrí lugares, itinerarios y más."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Texto del botón CTA</label>
                    <input type="text" name="cta_text" value="{{ old('cta_text', $banner->cta_text) }}"
                        placeholder="Ej: Ver planes Premium"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">URL del botón CTA</label>
                    <input type="url" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}"
                        placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Color de fondo</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="bg_color" value="{{ old('bg_color', $banner->bg_color ?: '#2D6A4F') }}"
                            class="w-10 h-10 border border-gray-300 rounded-lg cursor-pointer p-0.5">
                        <span class="text-xs text-gray-500">(se usa si no hay imagen de fondo)</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Posición <span class="text-red-500">*</span></label>
                    <input type="number" name="position" value="{{ old('position', $banner->position ?: 3) }}" min="1" max="999" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788] focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">Aparece después del lugar número N en la grilla.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ old('active', $banner->active ?? true) ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-10 h-6 bg-gray-300 peer-checked:bg-[#2D6A4F] rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:w-5 after:h-5 after:transition-transform peer-checked:after:translate-x-4"></div>
                </label>
                <span class="text-sm font-medium text-gray-700">Banner activo (visible en el sitio)</span>
            </div>
        </div>

        {{-- Imágenes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h2 class="font-semibold text-gray-800 text-base border-b border-gray-100 pb-3">Imágenes</h2>
            <p class="text-sm text-gray-500">Podés subir una imagen para desktop y otra para mobile. Si no subís imagen, se usará el color de fondo elegido.</p>

            {{-- Desktop image --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Imagen Desktop
                    <span class="text-xs text-gray-400 font-normal ml-1">(recomendado: 1200×300px, JPG/PNG, máx 4MB)</span>
                </label>
                @if($banner->image_desktop)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $banner->image_desktop) }}" alt="Desktop"
                            class="h-24 w-full object-cover rounded-lg border border-gray-200">
                        <label class="inline-flex items-center gap-1.5 mt-2 text-xs text-red-600 cursor-pointer">
                            <input type="checkbox" name="delete_image_desktop" value="1" class="rounded">
                            Eliminar imagen desktop
                        </label>
                    </div>
                @endif
                <input type="file" name="image_desktop" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2D6A4F]/10 file:text-[#2D6A4F] hover:file:bg-[#2D6A4F]/20 cursor-pointer">
            </div>

            {{-- Mobile image --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Imagen Mobile
                    <span class="text-xs text-gray-400 font-normal ml-1">(recomendado: 400×400px cuadrada, JPG/PNG, máx 2MB)</span>
                </label>
                @if($banner->image_mobile)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $banner->image_mobile) }}" alt="Mobile"
                            class="h-24 w-32 object-cover rounded-lg border border-gray-200">
                        <label class="inline-flex items-center gap-1.5 mt-2 text-xs text-red-600 cursor-pointer">
                            <input type="checkbox" name="delete_image_mobile" value="1" class="rounded">
                            Eliminar imagen mobile
                        </label>
                    </div>
                @endif
                <input type="file" name="image_mobile" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#2D6A4F]/10 file:text-[#2D6A4F] hover:file:bg-[#2D6A4F]/20 cursor-pointer">
            </div>
        </div>

        {{-- Preview mock --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 text-base border-b border-gray-100 pb-3 mb-4">Vista previa del estilo</h2>
            <div id="banner-preview" class="rounded-xl overflow-hidden relative flex items-center justify-between gap-6 px-8 py-7 text-white" style="background-color: #2D6A4F;">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-amber-400 text-white text-[0.65rem] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">⭐ Premium</span>
                    </div>
                    <h3 id="preview-title" class="text-xl font-bold mb-1">Accedé a experiencias exclusivas</h3>
                    <p id="preview-subtitle" class="text-white/80 text-sm">Suscribite a Premium y descubrí lugares únicos, itinerarios y más.</p>
                    <a id="preview-cta" class="inline-flex items-center gap-2 mt-4 bg-white text-[#2D6A4F] font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-amber-400 hover:text-white transition">
                        Ver planes Premium
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="hidden sm:flex items-center justify-center flex-shrink-0">
                    <svg class="w-20 h-20 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">* Preview aproximado. El estilo final depende de si subís imagen de fondo.</p>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.lugar-banners.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-2.5 text-sm font-semibold bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white rounded-lg transition">
                {{ $banner->exists ? 'Guardar cambios' : 'Crear Banner' }}
            </button>
        </div>
    </form>
</div>

<script>
    // Live preview update
    const titleInput = document.querySelector('input[name="title"]');
    const subtitleInput = document.querySelector('input[name="subtitle"]');
    const ctaTextInput = document.querySelector('input[name="cta_text"]');
    const colorInput = document.querySelector('input[name="bg_color"]');
    const preview = document.getElementById('banner-preview');

    function updatePreview() {
        document.getElementById('preview-title').textContent = titleInput.value || 'Título del banner';
        document.getElementById('preview-subtitle').textContent = subtitleInput.value || 'Subtítulo del banner';
        document.getElementById('preview-cta').textContent = ctaTextInput.value || 'Ver planes';
        preview.style.backgroundColor = colorInput.value;
    }

    titleInput.addEventListener('input', updatePreview);
    subtitleInput.addEventListener('input', updatePreview);
    ctaTextInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updatePreview);
    updatePreview();
</script>
@endsection
