<section class="relative bg-[#2D6A4F] text-white overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2D6A4F] to-[#1A1A1A] opacity-90"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $sections['hero']->title ?? 'Descubrí Tandil' }}</h1>
            <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto">
                {{ $sections['hero']->subtitle ?? '' }}
            </p>

            <div class="max-w-xl mx-auto">
                <div class="flex bg-white rounded-lg shadow-lg overflow-hidden">
                    <input type="text" placeholder="Buscá lugares, guías, experiencias..." class="flex-1 px-5 py-4 text-[#1A1A1A] focus:outline-none">
                    <button class="bg-[#52B788] hover:bg-[#2D6A4F] text-white px-6 py-4 transition font-semibold">
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
