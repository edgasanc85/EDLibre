<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionComportamental extends Model
{
    protected $fillable = [
        'evaluacion_id',
        'compromiso_comportamental_id',
        'conducta_id',
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

    public function compromisoComportamental()
    {
        return $this->belongsTo(CompromisoComportamental::class);
    }

    public function conducta()
    {
        return $this->belongsTo(Conducta::class);
    }
}
