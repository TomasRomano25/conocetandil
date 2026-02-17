<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('configurations')->insert([
            ['key' => 'backup_enabled',        'value' => '1',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_interval_hours', 'value' => '1',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_keep_count',     'value' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_last_run',       'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backup_latest_file',    'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
