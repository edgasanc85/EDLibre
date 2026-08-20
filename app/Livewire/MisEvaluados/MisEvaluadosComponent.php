<?php

namespace App\Livewire\MisEvaluados;

use App\Models\Concertacion;
use App\Models\Evaluado;
use App\Models\Evaluador;
use App\Models\Periodo;
use Livewire\Component;

class MisEvaluadosComponent extends Component
{
    public function approveConcertacion($concertacion_id)
    {
        $evaluador = Evaluador::where('user_id', auth()->id())->active()->first();
        if (! $evaluador) {
            session()->flash('error', 'No tienes un perfil de Evaluador activo.');

            return;
        }

        $concertacion = Concertacion::with('evaluado')->findOrFail($concertacion_id);

        if ($concertacion->evaluado->dependencia_id !== $evaluador->dependencia_id && ! auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para aprobar esta concertación.');
        }

        if ($concertacion->estado === 'aprobado') {
            session()->flash('message', 'La concertación ya se encuentra aprobada.');

            return;
        }

        $concertacion->update([
            'estado' => 'aprobado',
            'evaluador_id' => $evaluador->id,
            'fecha_aprobacion_evaluador' => now(),
        ]);

        session()->flash('message', 'Concertación aprobada exitosamente para el evaluado '.$concertacion->evaluado->user->name.'.');
    }

    public function render()
    {
        $evaluador = Evaluador::where('user_id', auth()->id())->first();

        $evaluados = collect();
        if ($evaluador) {
            $evaluados = Evaluado::where('dependencia_id', $evaluador->dependencia_id)
                ->with(['user', 'nivel', 'concertaciones' => function ($q) {
                    $q->active()->with(['evaluaciones' => function ($eq) {
                        $eq->active();
                    }]);
                }])
                ->active()
                ->get();
        }

        $periodos = Periodo::active()->get();

        return view('livewire.mis-evaluados.mis-evaluados-component', compact('evaluador', 'evaluados', 'periodos'))
            ->layout('layouts.app');
    }
}
