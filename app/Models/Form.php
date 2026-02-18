<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'active',
        'send_notification', 'notification_email',
    ];

    protected $casts = [
        'active'            => 'boolean',
        'send_notification' => 'boolean',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function visibleFields(): HasMany
    {
        return $this->hasMany(FormField::class)->where('visible', true)->orderBy('sort_order');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
