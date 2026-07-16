<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionCompromiso extends Model
{
    protected $fillable = [
        'evaluacion_id',
        'compromiso_funcional_id',
        'calificacion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'calificacion' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function compromisoFuncional()
    {
        return $this->belongsTo(CompromisoFuncional::class);
    }
}
