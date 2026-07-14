<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concertacion extends Model
{
    protected $table = 'concertaciones';

    protected $fillable = [
        'evaluado_id',
        'evaluador_id',
        'periodo_id',
        'estado',
        'fecha_aprobacion_evaluado',
        'fecha_aprobacion_evaluador',
        'activo',
    ];

    protected $casts = [
        'fecha_aprobacion_evaluado' => 'datetime',
        'fecha_aprobacion_evaluador' => 'datetime',
        'activo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function evaluado()
    {
        return $this->belongsTo(Evaluado::class);
    }

    public function evaluador()
    {
        return $this->belongsTo(Evaluador::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function compromisosFuncionals()
    {
        return $this->hasMany(CompromisoFuncional::class);
    }

    public function compromisosComportamentals()
    {
        return $this->hasMany(CompromisoComportamental::class);
    }
}
