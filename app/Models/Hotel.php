<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hotel extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'name', 'slug', 'short_description', 'description',
        'address', 'phone', 'email', 'website', 'stars', 'checkin_time',
        'checkout_time', 'services', 'cover_image', 'status', 'payment_reference',
        'featured', 'approved_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'services'    => 'array',
            'featured'    => 'boolean',
            'stars'       => 'integer',
            'approved_at' => 'datetime',
            'expires_at'  => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(HotelPlan::class, 'plan_id');
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class)->orderBy('order');
    }

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class)->orderBy('order');
    }

    public function order()
    {
        return $this->hasOne(HotelOrder::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('featured')->orderBy('name');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isActive(): bool    { return $this->status === 'active'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
    public function isSuspended(): bool { return $this->status === 'suspended'; }

    public function isPremiumTier(): bool
    {
        return $this->plan && $this->plan->tier >= 3;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pendiente',
            'active'    => 'Activo',
            'rejected'  => 'Rechazado',
            'suspended' => 'Suspendido',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'amber',
            'active'    => 'green',
            'rejected'  => 'red',
            'suspended' => 'gray',
            default     => 'gray',
        };
    }

    protected static function booted(): void
    {
        static::creating(function (Hotel $hotel) {
            if (empty($hotel->slug)) {
                $hotel->slug = Str::slug($hotel->name);
            }
        });
    }
}
