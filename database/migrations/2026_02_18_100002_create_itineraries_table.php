<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('intro_tip')->nullable();       // shown at top of itinerary
            $table->integer('days_min')->default(1);
            $table->integer('days_max')->default(1);
            // Filters used by the planner questionnaire
            $table->string('type')->default('mixed');    // nature|gastronomy|adventure|relax|mixed
            $table->string('season')->default('all');    // summer|winter|all
            $table->boolean('requires_car')->default(false);
            $table->boolean('kid_friendly')->default(true);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
