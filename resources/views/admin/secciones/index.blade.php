@extends('layouts.admin')

@section('title', 'Editar Secciones')
@section('header', 'Editar Secciones del Sitio')

@section('content')

@php
    $activeTab = request('tab', 'inicio');

    $tabs = [
        'inicio'   => 'Inicio',
        'lugares'  => 'Lugares',
        'guias'    => 'Guías',
        'contacto' => 'Contacto',
        'premium'  => 'Premium',
    ];

    $tabSections = [
        'inicio'   => ['hero', 'banner', 'featured', 'cta_guias', 'cta_contacto'],
        'lugares'  => ['lugares_hero'],
        'guias'    => ['guias_hero'],
        'contacto' => ['contacto_hero'],
        'premium'  => ['premium_hero'],
    ];

    $bannerKeys = ['hero', 'lugares_hero', 'guias_hero', 'contacto_hero', 'premium_hero'];

    $sectionLabels = [
        'hero'          => 'Hero principal',
        'banner'        => 'Banner Promocional',
        'featured'      => 'Lugares Destacados',
        'cta_guias'     => 'Call to Action — Guías',
        'cta_contacto'  => 'Call to Action — Contacto',
        'lugares_hero'  => 'Hero — Lugares',
        'guias_hero'    => 'Hero — Guías',
        'contacto_hero' => 'Hero — Contacto',
        'premium_hero'  => 'Hero — Premium',
    ];

    $currentKeys = $tabSections[$activeTab] ?? $tabSections['inicio'];
    $bannerKey   = collect($currentKeys)->first(fn($k) => in_array($k, $bannerKeys));
    $bannerSection = $bannerKey && isset($sections[$bannerKey]) ? $sections[$bannerKey] : null;
@endphp

{{-- Tab navigation --}}
<div class="flex gap-1 mb-8 bg-white rounded-xl border border-gray-100 shadow-sm p-1 w-fit flex-wrap">
    @foreach ($tabs as $tab => $label)
        <a href="{{ route('admin.secciones.index', ['tab' => $tab]) }}"
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition
                  {{ $activeTab === $tab ? 'bg-[#2D6A4F] text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Banner Card --}}
@if ($bannerSection)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-[#1A1A1A]">Imagen del Banner</h2>
                <p class="text-sm text-gray-500 mt-0.5">Imagen de fondo del encabezado de esta página. Sin imagen se usa el fondo verde por defecto.</p>
            </div>
            @if ($bannerSection->image)
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Imagen activa</span>
            @else
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Sin imagen</span>
            @endif
        </div>

        @if ($bannerSection->image)
            <div class="mb-4 relative w-full">
                <img src="{{ asset('storage/' . $bannerSection->image) }}"
                     alt="Banner actual"
                     class="w-full h-52 object-cover rounded-lg border border-gray-200">
            </div>
            <form method="POST" action="{{ route('admin.inicio.banner.delete', $bannerKey) }}"
                  onsubmit="return confirm('¿Eliminar esta imagen?')" class="mb-5">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition">
                    Eliminar imagen actual
                </button>
            </form>
        @else
            <div class="mb-5 w-full h-52 bg-gradient-to-br from-[#2D6A4F]/10 to-[#52B788]/10 rounded-lg border border-dashed border-gray-300 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-[#2D6A4F]/30 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-400">Sin imagen — se usa fondo verde</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.inicio.banner.update', $bannerKey) }}"
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
                {{ $bannerSection->image ? 'Reemplazar imagen' : 'Subir imagen' }}
            </button>
        </form>
        @error('image')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif

{{-- Section content cards --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-base font-semibold text-[#1A1A1A]">Contenido de las Secciones</h2>
        <p class="text-sm text-gray-500 mt-0.5">Editá el texto de cada sección y su visibilidad en el sitio.</p>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach ($currentKeys as $key)
            @if (isset($sections[$key]))
                @php $sec = $sections[$key]; @endphp
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="font-medium text-[#1A1A1A] text-sm">{{ $sectionLabels[$key] ?? $key }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-500">{{ $key }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $sec->title }}</p>
                        @if ($sec->subtitle)
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ Str::limit($sec->subtitle, 100) }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $sec->is_visible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $sec->is_visible ? 'Visible' : 'Oculto' }}
                        </span>
                        <button onclick="openModal({{ $sec->id }})"
                            class="bg-[#2D6A4F]/10 hover:bg-[#2D6A4F]/20 text-[#2D6A4F] font-medium text-sm px-4 py-1.5 rounded-lg transition">
                            Editar
                        </button>
                    </div>
                </div>
            @else
                <div class="px-6 py-4 flex items-center gap-3 text-sm text-gray-400">
                    <span class="font-mono bg-gray-50 px-2 py-0.5 rounded">{{ $key }}</span>
                    <span>Sección no encontrada — corré el seeder para crearla.</span>
                </div>
            @endif
        @endforeach
    </div>
</div>

{{-- Contact Info form (Contacto tab only) --}}
@if ($activeTab === 'contacto')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-[#1A1A1A] mb-1">Información de Contacto</h2>
        <p class="text-sm text-gray-500 mb-5">Datos que se muestran en la página de Contacto del sitio público.</p>

        <form method="POST" action="{{ route('admin.secciones.contact-info.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $contactInfo['address']) }}"
                    placeholder="9 de Julio 555, Tandil..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788] text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $contactInfo['phone']) }}"
                    placeholder="(0249) 444-1234"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788] text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $contactInfo['email']) }}"
                    placeholder="info@conocetandil.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788] text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Horarios</label>
                <input type="text" name="contact_hours" value="{{ old('contact_hours', $contactInfo['hours']) }}"
                    placeholder="Lun–Vie 9–18hs"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788] text-sm">
            </div>
            <div class="sm:col-span-2 flex justify-end pt-1">
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2.5 px-8 rounded-lg transition">
                    Guardar información de contacto
                </button>
            </div>
        </form>
    </div>
@endif

{{-- Edit Modal --}}
<div id="edit-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <h3 class="text-lg font-bold text-[#1A1A1A]">Editar Sección</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="title" id="modal-title" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <input type="text" name="subtitle" id="modal-subtitle"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea name="content" id="modal-content" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
                </div>

                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_visible" id="modal-visible" value="1"
                            class="rounded border-gray-300 text-[#2D6A4F] focus:ring-[#52B788]">
                        <span class="ml-2 text-sm text-gray-700">Visible en el sitio</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-900 text-sm font-medium">Cancelar</button>
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const sectionsData = @json($sections);

    function openModal(id) {
        const sec = Object.values(sectionsData).find(s => s.id == id);
        if (!sec) return;
        document.getElementById('edit-form').action = '{{ url("admin/inicio") }}/' + id;
        document.getElementById('modal-title').value  = sec.title    || '';
        document.getElementById('modal-subtitle').value = sec.subtitle || '';
        document.getElementById('modal-content').value  = sec.content  || '';
        document.getElementById('modal-visible').checked = !!sec.is_visible;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    document.getElementById('edit-modal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
</script>

@endsection
