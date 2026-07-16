<?php

namespace App\Livewire\Evaluaciones;

use App\Models\Concertacion;
use App\Models\Evaluacion;
use App\Models\EvaluacionComportamental;
use App\Models\EvaluacionCompromiso;
use App\Models\Evaluador;
use Livewire\Component;

class EvaluacionComponent extends Component
{
    public $concertacion_id;

    public $concertacion;

    public $rolActual;

    // Modal states
    public $showCreateModal = false;

    public $showGradeModal = false;

    // Create Evaluacion Form
    public $causal = '';

    // Grade Evaluacion Form
    public $evaluacion_seleccionada_id;

    public $calificaciones = []; // array of compromiso_funcional_id => calificacion (0-100)

    public $calificaciones_comportamentales = []; // array of compromiso_comportamental_id_conducta_id => calificacion (0-100)

    protected $rules = [
        'causal' => 'required|string',
    ];

    public function mount($concertacion_id)
    {
        $this->concertacion_id = $concertacion_id;
        $this->loadData();
    }

    public function loadData()
    {
        $this->concertacion = Concertacion::with([
            'evaluado.user',
            'periodo',
            'evaluaciones',
            'compromisosFuncionals.evidencias',
            'compromisosComportamentals.conductas',
            'compromisosComportamentals.competencia',
        ])->findOrFail($this->concertacion_id);

        if ($this->concertacion->estado !== 'aprobado') {
            abort(403, 'La concertación no está aprobada.');
        }

        $user_id = auth()->id();
        $isEvaluado = $this->concertacion->evaluado->user_id === $user_id;
        $isEvaluador = Evaluador::where('user_id', $user_id)
            ->where('dependencia_id', $this->concertacion->evaluado->dependencia_id)
            ->active()
            ->exists();

        if ($isEvaluado) {
            $this->rolActual = 'evaluado';
        } elseif ($isEvaluador) {
            $this->rolActual = 'evaluador';
        } else {
            abort(403, 'No tienes permisos para ver estas evaluaciones.');
        }
    }

    public function createEvaluacion()
    {
        if ($this->rolActual !== 'evaluador') {
            return;
        }

        $this->validate(['causal' => 'required']);

        $eval = Evaluacion::create([
            'concertacion_id' => $this->concertacion_id,
            'causal' => $this->causal,
            'estado' => 'en_revision', // Borrador del evaluador
            'activo' => true,
        ]);

        // Crear registros de compromisos funcionales en 0
        foreach ($this->concertacion->compromisosFuncionals as $cf) {
            EvaluacionCompromiso::create([
                'evaluacion_id' => $eval->id,
                'compromiso_funcional_id' => $cf->id,
                'calificacion' => null,
                'activo' => true,
            ]);
        }

        // Crear registros de comportamentales en 0 por cada conducta
        foreach ($this->concertacion->compromisosComportamentals as $cc) {
            foreach ($cc->conductas as $conducta) {
                EvaluacionComportamental::create([
                    'evaluacion_id' => $eval->id,
                    'compromiso_comportamental_id' => $cc->id,
                    'conducta_id' => $conducta->id,
                    'calificacion' => null,
                    'activo' => true,
                ]);
            }
        }

        $this->showCreateModal = false;
        $this->causal = '';
        $this->loadData();

        // Abrir modal de calificacion
        $this->openGradeModal($eval->id);
    }

    public function openGradeModal($evaluacion_id)
    {
        $evaluacion = Evaluacion::with('evaluacionCompromisos', 'evaluacionComportamentales')->findOrFail($evaluacion_id);
        $this->evaluacion_seleccionada_id = $evaluacion->id;

        $this->calificaciones = [];
        foreach ($evaluacion->evaluacionCompromisos as $ec) {
            $this->calificaciones[$ec->compromiso_funcional_id] = $ec->calificacion;
        }

        $this->calificaciones_comportamentales = [];
        foreach ($evaluacion->evaluacionComportamentales as $ecomp) {
            $key = $ecomp->compromiso_comportamental_id.'_'.$ecomp->conducta_id;
            $this->calificaciones_comportamentales[$key] = $ecomp->calificacion;
        }

        $this->showGradeModal = true;
    }

    public function saveCalificaciones()
    {
        if ($this->rolActual !== 'evaluador') {
            return;
        }

        $evaluacion = Evaluacion::findOrFail($this->evaluacion_seleccionada_id);

        // Validar que todas las notas estén entre 0 y 100
        foreach ($this->calificaciones as $cid => $nota) {
            if ($nota === '' || $nota === null || $nota < 0 || $nota > 100) {
                session()->flash('error', 'Todas las calificaciones funcionales deben estar entre 0 y 100.');

                return;
            }
        }

        foreach ($this->calificaciones_comportamentales as $key => $nota) {
            if ($nota === '' || $nota === null || $nota < 0 || $nota > 100) {
                session()->flash('error', 'Todas las calificaciones comportamentales deben estar entre 0 y 100.');

                return;
            }
        }

        // GUARDADO FUNCIONAL
        $puntajeTotalFuncional = 0;
        foreach ($this->calificaciones as $cid => $nota) {
            $ec = EvaluacionCompromiso::where('evaluacion_id', $evaluacion->id)
                ->where('compromiso_funcional_id', $cid)->first();
            if ($ec) {
                $ec->update(['calificacion' => $nota]);
                // Calculo ponderado funcional: nota * peso / 100
                $peso = $ec->compromisoFuncional->peso;
                $puntajeTotalFuncional += ($nota * $peso / 100);
            }
        }

        // GUARDADO COMPORTAMENTAL
        $sumaNotasComportamentales = 0;
        $cantidadConductas = count($this->calificaciones_comportamentales);

        foreach ($this->calificaciones_comportamentales as $key => $nota) {
            [$cc_id, $conducta_id] = explode('_', $key);
            $ecomp = EvaluacionComportamental::where('evaluacion_id', $evaluacion->id)
                ->where('compromiso_comportamental_id', $cc_id)
                ->where('conducta_id', $conducta_id)
                ->first();

            if ($ecomp) {
                $ecomp->update(['calificacion' => $nota]);
                $sumaNotasComportamentales += $nota;
            }
        }

        $promedioComportamental = $cantidadConductas > 0 ? ($sumaNotasComportamentales / $cantidadConductas) : 0;
        $puntajeTotalComportamental = ($promedioComportamental * 15) / 100;

        $evaluacion->update([
            'puntaje_funcional_obtenido' => $puntajeTotalFuncional,
            'puntaje_comportamental_obtenido' => $puntajeTotalComportamental,
        ]);

        session()->flash('message', 'Calificaciones guardadas exitosamente.');
    }

    public function notificarEvaluacion()
    {
        if ($this->rolActual !== 'evaluador') {
            return;
        }
        $evaluacion = Evaluacion::findOrFail($this->evaluacion_seleccionada_id);
        $evaluacion->update([
            'estado' => 'calificada',
            'fecha_evaluacion' => now(),
        ]);

        $this->showGradeModal = false;
        $this->loadData();
        session()->flash('message', 'Evaluación enviada al evaluado exitosamente.');
    }

    public function acceptEvaluacion($evaluacion_id)
    {
        if ($this->rolActual !== 'evaluado') {
            return;
        }
        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        $evaluacion->update(['estado' => 'aceptada']);
        $this->loadData();
        session()->flash('message', 'Evaluación aceptada.');
    }

    public function render()
    {
        return view('livewire.evaluaciones.evaluacion-component')
            ->layout('layouts.app');
    }
}
