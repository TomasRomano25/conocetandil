<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugarImage extends Model
{
    protected $fillable = [
        'lugar_id',
        'path',
        'order',
        'focal_x',
        'focal_y',
    ];

    protected function casts(): array
    {
        return [
            'focal_x' => 'decimal:2',
            'focal_y' => 'decimal:2',
        ];
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'lugar_id');
    }
}
