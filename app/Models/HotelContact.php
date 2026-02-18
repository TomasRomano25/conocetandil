<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelContact extends Model
{
    protected $fillable = [
        'hotel_id', 'sender_name', 'sender_email',
        'sender_phone', 'message', 'email_sent',
    ];

    protected function casts(): array
    {
        return ['email_sent' => 'boolean'];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
