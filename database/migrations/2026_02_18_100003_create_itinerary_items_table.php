<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lugar_id')->nullable()->constrained('lugares')->nullOnDelete();
            $table->integer('day')->default(1);
            $table->string('time_block')->default('morning'); // morning|lunch|afternoon|evening
            $table->integer('sort_order')->default(0);
            $table->string('custom_title')->nullable();       // override lugar title if needed
            $table->integer('duration_minutes')->nullable();  // estimated time
            $table->string('estimated_cost')->nullable();     // e.g. "Gratis" or "$500-$1000"
            $table->text('why_order')->nullable();            // "Ideal ir temprano porque..."
            $table->text('contextual_notes')->nullable();     // weather tips, warnings
            $table->text('skip_if')->nullable();              // "Saltá esto si no tenés auto"
            $table->text('why_worth_it')->nullable();         // "Vale la pena porque..."
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_items');
    }
};
