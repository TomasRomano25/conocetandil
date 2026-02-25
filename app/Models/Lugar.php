<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lugar extends Model
{
    protected $table = 'lugares';

    protected $fillable = [
        'title',
        'slug',
        'direction',
        'description',
        'image',
        'image_focal_x',
        'image_focal_y',
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

    protected $appends = ['cover_image', 'cover_focal_point'];

    protected function casts(): array
    {
        return [
            'featured'   => 'boolean',
            'is_premium' => 'boolean',
            'order'      => 'integer',
            'rating' => 'decimal:1',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'image_focal_x' => 'decimal:2',
            'image_focal_y' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
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

    public function getCoverFocalPointAttribute(): array
    {
        $firstGallery = $this->images->first();

        if ($firstGallery) {
            return ['x' => $firstGallery->focal_x ?? 50, 'y' => $firstGallery->focal_y ?? 50];
        }

        return ['x' => $this->image_focal_x ?? 50, 'y' => $this->image_focal_y ?? 50];
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
