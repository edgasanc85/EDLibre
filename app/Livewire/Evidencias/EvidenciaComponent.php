<?php

namespace App\Livewire\Evidencias;

use App\Models\Concertacion;
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
            'compromisosFuncionals.evidencias',
        ])->findOrFail($this->concertacion_id);

        // Security check
        if ($this->concertacion->evaluado->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para ver estas evidencias.');
        }

        if ($this->concertacion->estado !== 'aprobado') {
            abort(403, 'La concertación no está aprobada.');
        }

        // Initialize form array
        foreach ($this->concertacion->compromisosFuncionals as $cf) {
            $this->evidencias_nuevas[$cf->id] = [
                'descripcion' => '',
                'ubicacion' => '',
            ];
        }
    }

    public function saveEvidencia($compromiso_id)
    {
        if ($this->concertacion->evidencias_enviadas) {
            abort(403, 'Las evidencias ya fueron enviadas y no se pueden modificar.');
        }

        $data = $this->evidencias_nuevas[$compromiso_id];

        $this->validate([
            "evidencias_nuevas.$compromiso_id.descripcion" => 'required|string|max:1000',
            "evidencias_nuevas.$compromiso_id.ubicacion" => 'required|url|max:255',
        ], [
            "evidencias_nuevas.$compromiso_id.descripcion.required" => 'La descripción es requerida.',
            "evidencias_nuevas.$compromiso_id.ubicacion.required" => 'La URL es requerida.',
            "evidencias_nuevas.$compromiso_id.ubicacion.url" => 'Debe ser una URL válida (ej. https://drive.google.com/...)',
        ]);

        EvidenciaFuncional::create([
            'compromiso_funcional_id' => $compromiso_id,
            'descripcion' => $data['descripcion'],
            'ubicacion' => $data['ubicacion'],
            'activo' => true,
        ]);

        $this->evidencias_nuevas[$compromiso_id] = ['descripcion' => '', 'ubicacion' => ''];

        session()->flash("message_$compromiso_id", 'Evidencia registrada exitosamente.');
        $this->loadData();
    }

    public function deleteEvidencia($evidencia_id)
    {
        if ($this->concertacion->evidencias_enviadas) {
            abort(403, 'Las evidencias ya fueron enviadas y no se pueden modificar.');
        }

        $evidencia = EvidenciaFuncional::findOrFail($evidencia_id);

        // Verificar que pertenezca a la concertación actual
        if ($evidencia->compromisoFuncional->concertacion_id == $this->concertacion_id) {
            $evidencia->update(['activo' => false]);
            session()->flash("message_{$evidencia->compromiso_funcional_id}", 'Evidencia eliminada exitosamente.');
            $this->loadData();
        }
    }

    public function sendEvidencias()
    {
        if ($this->concertacion->evidencias_enviadas) {
            return;
        }

        $this->concertacion->update(['evidencias_enviadas' => true]);
        session()->flash('message_general', 'Evidencias enviadas correctamente. Ya no se podrán modificar.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.evidencias.evidencia-component')
            ->layout('layouts.app');
    }
}
