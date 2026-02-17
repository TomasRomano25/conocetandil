<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('label', 100);
            $table->string('route_name', 100);
            $table->integer('order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        DB::table('nav_items')->insert([
            ['key' => 'inicio',   'label' => 'Inicio',   'route_name' => 'inicio',   'order' => 1, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'lugares',  'label' => 'Lugares',  'route_name' => 'lugares',  'order' => 2, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'guias',    'label' => 'Guías',    'route_name' => 'guias',    'order' => 3, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contacto', 'label' => 'Contacto', 'route_name' => 'contacto', 'order' => 4, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
