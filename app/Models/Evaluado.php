<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluado extends Model
{
    protected $fillable = [
        'user_id',
        'dependencia_id',
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
}
