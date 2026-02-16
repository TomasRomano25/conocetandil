<?php

namespace Database\Seeders;

use App\Models\Lugar;
use Illuminate\Database\Seeder;

class LugarSeeder extends Seeder
{
    public function run(): void
    {
        $lugares = [
            ['title' => 'Cerro Centinela', 'direction' => 'Ruta 74 Km 5', 'description' => 'Uno de los miradores naturales más visitados, con aerosilla y senderos entre las sierras.', 'featured' => true, 'order' => 1],
            ['title' => 'Piedra Movediza', 'direction' => 'Parque Lítico La Movediza', 'description' => 'Réplica de la famosa roca y parque temático con vistas espectaculares de la ciudad.', 'featured' => true, 'order' => 2],
            ['title' => 'Lago del Fuerte', 'direction' => 'Av. Gobernador Arana', 'description' => 'Paseos en bote, caminatas costeras y gastronomía a orillas del lago.', 'featured' => true, 'order' => 3],
            ['title' => 'Monte Calvario', 'direction' => 'Av. Jesús de la Buena Esperanza', 'description' => 'Vía crucis entre las sierras con capillas y un paisaje serrano inolvidable.', 'featured' => true, 'order' => 4],
            ['title' => 'Reserva Sierra del Tigre', 'direction' => 'Camino a Sierra del Tigre', 'description' => 'Reserva natural con senderos de trekking, flora nativa y fauna silvestre.', 'featured' => true, 'order' => 5],
            ['title' => 'Centro Histórico', 'direction' => 'Plaza Independencia', 'description' => 'Arquitectura histórica, museos, cafés y el corazón comercial de Tandil.', 'featured' => true, 'order' => 6],
            ['title' => 'Balcón de Nogueira', 'direction' => 'Camino de los Cerros', 'description' => 'Punto panorámico con vista a la ciudad y las sierras, ideal para fotografía.', 'featured' => false, 'order' => 7],
            ['title' => 'Parque Independencia', 'direction' => 'Av. Avellaneda s/n', 'description' => 'Amplio espacio verde con castillo morisco, lago y actividades recreativas.', 'featured' => false, 'order' => 8],
            ['title' => 'Época de Quesos', 'direction' => 'Ruta 226 Km 160', 'description' => 'Ruta del queso artesanal y embutidos, degustaciones y compras directas al productor.', 'featured' => false, 'order' => 9],
        ];

        foreach ($lugares as $lugar) {
            Lugar::updateOrCreate(['title' => $lugar['title']], $lugar);
        }
    }
}
