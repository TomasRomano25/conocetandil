<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelView extends Model
{
    protected $fillable = ['hotel_id', 'session_id', 'viewed_date'];

    protected function casts(): array
    {
        return ['viewed_date' => 'date'];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
