<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'            => '1 Mes',
                'slug'            => '1-mes',
                'description'     => 'Acceso completo por un mes. Ideal para tu próxima visita.',
                'price'           => 2999.00,
                'duration_months' => 1,
                'features'        => [
                    'Itinerarios Premium curados',
                    'Planificador inteligente',
                    'Orden por momento del día',
                    'Links a Google Maps',
                    'Alertas y consejos editoriales',
                ],
                'active'          => true,
                'sort_order'      => 1,
            ],
            [
                'name'            => '3 Meses',
                'slug'            => '3-meses',
                'description'     => 'Perfecto si querés planificar varias visitas o recomendárselo a alguien.',
                'price'           => 6999.00,
                'duration_months' => 3,
                'features'        => [
                    'Todo lo de 1 mes',
                    'Válido para 3 viajes',
                    'Ahorro vs. compra mensual',
                ],
                'active'          => true,
                'sort_order'      => 2,
            ],
            [
                'name'            => '6 Meses',
                'slug'            => '6-meses',
                'description'     => 'La opción más elegida. Acceso extendido con el mejor balance precio-beneficio.',
                'price'           => 11999.00,
                'duration_months' => 6,
                'features'        => [
                    'Todo lo de 1 mes',
                    'Válido durante 6 meses',
                    'Acceso a novedades y nuevos itinerarios',
                ],
                'active'          => true,
                'sort_order'      => 3,
            ],
            [
                'name'            => '1 Año',
                'slug'            => '1-ano',
                'description'     => 'El mejor precio por mes. Para el viajero frecuente o el amante de Tandil.',
                'price'           => 19999.00,
                'duration_months' => 12,
                'features'        => [
                    'Todo lo de 1 mes',
                    'Acceso completo por un año',
                    'Precio más bajo por mes',
                    'Soporte prioritario',
                ],
                'active'          => true,
                'sort_order'      => 4,
            ],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::firstOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
