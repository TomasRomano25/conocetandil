{{--
    Lugar promotional banner partial
    Variables: $banner (LugarBanner model)
--}}
<div class="relative rounded-2xl overflow-hidden shadow-lg text-white"
     style="background-color: {{ $banner->bg_color }}">

    {{-- Desktop background image --}}
    @if($banner->image_desktop)
        <div class="hidden sm:block absolute inset-0">
            <img src="{{ asset('storage/' . $banner->image_desktop) }}"
                 alt="{{ $banner->title }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
        </div>
    @endif

    {{-- Mobile background image --}}
    @if($banner->image_mobile)
        <div class="sm:hidden absolute inset-0">
            <img src="{{ asset('storage/' . $banner->image_mobile) }}"
                 alt="{{ $banner->title }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/55"></div>
        </div>
    @endif

    {{-- Content --}}
    <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-6 px-8 py-8 sm:py-10">

        {{-- Left: text --}}
        <div class="flex-1 min-w-0 text-center sm:text-left">
            {{-- Premium badge --}}
            <div class="flex items-center justify-center sm:justify-start gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 bg-amber-400 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/></svg>
                    Premium
                </span>
            </div>

            <h3 class="text-2xl sm:text-3xl font-bold leading-tight mb-2 drop-shadow-sm">
                {{ $banner->title }}
            </h3>

            @if($banner->subtitle)
                <p class="text-white/85 text-sm sm:text-base leading-relaxed max-w-xl">
                    {{ $banner->subtitle }}
                </p>
            @endif

            @if($banner->cta_url && $banner->cta_text)
                <div class="mt-5">
                    <a href="{{ $banner->cta_url }}"
                       class="inline-flex items-center gap-2 bg-white text-[#2D6A4F] hover:bg-amber-400 hover:text-white font-bold text-sm px-6 py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                        {{ $banner->cta_text }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- Right: decorative icon (hidden on mobile if there's a background image) --}}
        @if(!$banner->image_desktop)
            <div class="hidden sm:flex items-center justify-center flex-shrink-0 opacity-20">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 19l2-9 4.5 3L12 5l3.5 8L20 10l2 9H2z"/>
                </svg>
            </div>
        @endif

    </div>
</div>
