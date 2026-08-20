<?php

namespace App\Livewire\Evidencias;

use App\Models\Concertacion;
use App\Models\Evaluacion;
use App\Models\EvidenciaFuncional;
use Livewire\Component;

class EvidenciaComponent extends Component
{
    public $concertacion_id;

    public $concertacion;

    // Formularios
    public $evidencias_nuevas = []; // array of ['compromiso_funcional_id' => X, 'descripcion' => '', 'ubicacion' => '']

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
            'evaluaciones' => function ($q) {
                $q->active();
            },
            'compromisosFuncionals.evidencias' => function ($q) {
                $q->active()->with('evaluacion');
            },
        ])->findOrFail($this->concertacion_id);

        // Security check
        if ($this->concertacion->evaluado->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para ver estas evidencias.');
        }

        if ($this->concertacion->estado !== 'aprobado') {
            abort(403, 'La concertación no está aprobada.');
        }

        // Si no existen evaluaciones aún para la concertación, auto-inicializar las semestrales ordinarias
        if ($this->concertacion->evaluaciones->count() === 0) {
            $pInicio = $this->concertacion->periodo->fecha_inicio;
            $pFin = $this->concertacion->periodo->fecha_fin;
            $midDate = $pInicio->copy()->addMonths(6)->subDay();
            $sem2Start = $pInicio->copy()->addMonths(6);

            Evaluacion::create([
                'concertacion_id' => $this->concertacion->id,
                'causal' => 'Parcial primer semestre',
                'estado' => 'creada',
                'periodo_evaluado_inicio' => $pInicio,
                'periodo_evaluado_fin' => $midDate,
                'activo' => true,
            ]);

            Evaluacion::create([
                'concertacion_id' => $this->concertacion->id,
                'causal' => 'Parcial segundo semestre',
                'estado' => 'creada',
                'periodo_evaluado_inicio' => $sem2Start,
                'periodo_evaluado_fin' => $pFin,
                'activo' => true,
            ]);

            $this->concertacion->load('evaluaciones');
        }

        $primeraEval = $this->concertacion->evaluaciones->first();

        // Initialize form array
        foreach ($this->concertacion->compromisosFuncionals as $cf) {
            if (! isset($this->evidencias_nuevas[$cf->id])) {
                $this->evidencias_nuevas[$cf->id] = [
                    'descripcion' => '',
                    'ubicacion' => '',
                    'evaluacion_id' => $primeraEval ? $primeraEval->id : '',
                ];
            }
        }
    }

    public function saveEvidencia($compromiso_id)
    {
        $data = $this->evidencias_nuevas[$compromiso_id];

        $this->validate([
            "evidencias_nuevas.$compromiso_id.descripcion" => 'required|string|max:1000',
            "evidencias_nuevas.$compromiso_id.ubicacion" => 'required|url|max:255',
            "evidencias_nuevas.$compromiso_id.evaluacion_id" => 'required|exists:evaluacions,id',
        ], [
            "evidencias_nuevas.$compromiso_id.descripcion.required" => 'La descripción es requerida.',
            "evidencias_nuevas.$compromiso_id.ubicacion.required" => 'La URL es requerida.',
            "evidencias_nuevas.$compromiso_id.ubicacion.url" => 'Debe ser una URL válida (ej. https://drive.google.com/...)',
            "evidencias_nuevas.$compromiso_id.evaluacion_id.required" => 'Debe seleccionar la evaluación asociada.',
            "evidencias_nuevas.$compromiso_id.evaluacion_id.exists" => 'La evaluación seleccionada no es válida.',
        ]);

        EvidenciaFuncional::create([
            'compromiso_funcional_id' => $compromiso_id,
            'evaluacion_id' => $data['evaluacion_id'],
            'descripcion' => $data['descripcion'],
            'ubicacion' => $data['ubicacion'],
            'activo' => true,
        ]);

        $primeraEval = $this->concertacion->evaluaciones->first();
        $this->evidencias_nuevas[$compromiso_id] = [
            'descripcion' => '',
            'ubicacion' => '',
            'evaluacion_id' => $data['evaluacion_id'] ?? ($primeraEval ? $primeraEval->id : ''),
        ];

        session()->flash("message_$compromiso_id", 'Evidencia registrada exitosamente.');
        $this->loadData();
    }

    public function deleteEvidencia($evidencia_id)
    {
        $evidencia = EvidenciaFuncional::findOrFail($evidencia_id);

        // Verificar que pertenezca a la concertación actual
        if ($evidencia->compromisoFuncional->concertacion_id == $this->concertacion_id) {
            $evidencia->update(['activo' => false]);
            session()->flash("message_{$evidencia->compromiso_funcional_id}", 'Evidencia eliminada exitosamente.');
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.evidencias.evidencia-component')
            ->layout('layouts.app');
    }
}
