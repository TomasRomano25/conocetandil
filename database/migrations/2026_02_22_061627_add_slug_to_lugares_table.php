<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        // Populate slugs for existing records
        $lugares = DB::table('lugares')->orderBy('id')->get(['id', 'title']);
        $used = [];

        foreach ($lugares as $lugar) {
            $base = Str::slug($lugar->title);
            $slug = $base;
            $i    = 2;

            while (in_array($slug, $used)) {
                $slug = $base . '-' . $i++;
            }

            $used[] = $slug;
            DB::table('lugares')->where('id', $lugar->id)->update(['slug' => $slug]);
        }

        Schema::table('lugares', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('lugares', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
