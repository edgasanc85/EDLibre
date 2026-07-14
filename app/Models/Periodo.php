<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $fillable = [
        'vigencia',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function compromisosFuncionals()
    {
        return $this->hasMany(CompromisoFuncional::class);
    }

    public function compromisosComportamentals()
    {
        return $this->hasMany(CompromisoComportamental::class);
    }

    public function concertaciones()
    {
        return $this->hasMany(Concertacion::class);
    }
}
