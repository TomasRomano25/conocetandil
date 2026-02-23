<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugarBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'cta_text',
        'cta_url',
        'image_desktop',
        'image_mobile',
        'bg_color',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
