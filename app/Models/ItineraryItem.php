<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryItem extends Model
{
    protected $fillable = [
        'itinerary_id', 'lugar_id', 'day', 'time_block', 'sort_order',
        'custom_title', 'duration_minutes', 'estimated_cost',
        'why_order', 'contextual_notes', 'skip_if', 'why_worth_it',
        'travel_minutes_to_next',
    ];

    protected $casts = [
        'day'                    => 'integer',
        'sort_order'             => 'integer',
        'duration_minutes'       => 'integer',
        'travel_minutes_to_next' => 'integer',
    ];

    public function itinerary(): BelongsTo
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function lugar(): BelongsTo
    {
        return $this->belongsTo(Lugar::class);
    }

    public function displayTitle(): string
    {
        return $this->custom_title ?? $this->lugar?->title ?? '—';
    }

    public function travelLabel(): ?string
    {
        if (! $this->travel_minutes_to_next) return null;
        $m = $this->travel_minutes_to_next;
        $h = intdiv($m, 60);
        $r = $m % 60;
        if ($h > 0 && $r > 0) return "{$h}h {$r}min";
        return $h > 0 ? "{$h}h" : "{$m}min";
    }

    public function travelIcon(): string
    {
        return ($this->travel_minutes_to_next ?? 99) <= 8 ? '🚶' : '🚗';
    }

    public function formattedDuration(): ?string
    {
        if (! $this->duration_minutes) {
            return null;
        }

        $hours   = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}min";
        }

        return $hours > 0 ? "{$hours}h" : "{$minutes}min";
    }
}
