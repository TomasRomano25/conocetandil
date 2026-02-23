<section class="relative overflow-hidden bg-[#111827] py-20 md:py-28">
    {{-- Decorative large text --}}
    <div class="absolute right-0 bottom-0 text-[10rem] md:text-[14rem] font-black text-white/[0.025] leading-none select-none pointer-events-none translate-y-4 translate-x-4">GUÍAS</div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-10">

            {{-- Left: text --}}
            <div class="max-w-xl">
                <span class="text-[#52B788] text-xs font-semibold uppercase tracking-[0.15em] mb-4 block">Experiencias curadas</span>
                <h2 class="text-4xl md:text-5xl font-black text-white leading-[1.05] tracking-tight">
                    {{ $sections['cta_guias']->title ?? '¿Querés explorar Tandil con un guía?' }}
                </h2>
                @if($sections['cta_guias']->subtitle ?? null)
                    <p class="text-gray-400 text-lg mt-5 leading-relaxed">{{ $sections['cta_guias']->subtitle }}</p>
                @else
                    <p class="text-gray-500 text-lg mt-5 leading-relaxed">Accedé a guías locales especializados y llevá tu experiencia al siguiente nivel.</p>
                @endif
            </div>

            {{-- Right: CTA --}}
            <div class="flex-shrink-0">
                <a href="{{ route('guias') }}"
                    class="inline-flex items-center gap-3 bg-[#2D6A4F] hover:bg-[#52B788] text-white font-semibold px-8 py-4 rounded-2xl transition-all duration-200 text-base shadow-lg hover:shadow-xl hover:-translate-y-0.5 group">
                    Explorar Guías
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
