<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $fillable = [
        'concertacion_id',
        'causal',
        'estado',
        'puntaje_funcional_obtenido',
        'puntaje_comportamental_obtenido',
        'fecha_evaluacion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'puntaje_funcional_obtenido' => 'decimal:2',
        'puntaje_comportamental_obtenido' => 'decimal:2',
        'fecha_evaluacion' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function concertacion()
    {
        return $this->belongsTo(Concertacion::class);
    }

    public function evaluacionCompromisos()
    {
        return $this->hasMany(EvaluacionCompromiso::class);
    }

    public function evaluacionComportamentales()
    {
        return $this->hasMany(EvaluacionComportamental::class);
    }
}
