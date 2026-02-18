@extends('layouts.app')
@section('title', isset($hotel) ? 'Editar Hotel' : 'Registrar Hotel — ' . $plan->name)

@section('content')

<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-sm text-gray-500 mb-1">{{ isset($hotel) ? 'Editar hotel · ' : 'Plan: ' }} {{ $plan->name }}</p>
            <h1 class="text-2xl font-bold text-[#1A1A1A]">
                {{ isset($hotel) ? 'Editar ' . $hotel->name : 'Registrar mi hotel' }}
            </h1>
        </div>

        <form
            id="hotel-register-form"
            method="POST"
            action="{{ isset($hotel) ? route('hoteles.owner.update') : route('hoteles.owner.store', $plan) }}"
            enctype="multipart/form-data"
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7 space-y-6">
            @csrf
            @isset($hotel)
                @method('PUT')
            @endisset
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-hotel-register">

            {{-- Basic info --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del hotel *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $hotel->name ?? '') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="hotel_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de alojamiento</label>
                <input type="text" name="hotel_type" id="hotel_type"
                    value="{{ old('hotel_type', $hotel->hotel_type ?? '') }}"
                    placeholder="ej. Hotel, Cabaña, Hostel, Apart Hotel, ..."
                    list="hotel-type-suggestions"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <datalist id="hotel-type-suggestions">
                    <option value="Hotel">
                    <option value="Cabaña">
                    <option value="Hostel">
                    <option value="Apart Hotel">
                    <option value="Bed & Breakfast">
                    <option value="Camping">
                    <option value="Posada">
                    <option value="Glamping">
                </datalist>
                @error('hotel_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción corta</label>
                <input type="text" name="short_description" id="short_description"
                    value="{{ old('short_description', $hotel->short_description ?? '') }}"
                    placeholder="Una frase para el listado (máx. 255 caracteres)"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('short_description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción completa *</label>
                <textarea name="description" id="description" rows="5" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('description', $hotel->description ?? '') }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                <input type="text" name="address" id="address" value="{{ old('address', $hotel->address ?? '') }}" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                @error('address') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $hotel->phone ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de contacto *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $hotel->email ?? '') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Cover image --}}
            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Foto de portada</label>
                @isset($hotel)
                    @if ($hotel->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}" class="h-32 rounded-lg object-cover">
                            <p class="text-xs text-gray-500 mt-1">Imagen actual. Subí una nueva para reemplazarla.</p>
                        </div>
                    @endif
                @endisset
                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewImage(event, 'cover-preview')">
                <img id="cover-preview" class="mt-2 h-32 rounded-lg object-cover hidden">
                @error('cover_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tier 2+ fields --}}
            @if ($plan->tier >= 2)
            <hr class="border-gray-200">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Información adicional</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Sitio web</label>
                    <input type="text" name="website" id="website" value="{{ old('website', $hotel->website ?? '') }}" placeholder="https://..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                    @error('website') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stars" class="block text-sm font-medium text-gray-700 mb-1">Estrellas</label>
                    <select name="stars" id="stars"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788] bg-white">
                        <option value="">Sin clasificar</option>
                        @for ($s = 1; $s <= 5; $s++)
                            <option value="{{ $s }}" @selected(old('stars', $hotel->stars ?? null) == $s)>{{ $s }} estrella{{ $s > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="checkin_time" class="block text-sm font-medium text-gray-700 mb-1">Horario check-in</label>
                    <input type="text" name="checkin_time" id="checkin_time" placeholder="ej. 14:00"
                        value="{{ old('checkin_time', $hotel->checkin_time ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
                <div>
                    <label for="checkout_time" class="block text-sm font-medium text-gray-700 mb-1">Horario check-out</label>
                    <input type="text" name="checkout_time" id="checkout_time" placeholder="ej. 10:00"
                        value="{{ old('checkout_time', $hotel->checkout_time ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                </div>
            </div>

            <div>
                <label for="services" class="block text-sm font-medium text-gray-700 mb-1">Servicios</label>
                <input type="text" name="services" id="services"
                    value="{{ old('services', isset($hotel) && $hotel->services ? implode(', ', $hotel->services) : '') }}"
                    placeholder="WiFi, Estacionamiento, Desayuno, Pileta, ..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <p class="text-xs text-gray-400 mt-1">Separados por coma</p>
                @error('services') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Gallery --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Galería de imágenes (máximo {{ $plan->max_images }})
                </label>
                @isset($hotel)
                    @if ($hotel->images->count())
                        <div class="flex gap-3 flex-wrap mb-2">
                            @foreach ($hotel->images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Galería" class="h-24 w-24 rounded-lg object-cover">
                                    <label class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition" title="Eliminar">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only delete-img-check">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mb-2">Hover en la imagen y click en X para marcarla a eliminar.</p>
                    @endif
                @endisset
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]"
                    onchange="previewGallery(event)">
                <div id="gallery-preview" class="mt-2 flex gap-2 flex-wrap"></div>
                @error('gallery.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif

            {{-- Tier 3: Rooms --}}
            @if ($plan->tier >= 3)
            <hr class="border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Habitaciones</p>
                <button type="button" onclick="addRoom()"
                    class="text-xs font-semibold text-[#2D6A4F] hover:underline">+ Agregar habitación</button>
            </div>
            <div id="rooms-container" class="space-y-4">
                @isset($hotel)
                    @foreach ($hotel->rooms as $i => $room)
                    @include('hoteles._room_row', ['i' => $i, 'room' => $room])
                    @endforeach
                @endisset
            </div>
            @endif

            <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-3 px-8 rounded-xl transition">
                    {{ isset($hotel) ? 'Guardar cambios' : 'Registrar hotel' }}
                </button>
                @isset($hotel)
                    <a href="{{ route('hoteles.owner.panel') }}" class="text-gray-500 hover:text-gray-900 text-sm">Cancelar</a>
                @else
                    <a href="{{ route('hoteles.owner.planes') }}" class="text-gray-500 hover:text-gray-900 text-sm">Cambiar plan</a>
                @endisset
            </div>
        </form>
    </div>
</section>

<template id="room-template">
    <div class="room-row border border-gray-200 rounded-xl p-4 space-y-3">
        <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700">Habitación <span class="room-num"></span></p>
            <button type="button" onclick="removeRoom(this)" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <input type="text" name="rooms[IDX][name]" placeholder="Nombre de la habitación *" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <input type="number" name="rooms[IDX][capacity]" placeholder="Capacidad (personas)" min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div>
                <input type="number" name="rooms[IDX][price]" placeholder="Precio/noche (opcional)" min="0" step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            </div>
            <div class="col-span-2">
                <textarea name="rooms[IDX][description]" rows="2" placeholder="Descripción (opcional)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]"></textarea>
            </div>
            <div class="col-span-2">
                <input type="file" name="room_images[IDX]" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
                <p class="text-xs text-gray-400 mt-1">Imagen de la habitación (opcional)</p>
            </div>
        </div>
    </div>
</template>

<script>
let roomCount = {{ isset($hotel) ? $hotel->rooms->count() : 0 }};

function addRoom() {
    const template = document.getElementById('room-template').innerHTML;
    const html = template.replace(/IDX/g, roomCount).replace('<span class="room-num"></span>', roomCount + 1);
    document.getElementById('rooms-container').insertAdjacentHTML('beforeend', html);
    roomCount++;
    updateRoomNumbers();
}

function removeRoom(btn) {
    btn.closest('.room-row').remove();
    updateRoomNumbers();
}

function updateRoomNumbers() {
    document.querySelectorAll('.room-num').forEach((el, i) => { el.textContent = i + 1; });
}

function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
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

document.querySelectorAll('.delete-img-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const container = this.closest('.relative');
        container.classList.toggle('opacity-40', this.checked);
        container.classList.toggle('ring-2', this.checked);
        container.classList.toggle('ring-red-500', this.checked);
    });
});
</script>

@if(\App\Models\Configuration::get('recaptcha_site_key') && !isset($hotel))
<script>
document.getElementById('hotel-register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'hotel_register'}).then(function(token) {
            document.getElementById('g-recaptcha-hotel-register').value = token;
            form.submit();
        });
    });
});
</script>
@endif

@endsection
