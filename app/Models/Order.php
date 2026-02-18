<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'status', 'total',
        'transfer_reference', 'promotion_id', 'discount', 'admin_notes', 'completed_at',
    ];

    protected $casts = [
        'total'        => 'decimal:2',
        'discount'     => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pendiente',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'amber',
            'completed' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    /** Grant premium to the user based on plan duration, then mark order completed. */
    public function complete(): void
    {
        $user  = $this->user;
        $months = $this->plan->duration_months;

        $base = ($user->premium_expires_at && $user->premium_expires_at->isFuture())
            ? $user->premium_expires_at
            : now();

        $user->update(['premium_expires_at' => $base->addMonths($months)]);

        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
