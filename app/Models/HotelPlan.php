<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'tier',
        'max_images', 'has_services', 'has_rooms', 'has_gallery_captions',
        'is_featured', 'duration_months', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price'               => 'decimal:2',
            'tier'                => 'integer',
            'max_images'          => 'integer',
            'has_services'        => 'boolean',
            'has_rooms'           => 'boolean',
            'has_gallery_captions'=> 'boolean',
            'is_featured'         => 'boolean',
            'duration_months'     => 'integer',
            'is_active'           => 'boolean',
            'sort_order'          => 'integer',
        ];
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function formattedPrice(): string
    {
        return '$' . number_format($this->price, 0, ',', '.');
    }

    public function tierLabel(): string
    {
        return match ($this->tier) {
            1 => 'Básico',
            2 => 'Estándar',
            3 => 'Diamante',
            default => 'Tier ' . $this->tier,
        };
    }

    public function durationLabel(): string
    {
        return match ($this->duration_months) {
            1       => '1 mes',
            3       => '3 meses',
            6       => '6 meses',
            12      => '1 año',
            default => $this->duration_months . ' meses',
        };
    }
}
