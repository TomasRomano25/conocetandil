<?php

namespace Database\Seeders;

use App\Models\InicioSection;
use Illuminate\Database\Seeder;

class InicioSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'title' => 'Descubrí Tandil',
                'subtitle' => 'Explorá las sierras, saboreá sus productos artesanales y viví experiencias únicas en uno de los destinos más encantadores de Argentina.',
                'content' => null,
                'order' => 1,
                'is_visible' => true,
            ],
            [
                'key' => 'banner',
                'title' => 'Espacio reservado para promociones de empresas asociadas',
                'subtitle' => null,
                'content' => null,
                'order' => 2,
                'is_visible' => true,
            ],
            [
                'key' => 'featured',
                'title' => 'Lugares Destacados',
                'subtitle' => 'Descubrí los rincones más increíbles que Tandil tiene para ofrecerte.',
                'content' => null,
                'order' => 3,
                'is_visible' => true,
            ],
            [
                'key' => 'cta_guias',
                'title' => '¿Querés explorar Tandil con un guía?',
                'subtitle' => 'Nuestras guías te llevan por los mejores recorridos, con toda la info que necesitás para disfrutar al máximo.',
                'content' => null,
                'order' => 4,
                'is_visible' => true,
            ],
            [
                'key' => 'cta_contacto',
                'title' => '¿Tenés alguna consulta?',
                'subtitle' => 'Escribinos y te respondemos a la brevedad.',
                'content' => null,
                'order' => 5,
                'is_visible' => true,
            ],
            [
                'key' => 'lugares_hero',
                'title' => 'Lugares para Visitar',
                'subtitle' => 'Explorá todos los rincones que hacen de Tandil un destino único.',
                'content' => null,
                'order' => 6,
                'is_visible' => true,
            ],
            [
                'key' => 'guias_hero',
                'title' => 'Guías de Tandil',
                'subtitle' => 'Conseguí la guía perfecta para tu próxima aventura en las sierras.',
                'content' => null,
                'order' => 7,
                'is_visible' => true,
            ],
            [
                'key' => 'contacto_hero',
                'title' => 'Contacto',
                'subtitle' => '¿Tenés alguna consulta? Escribinos y te respondemos a la brevedad.',
                'content' => null,
                'order' => 8,
                'is_visible' => true,
            ],
            [
                'key' => 'premium_hero',
                'title' => 'Conoce Tandil Premium',
                'subtitle' => 'Accedé a itinerarios exclusivos, guías especializadas y contenido único para vivir Tandil al máximo.',
                'content' => null,
                'order' => 9,
                'is_visible' => true,
            ],
        ];

        foreach ($sections as $section) {
            InicioSection::updateOrCreate(['key' => $section['key']], $section);
        }
    }
}
