<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class);
    }
}
