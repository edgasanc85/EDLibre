<?php

namespace App\Livewire\Competencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Competencia;
use App\Models\Conducta;
use App\Models\Nivel;

class CompetenciaComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    
    public $selected_id;
    public $nivel_id, $nombre, $descripcion;
    
    // Arreglo para almacenar las conductas dinámicamente
    public $conductas = [];
    
    public $isFormOpen = false;
    public $isEditMode = false;

    protected function rules() {
        return [
            'nivel_id' => 'required|exists:nivels,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'conductas.*.descripcion' => 'required|string',
        ];
    }

    protected $messages = [
        'nivel_id.required' => 'Debe seleccionar un nivel.',
        'conductas.*.descripcion.required' => 'La descripción de la conducta es obligatoria.',
    ];

    public function updatingSearch() {
        $this->resetPage();
    }

    public function render() {
        $records = Competencia::active()
            ->with('nivel')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
            
        $niveles = Nivel::active()->get();

        return view('livewire.competencias.competencia-component', compact('records', 'niveles'))
            ->layout('layouts.app');
    }

    public function resetInputFields() {
        $this->nivel_id = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->selected_id = null;
        $this->conductas = [];
        $this->isEditMode = false;
        $this->isFormOpen = false;
    }

    public function create() {
        $this->resetInputFields();
        $this->addConducta();
        $this->isFormOpen = true;
    }
    
    public function addConducta() {
        $this->conductas[] = ['id' => null, 'descripcion' => ''];
    }
    
    public function removeConducta($index) {
        unset($this->conductas[$index]);
        $this->conductas = array_values($this->conductas); // re-index
    }

    public function store() {
        $this->validate();

        $competencia = Competencia::create([
            'nivel_id' => $this->nivel_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activo' => true
        ]);

        foreach ($this->conductas as $c) {
            if (!empty(trim($c['descripcion']))) {
                Conducta::create([
                    'competencia_id' => $competencia->id,
                    'descripcion' => $c['descripcion'],
                    'activo' => true
                ]);
            }
        }

        session()->flash('message', 'Competencia y conductas registradas exitosamente.');
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = Competencia::active()->findOrFail($id);
        $this->selected_id = $id;
        $this->nivel_id = $record->nivel_id;
        $this->nombre = $record->nombre;
        $this->descripcion = $record->descripcion;
        
        $this->conductas = $record->conductas()->active()->get()->map(function($c) {
            return ['id' => $c->id, 'descripcion' => $c->descripcion];
        })->toArray();
        
        if (count($this->conductas) === 0) {
            $this->addConducta();
        }
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate();

        if ($this->selected_id) {
            $competencia = Competencia::active()->findOrFail($this->selected_id);
            
            $competencia->update([
                'nivel_id' => $this->nivel_id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion
            ]);

            $idsEnFormulario = collect($this->conductas)->pluck('id')->filter()->toArray();
            
            Conducta::where('competencia_id', $competencia->id)
                ->active()
                ->whereNotIn('id', $idsEnFormulario)
                ->update(['activo' => false]);
                
            foreach ($this->conductas as $c) {
                if (!empty(trim($c['descripcion']))) {
                    if (isset($c['id']) && $c['id']) {
                        Conducta::where('id', $c['id'])->update(['descripcion' => $c['descripcion']]);
                    } else {
                        Conducta::create([
                            'competencia_id' => $competencia->id,
                            'descripcion' => $c['descripcion'],
                            'activo' => true
                        ]);
                    }
                }
            }

            session()->flash('message', 'Competencia actualizada exitosamente.');
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $record = Competencia::active()->findOrFail($id);
        
        $record->update(['activo' => false]);
        $record->conductas()->update(['activo' => false]);
        
        session()->flash('message', 'Competencia eliminada exitosamente.');
    }
}
