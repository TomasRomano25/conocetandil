@php $heroImage = $sections['hero']->image ?? null; @endphp
<section class="relative overflow-hidden min-h-[88vh] flex items-end" style="background-color: #0F1F16;">
    {{-- Background --}}
    @if ($heroImage)
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
             style="background-image: url('{{ asset('storage/' . $heroImage) }}')"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(15,31,22,0.4) 0%, rgba(15,31,22,0.7) 60%, rgba(15,31,22,0.92) 100%);"></div>
    @else
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 70% 30%, rgba(45,106,79,0.35) 0%, transparent 55%), radial-gradient(ellipse at 10% 80%, rgba(45,106,79,0.2) 0%, transparent 45%);"></div>
    @endif

    {{-- Decorative circles --}}
    <div class="absolute -right-40 -top-40 w-[600px] h-[600px] rounded-full border border-white/[0.04] pointer-events-none"></div>
    <div class="absolute -right-20 -top-20 w-[400px] h-[400px] rounded-full border border-white/[0.04] pointer-events-none"></div>

    {{-- Content --}}
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 pt-8">
        <div class="max-w-3xl">
            {{-- Eyebrow --}}
            <div class="flex items-center gap-2 mb-7">
                <div class="w-1 h-4 rounded-full bg-[#52B788]"></div>
                <span class="text-[#52B788] text-xs font-semibold uppercase tracking-[0.18em]">Tandil, Buenos Aires</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-[5.25rem] font-black text-white leading-[0.92] tracking-tight mb-7">
                {{ $sections['hero']->title ?? 'Descubrí Tandil' }}
            </h1>

            {{-- Subtitle --}}
            <p class="text-white/55 text-lg md:text-xl max-w-xl leading-relaxed mb-10">
                {{ $sections['hero']->subtitle ?? 'Lugares únicos, guías locales e itinerarios curados para que tu visita sea inolvidable.' }}
            </p>

            {{-- Search bar --}}
            <form action="{{ route('lugares') }}" method="GET" class="flex max-w-lg gap-2">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" placeholder="Buscá lugares, experiencias..."
                        class="w-full bg-white/10 backdrop-blur-sm border border-white/15 text-white placeholder-white/35 rounded-2xl pl-11 pr-4 py-3.5 text-sm focus:outline-none focus:bg-white/15 focus:border-white/30 transition-all"
                        autocomplete="off">
                </div>
                <button type="submit"
                    class="bg-[#2D6A4F] hover:bg-[#52B788] text-white font-semibold px-6 py-3.5 rounded-2xl transition-all duration-200 text-sm flex-shrink-0 flex items-center gap-2">
                    Buscar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>

            {{-- Trust signals --}}
            <div class="flex items-center gap-2 mt-7 text-white/30 text-xs">
                <span>300+ lugares</span>
                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                <span>Guías locales</span>
                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                <span>Actualizado 2025</span>
            </div>
        </div>
    </div>
</section>
