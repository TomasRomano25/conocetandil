<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerary extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'intro_tip',
        'days_min', 'days_max', 'type', 'season',
        'requires_car', 'kid_friendly', 'active',
        'sort_order', 'cover_image',
    ];

    protected $casts = [
        'requires_car' => 'boolean',
        'kid_friendly' => 'boolean',
        'active'       => 'boolean',
        'days_min'     => 'integer',
        'days_max'     => 'integer',
        'sort_order'   => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ItineraryItem::class)
            ->orderBy('day')
            ->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Match itineraries against planner questionnaire answers.
     * Flexible scoring: requires strict days match, soft match on type/season/car/kids.
     */
    public function scopeMatchFilters($query, int $days, string $type, string $season, bool $kids, bool $car)
    {
        return $query->active()
            ->where('days_min', '<=', $days)
            ->where('days_max', '>=', $days)
            ->where(function ($q) use ($type) {
                $q->where('type', $type)->orWhere('type', 'mixed');
            })
            ->where(function ($q) use ($season) {
                $q->where('season', $season)->orWhere('season', 'all');
            })
            ->where(function ($q) use ($car) {
                // If user has no car, exclude car-required itineraries
                if (! $car) {
                    $q->where('requires_car', false);
                }
            })
            ->where(function ($q) use ($kids) {
                // If travelling with kids, only show kid-friendly
                if ($kids) {
                    $q->where('kid_friendly', true);
                }
            })
            ->ordered();
    }

    /** Group items by day, returns Collection keyed by day number */
    public function itemsByDay()
    {
        return $this->items->groupBy('day');
    }

    public static function timeBlockLabel(string $block): string
    {
        return match ($block) {
            'morning'   => 'Mañana',
            'lunch'     => 'Mediodía',
            'afternoon' => 'Tarde',
            'evening'   => 'Noche',
            default     => ucfirst($block),
        };
    }

    public static function timeBlockIcon(string $block): string
    {
        return match ($block) {
            'morning'   => '🌅',
            'lunch'     => '☀️',
            'afternoon' => '🌤️',
            'evening'   => '🌙',
            default     => '📍',
        };
    }
}
