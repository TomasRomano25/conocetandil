<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'inicio',   'label' => 'Inicio',   'route_name' => 'inicio',          'order' => 1, 'is_visible' => false],
            ['key' => 'lugares',  'label' => 'Lugares',  'route_name' => 'lugares',          'order' => 2, 'is_visible' => true],
            ['key' => 'guias',    'label' => 'Guías',    'route_name' => 'guias',            'order' => 3, 'is_visible' => true],
            ['key' => 'hoteles',  'label' => 'Hoteles',  'route_name' => 'hoteles.index',    'order' => 4, 'is_visible' => true],
            ['key' => 'premium',  'label' => 'Premium',  'route_name' => 'premium.upsell',   'order' => 5, 'is_visible' => false],
            ['key' => 'contacto', 'label' => 'Contacto', 'route_name' => 'contacto',         'order' => 6, 'is_visible' => true],
        ];

        foreach ($items as $item) {
            NavItem::firstOrCreate(
                ['key' => $item['key']],
                $item
            );
        }
    }
}
