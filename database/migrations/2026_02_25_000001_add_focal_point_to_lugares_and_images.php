<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->decimal('image_focal_x', 5, 2)->default(50)->after('image');
            $table->decimal('image_focal_y', 5, 2)->default(50)->after('image_focal_x');
        });

        Schema::table('lugar_images', function (Blueprint $table) {
            $table->decimal('focal_x', 5, 2)->default(50)->after('order');
            $table->decimal('focal_y', 5, 2)->default(50)->after('focal_x');
        });
    }

    public function down(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->dropColumn(['image_focal_x', 'image_focal_y']);
        });

        Schema::table('lugar_images', function (Blueprint $table) {
            $table->dropColumn(['focal_x', 'focal_y']);
        });
    }
};
