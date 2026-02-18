<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price',
        'duration_months', 'features', 'active', 'sort_order',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'duration_months' => 'integer',
        'features'        => 'array',
        'active'          => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'plan_id');
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

    public function formattedPrice(): string
    {
        return '$' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
