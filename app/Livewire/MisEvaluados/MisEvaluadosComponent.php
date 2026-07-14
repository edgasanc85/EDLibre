<?php

namespace App\Livewire\MisEvaluados;

use Livewire\Component;
use App\Models\Evaluador;
use App\Models\Evaluado;
use App\Models\Periodo;

class MisEvaluadosComponent extends Component
{
    public function render()
    {
        $evaluador = Evaluador::where('user_id', auth()->id())->first();
        
        $evaluados = collect();
        if ($evaluador) {
            $evaluados = Evaluado::where('dependencia_id', $evaluador->dependencia_id)
                ->with(['user', 'nivel', 'concertaciones' => function($q) {
                    $q->active();
                }])
                ->active()
                ->get();
        }

        $periodos = Periodo::active()->get();

        return view('livewire.mis-evaluados.mis-evaluados-component', compact('evaluador', 'evaluados', 'periodos'))
            ->layout('layouts.app');
    }
}
