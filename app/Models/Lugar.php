<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    protected $table = 'lugares';

    protected $fillable = [
        'title',
        'direction',
        'description',
        'image',
        'featured',
        'is_premium',
        'order',
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
    ];

    protected $appends = ['cover_image'];

    protected function casts(): array
    {
        return [
            'featured'   => 'boolean',
            'is_premium' => 'boolean',
            'order'      => 'integer',
            'rating' => 'decimal:1',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function images()
    {
        return $this->hasMany(LugarImage::class, 'lugar_id')->orderBy('order');
    }

    public function getCoverImageAttribute()
    {
        $firstGallery = $this->images->first();

        if ($firstGallery) {
            return $firstGallery->path;
        }

        return $this->image;
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function hasPromotion(): bool
    {
        return $this->promotion_title !== null;
    }

    public function getGoogleMapsUrlAttribute(): string
    {
        if ($this->hasCoordinates()) {
            return 'https://www.google.com/maps/search/?api=1&query=' . $this->latitude . ',' . $this->longitude;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->direction . ', Tandil, Argentina');
    }

    public function getGoogleMapsDirectionsUrlAttribute(): string
    {
        if ($this->hasCoordinates()) {
            return 'https://www.google.com/maps/dir/?api=1&destination=' . $this->latitude . ',' . $this->longitude;
        }

        return 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($this->direction . ', Tandil, Argentina');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeSearch($query, string $term)
    {
        $like = '%' . $term . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('title', 'like', $like)
              ->orWhere('direction', 'like', $like)
              ->orWhere('description', 'like', $like);
        });
    }
}
