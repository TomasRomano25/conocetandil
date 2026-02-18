<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('promotion_id')->nullable()->after('transfer_reference');
            $table->decimal('discount', 10, 2)->default(0)->after('promotion_id');
        });
    }

    public function down(): void
    {
        Schema::table('hotel_orders', function (Blueprint $table) {
            $table->dropColumn(['promotion_id', 'discount']);
        });
    }
};
