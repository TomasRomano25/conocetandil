<section class="py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-[#1A1A1A]">{{ $sections['featured']->title ?? 'Lugares Destacados' }}</h2>
            <p class="mt-4 text-gray-600 max-w-xl mx-auto">{{ $sections['featured']->subtitle ?? '' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredLugares as $lugar)
                <a href="{{ route('lugar.show', $lugar) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 block">
                    <div class="h-48 bg-gradient-to-br from-[#2D6A4F]/20 to-[#52B788]/20 flex items-center justify-center">
                        @if ($lugar->cover_image)
                            <img src="{{ asset('storage/' . $lugar->cover_image) }}" alt="{{ $lugar->title }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-[#2D6A4F]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-[#1A1A1A] mb-2">{{ $lugar->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ Str::limit($lugar->description, 120) }}</p>
                        <span class="inline-block mt-4 text-[#2D6A4F] font-semibold text-sm">
                            Ver más &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
