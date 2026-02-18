<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedTinyInteger('tier')->default(1); // 1=Básico, 2=Estándar, 3=Diamante
            $table->unsignedSmallInteger('max_images')->default(1);
            $table->boolean('has_services')->default(false);
            $table->boolean('has_rooms')->default(false);
            $table->boolean('has_gallery_captions')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('duration_months')->default(12);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_plans');
    }
};
