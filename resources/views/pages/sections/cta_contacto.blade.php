<section class="py-20 md:py-24 bg-[#FAFAF8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="text-[#2D6A4F] text-xs font-semibold uppercase tracking-[0.15em] mb-5 block">Estamos para ayudarte</span>
            <h2 class="text-4xl md:text-5xl font-black text-[#111827] leading-[1.05] tracking-tight">
                {{ $sections['cta_contacto']->title ?? '¿Tenés alguna consulta?' }}
            </h2>
            @if($sections['cta_contacto']->subtitle ?? null)
                <p class="text-[#6B7280] text-lg mt-5 leading-relaxed">{{ $sections['cta_contacto']->subtitle }}</p>
            @else
                <p class="text-[#6B7280] text-lg mt-5 leading-relaxed">Escribinos y te respondemos a la brevedad. Estamos disponibles para ayudarte a planificar tu visita.</p>
            @endif
            <a href="{{ route('contacto') }}"
                class="inline-flex items-center gap-2.5 bg-[#111827] hover:bg-[#2D6A4F] text-white font-semibold px-8 py-4 rounded-2xl transition-all duration-200 text-base mt-8 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                Escribinos
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
