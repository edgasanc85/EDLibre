<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conducta extends Model
{
    protected $fillable = [
        'competencia_id',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }
}
