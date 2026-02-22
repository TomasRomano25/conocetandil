<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelOrder extends Model
{
    protected $fillable = [
        'hotel_id', 'user_id', 'plan_id', 'amount',
        'status', 'transfer_reference', 'mp_preference_id', 'promotion_id', 'discount', 'admin_notes', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'discount'     => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(HotelPlan::class, 'plan_id');
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

    /** Mark order completed and activate the hotel. */
    public function complete(): void
    {
        $hotel = $this->hotel;
        $months = $this->plan->duration_months;

        $hotel->update([
            'status'      => 'active',
            'approved_at' => now(),
            'expires_at'  => now()->addMonths($months),
        ]);

        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        $this->load('hotel', 'plan', 'user');

        if (\App\Models\Configuration::get('smtp_host')) {
            $adminEmail = \App\Models\Configuration::get('smtp_from_email');
            if ($adminEmail) {
                try { \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\Admin\OrderCompletedMail($this, 'hotel')); } catch (\Throwable) {}
            }
            try { \Illuminate\Support\Facades\Mail::to($this->user->email)->send(new \App\Mail\Customer\OrderCompletedMail($this, 'hotel')); } catch (\Throwable) {}
        }
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);

        $this->load('hotel', 'plan', 'user');

        if (\App\Models\Configuration::get('smtp_host')) {
            $adminEmail = \App\Models\Configuration::get('smtp_from_email');
            if ($adminEmail) {
                try { \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\Admin\OrderCancelledMail($this, 'hotel')); } catch (\Throwable) {}
            }
            try { \Illuminate\Support\Facades\Mail::to($this->user->email)->send(new \App\Mail\Customer\OrderCancelledMail($this, 'hotel')); } catch (\Throwable) {}
        }
    }
}
