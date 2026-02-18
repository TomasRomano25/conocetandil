<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('session_id', 40);
            $table->date('viewed_date');
            $table->timestamps();

            // One record per (hotel, session, day) — unique daily sessions
            $table->unique(['hotel_id', 'session_id', 'viewed_date']);
            $table->index(['hotel_id', 'viewed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_views');
    }
};
