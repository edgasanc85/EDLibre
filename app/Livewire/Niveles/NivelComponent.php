<?php

namespace App\Livewire\Niveles;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Nivel;

class NivelComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    
    public $selected_id;
    public $nombre;
    
    public $isFormOpen = false;
    public $isEditMode = false;

    protected function rules() {
        return [
            'nombre' => 'required|string|max:255',
        ];
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function render() {
        $records = Nivel::active()
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.niveles.nivel-component', compact('records'))
            ->layout('layouts.app');
    }

    public function resetInputFields() {
        $this->nombre = '';
        $this->selected_id = null;
        $this->isEditMode = false;
        $this->isFormOpen = false;
    }

    public function create() {
        $this->resetInputFields();
        $this->isFormOpen = true;
    }

    public function store() {
        $this->validate();

        Nivel::create([
            'nombre' => $this->nombre,
            'activo' => true
        ]);

        session()->flash('message', 'Nivel creado exitosamente.');
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = Nivel::active()->findOrFail($id);
        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate();

        if ($this->selected_id) {
            $record = Nivel::active()->findOrFail($this->selected_id);
            
            $record->update([
                'nombre' => $this->nombre
            ]);

            session()->flash('message', 'Nivel actualizado exitosamente.');
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $record = Nivel::active()->findOrFail($id);
        
        $record->update(['activo' => false]);
        
        session()->flash('message', 'Nivel eliminado exitosamente.');
    }
}
