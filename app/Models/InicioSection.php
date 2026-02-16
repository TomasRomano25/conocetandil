<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InicioSection extends Model
{
    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'content',
        'order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
