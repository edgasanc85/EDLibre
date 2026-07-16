<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaFuncional extends Model
{
    protected $fillable = [
        'compromiso_funcional_id',
        'descripcion',
        'ubicacion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function compromisoFuncional()
    {
        return $this->belongsTo(CompromisoFuncional::class);
    }
}
