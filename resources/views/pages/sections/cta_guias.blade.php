<section class="bg-[#1A1A1A] text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">{{ $sections['cta_guias']->title ?? '¿Querés explorar Tandil con un guía?' }}</h2>
        <p class="text-gray-400 mb-8 max-w-xl mx-auto">{{ $sections['cta_guias']->subtitle ?? '' }}</p>
        <a href="{{ route('guias') }}" class="inline-block bg-[#52B788] hover:bg-[#2D6A4F] text-white font-bold py-3 px-8 rounded-lg transition">
            Ver Guías
        </a>
    </div>
</section>
