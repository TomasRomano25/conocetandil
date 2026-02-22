<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            // page identifier: 'inicio', 'lugares', 'lugar', 'guias', 'contacto', 'hoteles', 'hotel'
            $table->string('page', 50);
            // slug of the specific entity (e.g. lugar slug or hotel slug); null for listing/static pages
            $table->string('entity_slug', 255)->nullable();
            $table->string('session_id', 100);
            $table->date('viewed_date');
            $table->timestamps();

            // One view per session+page+entity+day
            $table->unique(['session_id', 'page', 'entity_slug', 'viewed_date'], 'page_views_unique');
            $table->index(['page', 'viewed_date']);
            $table->index('viewed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
