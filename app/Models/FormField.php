<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = [
        'form_id', 'name', 'label', 'type',
        'placeholder', 'required', 'visible',
        'sort_order', 'options',
    ];

    protected $casts = [
        'required'   => 'boolean',
        'visible'    => 'boolean',
        'sort_order' => 'integer',
        'options'    => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
