<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluado extends Model
{
    protected $fillable = [
        'user_id',
        'dependencia_id',
        'nivel_id',
        'cargo',
        'fecha_ingreso',
        'fecha_retiro',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_ingreso' => 'date',
        'fecha_retiro' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
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
