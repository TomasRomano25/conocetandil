<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'status', 'total',
        'transfer_reference', 'mp_preference_id', 'promotion_id', 'discount', 'admin_notes', 'completed_at',
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
        $plan  = $this->plan;
        $n     = $plan->duration_months;

        $base = ($user->premium_expires_at && $user->premium_expires_at->isFuture())
            ? $user->premium_expires_at
            : now();

        $expires = ($plan->duration_unit === 'weeks')
            ? $base->addWeeks($n)
            : $base->addMonths($n);

        $user->update(['premium_expires_at' => $expires]);

        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        $this->load('plan', 'user');

        if (\App\Models\Configuration::get('smtp_host')) {
            $adminEmail = \App\Models\Configuration::get('smtp_from_email');
            if ($adminEmail) {
                try { \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\Admin\OrderCompletedMail($this, 'membership')); } catch (\Throwable) {}
            }
            try { \Illuminate\Support\Facades\Mail::to($this->user->email)->send(new \App\Mail\Customer\OrderCompletedMail($this, 'membership')); } catch (\Throwable) {}
        }
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);

        $this->load('plan', 'user');

        if (\App\Models\Configuration::get('smtp_host')) {
            $adminEmail = \App\Models\Configuration::get('smtp_from_email');
            if ($adminEmail) {
                try { \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\Admin\OrderCancelledMail($this, 'membership')); } catch (\Throwable) {}
            }
            try { \Illuminate\Support\Facades\Mail::to($this->user->email)->send(new \App\Mail\Customer\OrderCancelledMail($this, 'membership')); } catch (\Throwable) {}
        }
    }
}
