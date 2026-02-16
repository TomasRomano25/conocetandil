<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('order');
            $table->decimal('rating', 2, 1)->nullable()->after('category');
            $table->string('phone', 20)->nullable()->after('rating');
            $table->string('website', 255)->nullable()->after('phone');
            $table->string('opening_hours', 255)->nullable()->after('website');
            $table->string('promotion_title', 150)->nullable()->after('opening_hours');
            $table->text('promotion_description')->nullable()->after('promotion_title');
            $table->string('promotion_url', 255)->nullable()->after('promotion_description');
            $table->decimal('latitude', 10, 7)->nullable()->after('promotion_url');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'rating',
                'phone',
                'website',
                'opening_hours',
                'promotion_title',
                'promotion_description',
                'promotion_url',
                'latitude',
                'longitude',
            ]);
        });
    }
};
