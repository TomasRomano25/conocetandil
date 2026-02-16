<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugarImage extends Model
{
    protected $fillable = [
        'lugar_id',
        'path',
        'order',
    ];

    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'lugar_id');
    }
}
