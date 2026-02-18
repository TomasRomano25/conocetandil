<a href="{{ route('hoteles.show', $hotel) }}"
    class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 group block">

    {{-- Cover image --}}
    <div class="relative h-48 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center overflow-hidden">
        @if ($hotel->cover_image)
            <img src="{{ asset('storage/' . $hotel->cover_image) }}" alt="{{ $hotel->name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        @endif

        {{-- Hotel type badge (top-left) --}}
        @if ($hotel->hotel_type)
            <div class="absolute top-3 left-3 bg-black/50 text-white text-[0.65rem] font-semibold px-2 py-1 rounded-full backdrop-blur-sm">
                {{ $hotel->hotel_type }}
            </div>
        @endif

        {{-- Stars (bottom-left) --}}
        @if ($hotel->stars)
            <div class="absolute bottom-3 left-3 flex gap-0.5">
                @for ($s = 1; $s <= 5; $s++)
                    <svg class="w-3.5 h-3.5 {{ $s <= $hotel->stars ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                @endfor
            </div>
        @endif

        {{-- Diamante mark (top-right) for featured section --}}
        @if ($hotel->plan && $hotel->plan->tier === 3)
            <div class="absolute top-3 right-3 text-amber-400 text-sm leading-none" title="Destacado">✦</div>
        @endif
    </div>

    <div class="p-5">
        <h3 class="text-lg font-bold text-[#1A1A1A] mb-1 group-hover:text-[#2D6A4F] transition-colors">{{ $hotel->name }}</h3>
        <p class="text-[#2D6A4F] text-sm font-medium mb-2 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $hotel->address }}
        </p>
        @if ($hotel->short_description)
            <p class="text-gray-600 text-sm">{{ Str::limit($hotel->short_description, 100) }}</p>
        @endif
    </div>
</a>
