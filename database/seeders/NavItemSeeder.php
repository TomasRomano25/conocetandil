<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['key' => 'lugares',  'label' => 'Lugares',  'route_name' => 'lugares',        'order' => 1],
            ['key' => 'guias',    'label' => 'Guías',    'route_name' => 'guias',           'order' => 2],
            ['key' => 'hoteles',  'label' => 'Hoteles',  'route_name' => 'hoteles.index',   'order' => 3],
            ['key' => 'contacto', 'label' => 'Contacto', 'route_name' => 'contacto',        'order' => 4],
        ];

        foreach ($items as $item) {
            NavItem::firstOrCreate(
                ['key' => $item['key']],
                array_merge($item, ['is_visible' => true])
            );
        }
    }
}
