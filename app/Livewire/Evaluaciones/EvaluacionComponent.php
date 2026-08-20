<?php

namespace App\Livewire\Evaluaciones;

use App\Models\Concertacion;
use App\Models\Evaluacion;
use App\Models\EvaluacionComportamental;
use App\Models\EvaluacionCompromiso;
use App\Models\Evaluador;
use App\Services\EvaluacionConsolidacionService;
use Carbon\Carbon;
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

    public $periodo_evaluado_inicio = '';

    public $periodo_evaluado_fin = '';

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

    public function createEvaluacion(EvaluacionConsolidacionService $consolidacionService)
    {
        if ($this->rolActual !== 'evaluador') {
            return;
        }

        $isConsolidacion = in_array($this->causal, ['Consolidación semestral', 'Consolidación definitiva']);

        $rules = [
            'causal' => 'required|string',
        ];

        if (! $isConsolidacion) {
            $rules['periodo_evaluado_inicio'] = 'required|date';
            $rules['periodo_evaluado_fin'] = 'required|date|after_or_equal:periodo_evaluado_inicio';
        }

        $this->validate($rules);

        if (! $isConsolidacion) {
            $periodo = $this->concertacion->periodo;
            $inicioReq = Carbon::parse($this->periodo_evaluado_inicio)->startOfDay();
            $finReq = Carbon::parse($this->periodo_evaluado_fin)->startOfDay();
            $pInicio = Carbon::parse($periodo->fecha_inicio)->startOfDay();
            $pFin = Carbon::parse($periodo->fecha_fin)->startOfDay();

            if ($inicioReq->lt($pInicio) || $finReq->gt($pFin)) {
                $this->addError('periodo_evaluado_inicio', 'Las fechas deben estar dentro del periodo evaluado ('.$pInicio->format('d/m/Y').' - '.$pFin->format('d/m/Y').').');

                return;
            }
        }

        if ($isConsolidacion) {
            $eval = $this->generarConsolidacion($this->causal, $consolidacionService);
            $this->showCreateModal = false;
            $this->causal = '';
            $this->loadData();

            if ($eval) {
                session()->flash('message', 'Consolidación generada exitosamente. El evaluado debe revisarla y aceptarla.');
            }

            return;
        }

        $eval = Evaluacion::create([
            'concertacion_id' => $this->concertacion_id,
            'causal' => $this->causal,
            'estado' => 'en_revision', // Borrador del evaluador
            'periodo_evaluado_inicio' => $this->periodo_evaluado_inicio,
            'periodo_evaluado_fin' => $this->periodo_evaluado_fin,
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
        $this->periodo_evaluado_inicio = '';
        $this->periodo_evaluado_fin = '';
        $this->loadData();

        // Abrir modal de calificacion
        $this->openGradeModal($eval->id);
    }

    public function generarConsolidacion(string $causal, ?EvaluacionConsolidacionService $service = null): ?Evaluacion
    {
        $service = $service ?? app(EvaluacionConsolidacionService::class);

        if ($causal === 'Consolidación semestral') {
            $eval = $service->generarConsolidacionSemestral($this->concertacion);
        } else {
            $eval = $service->generarConsolidacionDefinitiva($this->concertacion);
        }

        if (! $eval) {
            session()->flash('error', 'No hay evaluaciones previas con días definidos para consolidar.');

            return null;
        }

        return $eval;
    }

    public function openGradeModal($evaluacion_id)
    {
        $evaluacion = Evaluacion::with('evaluacionCompromisos', 'evaluacionComportamentales')->findOrFail($evaluacion_id);
        $this->evaluacion_seleccionada_id = $evaluacion->id;

        // Asegurar que existan los registros de compromisos funcionales
        $this->calificaciones = [];
        foreach ($this->concertacion->compromisosFuncionals as $cf) {
            $ec = EvaluacionCompromiso::firstOrCreate(
                [
                    'evaluacion_id' => $evaluacion->id,
                    'compromiso_funcional_id' => $cf->id,
                ],
                [
                    'calificacion' => null,
                    'activo' => true,
                ]
            );
            $this->calificaciones[$cf->id] = $ec->calificacion;
        }

        // Asegurar que existan los registros de compromisos comportamentales
        $this->calificaciones_comportamentales = [];
        foreach ($this->concertacion->compromisosComportamentals as $cc) {
            foreach ($cc->conductas as $conducta) {
                $ecomp = EvaluacionComportamental::firstOrCreate(
                    [
                        'evaluacion_id' => $evaluacion->id,
                        'compromiso_comportamental_id' => $cc->id,
                        'conducta_id' => $conducta->id,
                    ],
                    [
                        'calificacion' => null,
                        'activo' => true,
                    ]
                );
                $key = $cc->id.'_'.$conducta->id;
                $this->calificaciones_comportamentales[$key] = $ecomp->calificacion;
            }
        }

        $this->showGradeModal = true;
    }

    public function saveCalificaciones(): bool
    {
        if ($this->rolActual !== 'evaluador') {
            return false;
        }

        $evaluacion = Evaluacion::findOrFail($this->evaluacion_seleccionada_id);

        // Validar que todas las notas funcionales estén diligenciadas y entre 0 y 100
        if (empty($this->calificaciones)) {
            session()->flash('error', 'No hay compromisos funcionales para calificar.');

            return false;
        }

        foreach ($this->calificaciones as $cid => $nota) {
            if ($nota === '' || $nota === null || ! is_numeric($nota) || $nota < 0 || $nota > 100) {
                session()->flash('error', 'Todas las calificaciones funcionales deben ser valores numéricos entre 0 y 100.');

                return false;
            }
        }

        if (empty($this->calificaciones_comportamentales)) {
            session()->flash('error', 'No hay conductas comportamentales para calificar.');

            return false;
        }

        foreach ($this->calificaciones_comportamentales as $key => $nota) {
            if ($nota === '' || $nota === null || ! is_numeric($nota) || $nota < 0 || $nota > 100) {
                session()->flash('error', 'Todas las calificaciones comportamentales deben ser valores numéricos entre 0 y 100.');

                return false;
            }
        }

        // GUARDADO FUNCIONAL
        $porcentajeFuncional = 0;
        foreach ($this->calificaciones as $cid => $nota) {
            $notaFloat = (float) $nota;
            $ec = EvaluacionCompromiso::where('evaluacion_id', $evaluacion->id)
                ->where('compromiso_funcional_id', $cid)->first();

            if ($ec) {
                $ec->update(['calificacion' => $notaFloat]);
                $peso = $ec->compromisoFuncional ? (float) $ec->compromisoFuncional->peso : 0;
                $porcentajeFuncional += ($notaFloat * $peso / 100);
            }
        }

        // Ponderar al 85% de la evaluación total
        $puntajeTotalFuncional = round(($porcentajeFuncional * 85) / 100, 2);

        // GUARDADO COMPORTAMENTAL
        $sumaNotasComportamentales = 0;
        $cantidadConductas = count($this->calificaciones_comportamentales);

        foreach ($this->calificaciones_comportamentales as $key => $nota) {
            $notaFloat = (float) $nota;
            [$cc_id, $conducta_id] = explode('_', $key);
            $ecomp = EvaluacionComportamental::where('evaluacion_id', $evaluacion->id)
                ->where('compromiso_comportamental_id', $cc_id)
                ->where('conducta_id', $conducta_id)
                ->first();

            if ($ecomp) {
                $ecomp->update(['calificacion' => $notaFloat]);
                $sumaNotasComportamentales += $notaFloat;
            }
        }

        $promedioComportamental = $cantidadConductas > 0 ? ($sumaNotasComportamentales / $cantidadConductas) : 0;
        // Ponderar al 15% de la evaluación total
        $puntajeTotalComportamental = round(($promedioComportamental * 15) / 100, 2);

        $evaluacion->update([
            'puntaje_funcional_obtenido' => $puntajeTotalFuncional,
            'puntaje_comportamental_obtenido' => $puntajeTotalComportamental,
        ]);

        session()->flash('message', 'Calificaciones guardadas exitosamente.');

        return true;
    }

    public function notificarEvaluacion(EvaluacionConsolidacionService $consolidacionService)
    {
        if ($this->rolActual !== 'evaluador') {
            return;
        }

        // Guardar primero las calificaciones diligenciadas en el formulario
        if (! $this->saveCalificaciones()) {
            return;
        }

        $evaluacion = Evaluacion::findOrFail($this->evaluacion_seleccionada_id);
        $evaluacion->update([
            'estado' => 'calificada',
            'fecha_evaluacion' => now(),
        ]);

        $this->showGradeModal = false;

        $causalLower = mb_strtolower(trim($evaluacion->causal));

        if ($causalLower === 'parcial segundo semestre') {
            $consolidacionService->generarConsolidacionDefinitiva($this->concertacion);
            session()->flash('message', 'Evaluación del segundo semestre notificada exitosamente y Evaluación Consolidada Definitiva del Periodo generada automáticamente.');
        } elseif ($causalLower === 'consolidación definitiva') {
            session()->flash('message', 'Evaluación Consolidada Definitiva notificada al evaluado exitosamente.');
        } else {
            // Si ya existe una consolidación previa y se editó/notificó otra evaluación, actualizamos la consolidación
            if ($this->concertacion->tieneEvaluacionDefinitiva()) {
                $consolidacionService->generarConsolidacionDefinitiva($this->concertacion);
            }
            session()->flash('message', 'Evaluación enviada al evaluado exitosamente.');
        }

        $this->loadData();
    }
    
    public function acceptEvaluacion($evaluacion_id)
    {
        if ($this->rolActual !== 'evaluado') {
            return;
        }
        $evaluacion = Evaluacion::findOrFail($evaluacion_id);
        $evaluacion->update(['estado' => 'aceptada']);
        $this->loadData();
        session()->flash('message', 'Evaluación aceptada exitosamente.');
    }

    public function render()
    {
        return view('livewire.evaluaciones.evaluacion-component')
            ->layout('layouts.app');
    }
}
