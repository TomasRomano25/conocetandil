<section class="py-20 md:py-28 bg-[#FAFAF8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
            <div>
                <span class="text-[#2D6A4F] text-xs font-semibold uppercase tracking-[0.15em] mb-3 block">Selección editorial</span>
                <h2 class="text-4xl md:text-5xl font-black text-[#111827] tracking-tight leading-[1.05]">
                    {{ $sections['featured']->title ?? 'Lugares Destacados' }}
                </h2>
                @if($sections['featured']->subtitle ?? null)
                    <p class="text-[#6B7280] text-lg mt-4 max-w-lg leading-relaxed">{{ $sections['featured']->subtitle }}</p>
                @endif
            </div>
            <a href="{{ route('lugares') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#2D6A4F] hover:text-[#245C43] transition-colors flex-shrink-0 group">
                Ver todos los lugares
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($featuredLugares as $lugar)
                <a href="{{ route('lugar.show', $lugar) }}"
                    class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    {{-- Image --}}
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        @if ($lugar->cover_image)
                            <img src="{{ asset('storage/' . $lugar->cover_image) }}" alt="{{ $lugar->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#0F1F16] to-[#2D6A4F]/50 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Category badge --}}
                        @if ($lugar->category)
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-gray-700 text-[0.68rem] font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                {{ $lugar->category }}
                            </span>
                        @endif

                        {{-- Premium badge --}}
                        @if ($lugar->is_premium)
                            <div class="absolute top-3 right-3 flex items-center gap-1 bg-amber-400 text-white text-[0.65rem] font-bold px-2 py-1 rounded-full shadow-sm">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Premium
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <h3 class="text-base font-bold text-[#111827] mb-1.5 group-hover:text-[#2D6A4F] transition-colors leading-snug">
                            {{ $lugar->title }}
                        </h3>
                        <p class="text-[#9CA3AF] text-xs font-medium flex items-center gap-1.5 mb-3">
                            <svg class="w-3.5 h-3.5 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $lugar->direction }}
                        </p>
                        <p class="text-[#6B7280] text-sm leading-relaxed line-clamp-2">{{ Str::limit($lugar->description, 110) }}</p>

                        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[#2D6A4F] text-sm font-semibold">Ver lugar</span>
                            <svg class="w-4 h-4 text-[#2D6A4F] group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
