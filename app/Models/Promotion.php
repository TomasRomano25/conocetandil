<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'discount_type', 'discount_value',
        'applies_to', 'plan_ids', 'min_amount', 'max_discount',
        'max_uses', 'uses_count', 'max_uses_per_user',
        'valid_from', 'valid_until', 'is_active',
    ];

    protected $casts = [
        'plan_ids'          => 'array',
        'discount_value'    => 'decimal:2',
        'min_amount'        => 'decimal:2',
        'max_discount'      => 'decimal:2',
        'is_active'         => 'boolean',
        'valid_from'        => 'datetime',
        'valid_until'       => 'datetime',
    ];

    public function uses()
    {
        return $this->hasMany(PromotionUse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', strtoupper($code));
    }

    /** Calculate the actual discount amount for a given order amount. */
    public function calculateDiscount(float $amount): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $amount * ((float) $this->discount_value / 100);
            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = min((float) $this->discount_value, $amount);
        }

        return round($discount, 2);
    }

    /** Check if the promotion itself is currently valid (ignoring user). */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_until && now()->gt($this->valid_until)) return false;
        if ($this->max_uses !== null && $this->uses_count >= $this->max_uses) return false;
        return true;
    }

    /** Check if a specific user hasn't exceeded their personal limit. */
    public function isValidForUser(int $userId): bool
    {
        $used = $this->uses()->where('user_id', $userId)->count();
        return $used < $this->max_uses_per_user;
    }

    /** Check if this promotion applies to the given order type and plan. */
    public function isValidForPlan(string $type, ?int $planId): bool
    {
        if ($this->applies_to !== 'all' && $this->applies_to !== $type) {
            return false;
        }
        if ($this->plan_ids !== null && $planId !== null) {
            return in_array($planId, $this->plan_ids);
        }
        return true;
    }

    /**
     * Full validation for a checkout scenario.
     * Returns array: ['valid' => bool, 'discount' => float, 'discount_formatted' => string, 'message' => string, 'promotion_id' => int|null]
     */
    public function validateForCheckout(string $type, ?int $planId, float $amount, int $userId): array
    {
        if (!$this->isValid()) {
            $msg = !$this->is_active ? 'Este código no está activo.' : 'Este código ha expirado o agotado sus usos.';
            return ['valid' => false, 'discount' => 0, 'discount_formatted' => '', 'message' => $msg, 'promotion_id' => null];
        }

        if (!$this->isValidForPlan($type, $planId)) {
            return ['valid' => false, 'discount' => 0, 'discount_formatted' => '', 'message' => 'Este código no aplica a este plan.', 'promotion_id' => null];
        }

        if (!$this->isValidForUser($userId)) {
            return ['valid' => false, 'discount' => 0, 'discount_formatted' => '', 'message' => 'Ya usaste este código anteriormente.', 'promotion_id' => null];
        }

        if ($this->min_amount !== null && $amount < (float) $this->min_amount) {
            $min = '$' . number_format((float) $this->min_amount, 0, ',', '.');
            return ['valid' => false, 'discount' => 0, 'discount_formatted' => '', 'message' => "El monto mínimo para este código es {$min}.", 'promotion_id' => null];
        }

        $discount = $this->calculateDiscount($amount);
        $formatted = '$' . number_format($discount, 0, ',', '.');

        return [
            'valid'               => true,
            'discount'            => $discount,
            'discount_formatted'  => $formatted,
            'message'             => "¡Descuento de {$formatted} aplicado!",
            'promotion_id'        => $this->id,
        ];
    }
}
