<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompromisoFuncional extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluado_id',
        'periodo_id',
        'concertacion_id',
        'verbo',
        'objeto',
        'condicion',
        'peso',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'peso' => 'integer',
    ];

    /**
     * Scope para obtener solo los registros activos.
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación: Un compromiso funcional pertenece a un evaluado.
     */
    public function evaluado()
    {
        return $this->belongsTo(Evaluado::class);
    }

    /**
     * Relación: Un compromiso funcional pertenece a un periodo.
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function concertacion()
    {
        return $this->belongsTo(Concertacion::class);
    }
}
