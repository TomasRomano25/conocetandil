<div class="room-row border border-gray-200 rounded-xl p-4 space-y-3">
    <div class="flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-700">Habitación <span class="room-num">{{ $i + 1 }}</span></p>
        <button type="button" onclick="removeRoom(this)" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
            <input type="text" name="rooms[{{ $i }}][name]" value="{{ $room->name }}" placeholder="Nombre de la habitación *" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
        </div>
        <div>
            <input type="number" name="rooms[{{ $i }}][capacity]" value="{{ $room->capacity }}" placeholder="Capacidad (personas)" min="1"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
        </div>
        <div>
            <input type="number" name="rooms[{{ $i }}][price]" value="{{ $room->price_per_night }}" placeholder="Precio/noche (opcional)" min="0" step="0.01"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
        </div>
        <div class="col-span-2">
            <textarea name="rooms[{{ $i }}][description]" rows="2" placeholder="Descripción (opcional)"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ $room->description }}</textarea>
        </div>
        <div class="col-span-2">
            @if ($room->image)
                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="h-20 rounded-lg object-cover mb-2">
            @endif
            <input type="file" name="room_images[{{ $i }}]" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            <p class="text-xs text-gray-400 mt-1">Imagen de la habitación (opcional)</p>
        </div>
    </div>
</div>
