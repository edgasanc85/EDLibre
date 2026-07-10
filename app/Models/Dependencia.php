<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $fillable = [
        'nombre',
        'parent_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Scope para filtrar únicamente registros activos.
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Relación con la dependencia padre.
     */
    public function parent()
    {
        return $this->belongsTo(Dependencia::class, 'parent_id');
    }

    /**
     * Relación con las dependencias hijas.
     */
    public function subdependencias()
    {
        return $this->hasMany(Dependencia::class, 'parent_id');
    }

    public function evaluadores()
    {
        return $this->hasMany(Evaluador::class);
    }

    public function evaluados()
    {
        return $this->hasMany(Evaluado::class);
    }
}
