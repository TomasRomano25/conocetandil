<?php

namespace Database\Seeders;

use App\Models\HotelPlan;
use Illuminate\Database\Seeder;

class HotelPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'                => 'Básico',
                'slug'                => 'basico',
                'description'         => 'Presencia básica en el directorio. Una imagen de portada, descripción y formulario de contacto.',
                'price'               => 4999.00,
                'tier'                => 1,
                'max_images'          => 1,
                'has_services'        => false,
                'has_rooms'           => false,
                'has_gallery_captions'=> false,
                'is_featured'         => false,
                'duration_months'     => 12,
                'is_active'           => true,
                'sort_order'          => 1,
            ],
            [
                'name'                => 'Estándar',
                'slug'                => 'estandar',
                'description'         => 'Galería de hasta 5 imágenes, servicios, estrellas, horarios de check-in/out y formulario de contacto.',
                'price'               => 9999.00,
                'tier'                => 2,
                'max_images'          => 5,
                'has_services'        => true,
                'has_rooms'           => false,
                'has_gallery_captions'=> false,
                'is_featured'         => false,
                'duration_months'     => 12,
                'is_active'           => true,
                'sort_order'          => 2,
            ],
            [
                'name'                => 'Diamante',
                'slug'                => 'diamante',
                'description'         => 'La experiencia completa: galería con descripciones, sección de habitaciones, servicios, página con pestañas y posición destacada.',
                'price'               => 19999.00,
                'tier'                => 3,
                'max_images'          => 20,
                'has_services'        => true,
                'has_rooms'           => true,
                'has_gallery_captions'=> true,
                'is_featured'         => true,
                'duration_months'     => 12,
                'is_active'           => true,
                'sort_order'          => 3,
            ],
        ];

        foreach ($plans as $plan) {
            HotelPlan::firstOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
