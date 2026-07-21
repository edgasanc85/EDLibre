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
        'periodo_evaluado_inicio',
        'periodo_evaluado_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'puntaje_funcional_obtenido' => 'decimal:2',
        'puntaje_comportamental_obtenido' => 'decimal:2',
        'fecha_evaluacion' => 'datetime',
        'periodo_evaluado_inicio' => 'date',
        'periodo_evaluado_fin' => 'date',
    ];

    public function diasEvaluados()
    {
        if ($this->periodo_evaluado_inicio && $this->periodo_evaluado_fin) {
            return $this->periodo_evaluado_inicio->diffInDays($this->periodo_evaluado_fin) + 1; // +1 to include both start and end days
        }

        return 0;
    }

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
