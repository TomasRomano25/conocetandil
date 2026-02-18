<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUse extends Model
{
    protected $fillable = [
        'promotion_id', 'user_id', 'orderable_type', 'orderable_id', 'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
