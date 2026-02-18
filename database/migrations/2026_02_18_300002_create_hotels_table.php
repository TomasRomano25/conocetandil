<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('hotel_plans');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->unsignedTinyInteger('stars')->nullable(); // 1-5
            $table->string('checkin_time')->nullable();
            $table->string('checkout_time')->nullable();
            $table->json('services')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['pending', 'active', 'rejected', 'suspended'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
