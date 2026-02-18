<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('sender_name', 100);
            $table->string('sender_email', 150);
            $table->string('sender_phone', 30)->nullable();
            $table->text('message');
            $table->boolean('email_sent')->default(false);
            $table->timestamps();

            $table->index(['hotel_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_contacts');
    }
};
