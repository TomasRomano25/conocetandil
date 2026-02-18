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
    ];

    protected $casts = [
        'day'              => 'integer',
        'sort_order'       => 'integer',
        'duration_minutes' => 'integer',
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
