<?php

namespace App\Livewire\Dashboard;

use App\Models\Dependencia;
use App\Models\Evaluado;
use App\Models\Periodo;
use App\Models\Concertacion;
use Livewire\Component;

class DashboardAdminComponent extends Component
{
    public $periodos = [];
    public $selectedPeriodoId = null;
    public $search = '';

    public function mount()
    {
        $this->periodos = Periodo::orderBy('vigencia', 'desc')->get();
        
        $periodoActivo = $this->periodos->where('activo', true)->first();
        if ($periodoActivo) {
            $this->selectedPeriodoId = $periodoActivo->id;
        } elseif ($this->periodos->isNotEmpty()) {
            $this->selectedPeriodoId = $this->periodos->first()->id;
        }
    }

    public function render()
    {
        $dependenciasAgrupadas = collect();

        if ($this->selectedPeriodoId) {
            // Evaluados con concertaciones en este periodo
            $query = Concertacion::with([
                'evaluado.user',
                'evaluado.dependencia',
                'evaluador.user',
                'evaluaciones'
            ])->where('periodo_id', $this->selectedPeriodoId);

            if (!empty($this->search)) {
                $searchTerm = '%' . strtolower($this->search) . '%';
                $query->whereHas('evaluado.user', function($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(numero_documento) LIKE ?', [$searchTerm]);
                });
            }

            $concertaciones = $query->get();

            $dependenciasAgrupadas = $concertaciones->groupBy(function ($c) {
                return $c->evaluado->dependencia->nombre ?? 'Sin Dependencia';
            });
        }

        return view('livewire.dashboard.dashboard-admin-component', [
            'dependenciasAgrupadas' => $dependenciasAgrupadas
        ]);
    }
}
