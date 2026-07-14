<?php

namespace App\Livewire\MisCompromisos;

use Livewire\Component;
use App\Models\Evaluado;
use App\Models\Periodo;

class MisCompromisosComponent extends Component
{
    public function render()
    {
        // En un entorno real, obtenemos el evaluado asociado al usuario autenticado
        $evaluado = Evaluado::where('user_id', auth()->id())
            ->with(['concertaciones' => function($q) {
                $q->active();
            }])
            ->first();
        
        $periodos = Periodo::active()->get();

        return view('livewire.mis-compromisos.mis-compromisos-component', compact('evaluado', 'periodos'))
            ->layout('layouts.app');
    }
}
