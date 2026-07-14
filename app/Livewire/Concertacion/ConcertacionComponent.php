<?php

namespace App\Livewire\Concertacion;

use Livewire\Component;
use App\Models\Concertacion;
use App\Models\CompromisoFuncional;
use App\Models\CompromisoComportamental;
use App\Models\Conducta;
use App\Models\Competencia;
use App\Models\Evaluado;
use App\Models\Evaluador;
use App\Models\Periodo;

class ConcertacionComponent extends Component
{
    public $evaluado_id;
    public $periodo_id;
    public $concertacion = null;

    public $funcionales = [];
    public $comportamentales = [];

    public $isReadOnly = false;

    // Dependencias para selects
    public $competenciasDisponibles = [];
    public $conductasPorCompetencia = [];
    
    // Rol determinado por el sistema basado en el usuario logueado
    public $rolActual = 'evaluado'; // 'evaluado' o 'evaluador'

    public function mount($evaluado_id, $periodo_id)
    {
        $this->evaluado_id = $evaluado_id;
        $this->periodo_id = $periodo_id;
        
        $evaluado = Evaluado::findOrFail($this->evaluado_id);
        $user_id = auth()->id();

        if ($evaluado->user_id == $user_id) {
            $this->rolActual = 'evaluado';
        } else {
            // Verificar si el usuario actual es un evaluador activo de la dependencia del evaluado
            $esEvaluador = Evaluador::where('user_id', $user_id)
                ->where('dependencia_id', $evaluado->dependencia_id)
                ->active()
                ->exists();
                
            if ($esEvaluador) {
                $this->rolActual = 'evaluador';
            } else {
                abort(403, 'No tienes permisos para acceder a esta concertación.');
            }
        }

        $this->loadData();
    }

    public function loadData()
    {
        $evaluado = Evaluado::with('nivel')->findOrFail($this->evaluado_id);
        
        // Cargar competencias por nivel
        $this->competenciasDisponibles = Competencia::active()
            ->whereIn('nivel_id', [1, $evaluado->nivel_id])
            ->get();
            
        // Pre-cargar las conductas para no hacer consultas repetitivas
        foreach ($this->competenciasDisponibles as $comp) {
            $this->conductasPorCompetencia[$comp->id] = Conducta::active()->where('competencia_id', $comp->id)->get();
        }

        $this->concertacion = Concertacion::where('evaluado_id', $this->evaluado_id)
            ->where('periodo_id', $this->periodo_id)
            ->with(['compromisosFuncionals', 'compromisosComportamentals.conductas'])
            ->first();

        if ($this->concertacion) {
            $this->funcionales = $this->concertacion->compromisosFuncionals->map(function ($cf) {
                return [
                    'id' => $cf->id,
                    'verbo' => $cf->verbo,
                    'objeto' => $cf->objeto,
                    'condicion' => $cf->condicion,
                    'peso' => $cf->peso,
                ];
            })->toArray();

            $this->comportamentales = $this->concertacion->compromisosComportamentals->map(function ($cc) {
                return [
                    'id' => $cc->id,
                    'competencia_id' => $cc->competencia_id,
                    'conductas_ids' => $cc->conductas->pluck('id')->toArray(),
                ];
            })->toArray();
            
            if (in_array($this->concertacion->estado, ['en_revision', 'aprobado', 'fijado_de_oficio'])) {
                $this->isReadOnly = true;
            }
        } else {
            // Empezar con 3 vacíos para funcionales y 3 para comportamentales
            for ($i = 0; $i < 3; $i++) {
                $this->addFuncional();
                $this->addComportamental();
            }
        }
    }

    public function addFuncional()
    {
        if (count($this->funcionales) < 5) {
            $this->funcionales[] = ['id' => null, 'verbo' => '', 'objeto' => '', 'condicion' => '', 'peso' => ''];
        }
    }

    public function removeFuncional($index)
    {
        if (count($this->funcionales) > 3) {
            unset($this->funcionales[$index]);
            $this->funcionales = array_values($this->funcionales);
        } else {
            session()->flash('error', 'Debe haber mínimo 3 compromisos funcionales.');
        }
    }

    public function addComportamental()
    {
        if (count($this->comportamentales) < 5) {
            $this->comportamentales[] = ['id' => null, 'competencia_id' => '', 'conductas_ids' => []];
        }
    }

    public function removeComportamental($index)
    {
        if (count($this->comportamentales) > 3) {
            unset($this->comportamentales[$index]);
            $this->comportamentales = array_values($this->comportamentales);
        } else {
            session()->flash('error', 'Debe haber mínimo 3 compromisos comportamentales.');
        }
    }

    public function toggleConducta($comportamentalIndex, $conductaId)
    {
        $current = $this->comportamentales[$comportamentalIndex]['conductas_ids'] ?? [];
        
        if (in_array($conductaId, $current)) {
            $current = array_diff($current, [$conductaId]);
        } else {
            if (count($current) < 4) {
                $current[] = $conductaId;
            } else {
                session()->flash('error', 'Máximo 4 conductas por competencia.');
            }
        }
        
        $this->comportamentales[$comportamentalIndex]['conductas_ids'] = array_values($current);
    }

    public function validateConcertacion()
    {
        $this->resetErrorBag();
        $hasErrors = false;

        if (count($this->funcionales) < 3 || count($this->funcionales) > 5) {
            $this->addError('general', 'Debe fijar entre 3 y 5 compromisos funcionales.');
            $hasErrors = true;
        }

        if (count($this->comportamentales) < 3 || count($this->comportamentales) > 5) {
            $this->addError('general', 'Debe fijar entre 3 y 5 compromisos comportamentales.');
            $hasErrors = true;
        }

        $sumaPesos = 0;
        foreach ($this->funcionales as $i => $cf) {
            if (empty($cf['verbo']) || empty($cf['objeto']) || empty($cf['condicion']) || empty($cf['peso'])) {
                $this->addError("funcional.{$i}", 'Todos los campos del compromiso funcional son obligatorios.');
                $hasErrors = true;
            } else {
                $sumaPesos += (float) $cf['peso'];
            }
        }

        if ($sumaPesos != 85) {
            $this->addError('general', "La suma de los pesos de los compromisos funcionales ({$sumaPesos}%) debe ser exactamente 85%.");
            $hasErrors = true;
        }

        foreach ($this->comportamentales as $i => $cc) {
            if (empty($cc['competencia_id'])) {
                $this->addError("comportamental.{$i}", 'Debe seleccionar una competencia.');
                $hasErrors = true;
            }
            if (count($cc['conductas_ids']) < 3 || count($cc['conductas_ids']) > 4) {
                $this->addError("comportamental.{$i}", 'Debe seleccionar entre 3 y 4 conductas.');
                $hasErrors = true;
            }
        }

        return !$hasErrors;
    }

    public function saveDraft()
    {
        $this->saveData('borrador');
        session()->flash('message', 'Borrador guardado exitosamente.');
    }

    public function sendToEvaluador()
    {
        if ($this->validateConcertacion()) {
            $this->saveData('en_revision');
            session()->flash('message', 'Compromisos enviados a revisión.');
        }
    }

    public function approve()
    {
        if ($this->validateConcertacion()) {
            $this->saveData('aprobado');
            session()->flash('message', 'Compromisos aprobados exitosamente.');
        }
    }

    public function fixDeOficio()
    {
        if ($this->validateConcertacion()) {
            $this->saveData('fijado_de_oficio');
            session()->flash('message', 'Compromisos fijados de oficio exitosamente.');
        }
    }

    private function saveData($estado)
    {
        if (!$this->concertacion) {
            $this->concertacion = Concertacion::create([
                'evaluado_id' => $this->evaluado_id,
                'periodo_id' => $this->periodo_id,
                'estado' => $estado,
            ]);
        } else {
            $this->concertacion->update(['estado' => $estado]);
        }

        // Si se envió a revisión por el evaluado, registrar fecha
        if ($estado == 'en_revision' && $this->rolActual == 'evaluado') {
            $this->concertacion->update(['fecha_aprobacion_evaluado' => now()]);
        }

        if (in_array($estado, ['aprobado', 'fijado_de_oficio']) && $this->rolActual == 'evaluador') {
            $this->concertacion->update(['fecha_aprobacion_evaluador' => now()]);
        }

        // Limpiar compromisos viejos y recrear
        $this->concertacion->compromisosFuncionals()->delete();
        foreach ($this->funcionales as $cf) {
            if (!empty($cf['verbo'])) {
                CompromisoFuncional::create([
                    'evaluado_id' => $this->evaluado_id,
                    'periodo_id' => $this->periodo_id,
                    'concertacion_id' => $this->concertacion->id,
                    'verbo' => $cf['verbo'],
                    'objeto' => $cf['objeto'],
                    'condicion' => $cf['condicion'],
                    'peso' => $cf['peso'],
                ]);
            }
        }

        $this->concertacion->compromisosComportamentals()->delete();
        foreach ($this->comportamentales as $cc) {
            if (!empty($cc['competencia_id'])) {
                $comp = CompromisoComportamental::create([
                    'evaluado_id' => $this->evaluado_id,
                    'periodo_id' => $this->periodo_id,
                    'concertacion_id' => $this->concertacion->id,
                    'competencia_id' => $cc['competencia_id'],
                ]);
                $comp->conductas()->sync($cc['conductas_ids']);
            }
        }
        
        $this->loadData(); // Recargar IDs
    }

    public function render()
    {
        return view('livewire.concertacion.concertacion-component')
            ->layout('layouts.app');
    }
}
