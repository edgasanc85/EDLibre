<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    protected $fillable = [
        'nivel_id',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function conductas()
    {
        return $this->hasMany(Conducta::class);
    }
}
