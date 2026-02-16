<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lugar_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lugar_id')->constrained('lugares')->cascadeOnDelete();
            $table->string('path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lugar_images');
    }
};
