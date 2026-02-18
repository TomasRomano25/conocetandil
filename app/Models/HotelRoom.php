<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    protected $fillable = ['hotel_id', 'name', 'description', 'capacity', 'price_per_night', 'image', 'order'];

    protected function casts(): array
    {
        return [
            'capacity'        => 'integer',
            'price_per_night' => 'decimal:2',
        ];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function formattedPrice(): string
    {
        if (! $this->price_per_night) return '';
        return '$' . number_format($this->price_per_night, 0, ',', '.') . '/noche';
    }
}
