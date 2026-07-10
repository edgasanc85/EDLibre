<?php

namespace App\Livewire\Periodos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Periodo;
use Illuminate\Validation\Rule;

class PeriodoComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    
    public $selected_id;
    public $vigencia, $fecha_inicio, $fecha_fin;
    
    public $isFormOpen = false;
    public $isEditMode = false;

    protected function rules() {
        return [
            'vigencia' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('periodos')->where(function ($query) {
                    return $query->where('activo', true);
                })->ignore($this->selected_id)
            ],
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ];
    }

    protected $messages = [
        'vigencia.unique' => 'Ya existe un periodo registrado y activo para esta vigencia.',
        'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
    ];

    public function updatingSearch() {
        $this->resetPage();
    }

    public function render() {
        $records = Periodo::active()
            ->where('vigencia', 'like', '%' . $this->search . '%')
            ->orderBy('vigencia', 'desc')
            ->paginate($this->perPage);

        return view('livewire.periodos.periodo-component', compact('records'))
            ->layout('layouts.app');
    }

    public function resetInputFields() {
        $this->vigencia = '';
        $this->fecha_inicio = '';
        $this->fecha_fin = '';
        $this->selected_id = null;
        $this->isEditMode = false;
        $this->isFormOpen = false;
    }

    public function create() {
        $this->resetInputFields();
        $this->vigencia = date('Y') . '-' . (date('Y') + 1);
        $this->fecha_inicio = date('Y-01-01');
        $this->fecha_fin = date('Y-12-31');
        $this->isFormOpen = true;
    }

    public function store() {
        $this->validate();

        Periodo::create([
            'vigencia' => $this->vigencia,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'activo' => true
        ]);

        session()->flash('message', 'Periodo creado exitosamente.');
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = Periodo::active()->findOrFail($id);
        $this->selected_id = $id;
        $this->vigencia = $record->vigencia;
        
        // Formatear las fechas para los inputs tipo date
        $this->fecha_inicio = $record->fecha_inicio->format('Y-m-d');
        $this->fecha_fin = $record->fecha_fin->format('Y-m-d');
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate();

        if ($this->selected_id) {
            $record = Periodo::active()->findOrFail($this->selected_id);
            
            $record->update([
                'vigencia' => $this->vigencia,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
            ]);

            session()->flash('message', 'Periodo actualizado exitosamente.');
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $record = Periodo::active()->findOrFail($id);
        
        $record->update(['activo' => false]);
        
        session()->flash('message', 'Periodo eliminado exitosamente.');
    }
}
