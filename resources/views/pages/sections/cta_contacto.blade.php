<section class="py-16 bg-[#52B788]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-[#1A1A1A] mb-4">{{ $sections['cta_contacto']->title ?? '¿Tenés alguna consulta?' }}</h2>
        <p class="text-gray-600 mb-8">{{ $sections['cta_contacto']->subtitle ?? '' }}</p>
        <a href="{{ route('contacto') }}" class="inline-block bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-bold py-3 px-8 rounded-lg transition">
            Contactanos
        </a>
    </div>
</section>
