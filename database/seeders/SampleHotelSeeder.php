<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelPlan;
use App\Models\HotelRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleHotelSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@conocetandil.com')->first();

        if (! $admin) {
            return;
        }

        $plans = HotelPlan::pluck('id', 'tier'); // [1 => id, 2 => id, 3 => id]

        if ($plans->isEmpty()) {
            return;
        }

        $samples = [
            [
                'tier'              => 3,
                'name'              => 'Las Sierras Boutique Hotel',
                'hotel_type'        => 'Hotel',
                'slug'              => 'las-sierras-boutique-hotel',
                'short_description' => 'Hotel boutique de lujo con spa y vistas panorámicas a las sierras de Tandil.',
                'description'       => 'Las Sierras Boutique Hotel es una experiencia única en el corazón de las sierras de Tandil. Nuestras habitaciones y suites combinan diseño contemporáneo con la calidez del entorno natural. Contamos con spa completo, pileta climatizada, restaurante gourmet y atención personalizada para hacer de tu estadía un momento inolvidable.',
                'address'           => 'Av. Don Bosco 1234, Tandil',
                'phone'             => '(249) 444-5678',
                'email'             => 'info@lassierrasboutique.com.ar',
                'website'           => 'https://lassierrasboutique.com.ar',
                'stars'             => 5,
                'checkin_time'      => '14:00',
                'checkout_time'     => '10:00',
                'services'          => ['WiFi', 'Pileta', 'Spa', 'Desayuno incluido', 'Estacionamiento', 'Aire acondicionado'],
                'rooms'             => [
                    ['name' => 'Suite Vista Panorámica', 'capacity' => 2, 'price' => 45000, 'description' => 'Suite de lujo con terraza privada y vista 360° a las sierras.'],
                    ['name' => 'Habitación Doble Superior', 'capacity' => 2, 'price' => 28000, 'description' => 'Habitación amplia con cama king size y baño en suite.'],
                    ['name' => 'Habitación Simple', 'capacity' => 1, 'price' => 18000, 'description' => 'Habitación confortable con todo lo necesario para una estadía perfecta.'],
                ],
            ],
            [
                'tier'              => 2,
                'name'              => 'Cabañas El Roble',
                'hotel_type'        => 'Cabaña',
                'slug'              => 'cabanas-el-roble',
                'short_description' => 'Cabañas de madera rodeadas de naturaleza, ideales para escapadas en familia o pareja.',
                'description'       => 'Cabañas El Roble te ofrece el refugio perfecto en medio de la naturaleza tandilense. Cada cabaña cuenta con parrilla propia, cocina equipada y galería con vista al bosque. Aceptamos mascotas y contamos con estacionamiento privado. A solo 10 minutos del centro de Tandil.',
                'address'           => 'Camino a Cerro El Centinela km 3, Tandil',
                'phone'             => '(249) 455-1234',
                'email'             => 'reservas@cabanaselroble.com.ar',
                'website'           => null,
                'stars'             => 4,
                'checkin_time'      => '15:00',
                'checkout_time'     => '11:00',
                'services'          => ['WiFi', 'Parrilla', 'Pet Friendly', 'Estacionamiento'],
                'rooms'             => [],
            ],
            [
                'tier'              => 1,
                'name'              => 'Hostel Centro Tandil',
                'hotel_type'        => 'Hostel',
                'slug'              => 'hostel-centro-tandil',
                'short_description' => 'El punto de encuentro del viajero: céntrico, económico y con onda.',
                'description'       => 'Hostel Centro Tandil es la opción ideal para viajeros que quieren explorar la ciudad sin gastar de más. Ubicado a dos cuadras de la plaza principal, ofrecemos dormis compartidos y habitaciones privadas, cocina común, sala de juegos y desayuno incluido. La mejor relación calidad-precio de Tandil.',
                'address'           => 'Belgrano 654, Tandil',
                'phone'             => '(249) 422-9876',
                'email'             => 'hola@hostelcentrotandil.com.ar',
                'website'           => null,
                'stars'             => 3,
                'checkin_time'      => null,
                'checkout_time'     => null,
                'services'          => ['WiFi', 'Desayuno incluido'],
                'rooms'             => [],
            ],
        ];

        foreach ($samples as $data) {
            $planId = $plans[$data['tier']] ?? null;
            if (! $planId) continue;

            $hotel = Hotel::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'user_id'           => $admin->id,
                    'plan_id'           => $planId,
                    'name'              => $data['name'],
                    'hotel_type'        => $data['hotel_type'],
                    'short_description' => $data['short_description'],
                    'description'       => $data['description'],
                    'address'           => $data['address'],
                    'phone'             => $data['phone'],
                    'email'             => $data['email'],
                    'website'           => $data['website'],
                    'stars'             => $data['stars'],
                    'checkin_time'      => $data['checkin_time'],
                    'checkout_time'     => $data['checkout_time'],
                    'services'          => $data['services'],
                    'cover_image'       => null,
                    'status'            => 'active',
                    'approved_at'       => now(),
                    'expires_at'        => now()->addYear(),
                ]
            );

            // Add rooms for tier 3 (only on first create)
            if ($hotel->wasRecentlyCreated && ! empty($data['rooms'])) {
                foreach ($data['rooms'] as $i => $room) {
                    $hotel->rooms()->create([
                        'name'            => $room['name'],
                        'description'     => $room['description'],
                        'capacity'        => $room['capacity'],
                        'price_per_night' => $room['price'],
                        'order'           => $i,
                    ]);
                }
            }
        }
    }
}
