<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = ['page', 'entity_slug', 'session_id', 'viewed_date'];

    protected $casts = ['viewed_date' => 'date'];

    /**
     * Record a unique daily view (silent fail on duplicate).
     */
    public static function record(string $page, ?string $entitySlug = null): void
    {
        try {
            static::firstOrCreate([
                'page'        => $page,
                'entity_slug' => $entitySlug,
                'session_id'  => session()->getId(),
                'viewed_date' => today()->toDateString(),
            ]);
        } catch (\Throwable) {}
    }
}
