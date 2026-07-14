<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompromisoComportamental extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluado_id',
        'periodo_id',
        'concertacion_id',
        'competencia_id',
        'activo',
    ];

    protected $casts = [
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

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }

    public function conductas()
    {
        return $this->belongsToMany(Conducta::class, 'cc_conductas', 'compromiso_comportamental_id', 'conducta_id');
    }

    public function concertacion()
    {
        return $this->belongsTo(Concertacion::class);
    }
}
