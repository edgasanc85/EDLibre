<?php

namespace App\Livewire\Dependencias;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dependencia;

class DependenciaComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    
    public $selected_id;
    public $nombre, $parent_id;
    
    public $isFormOpen = false;
    public $isEditMode = false;

    protected function rules() {
        return [
            'nombre' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dependencias,id',
        ];
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function render() {
        $records = Dependencia::active()
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
            
        $padres = Dependencia::active()->get();

        return view('livewire.dependencias.dependencia-component', compact('records', 'padres'))
            ->layout('layouts.app');
    }

    public function resetInputFields() {
        $this->nombre = '';
        $this->parent_id = null;
        $this->selected_id = null;
        $this->isEditMode = false;
        $this->isFormOpen = false;
    }

    public function create() {
        $this->resetInputFields();
        
        $count = Dependencia::active()->count();
        if ($count === 0) {
            $this->parent_id = null;
        }

        $this->isFormOpen = true;
    }

    public function store() {
        $this->validate();

        $count = Dependencia::active()->count();
        if ($count === 0) {
            $this->parent_id = null;
        }

        Dependencia::create([
            'nombre' => $this->nombre,
            'parent_id' => $this->parent_id ?: null,
            'activo' => true
        ]);

        session()->flash('message', 'Dependencia creada exitosamente.');
        $this->isFormOpen = false;
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = Dependencia::active()->findOrFail($id);
        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->parent_id = $record->parent_id;
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate();

        if ($this->selected_id) {
            $record = Dependencia::active()->findOrFail($this->selected_id);
            
            if ($this->parent_id == $this->selected_id) {
                $this->addError('parent_id', 'Una dependencia no puede ser su propio padre.');
                return;
            }

            $record->update([
                'nombre' => $this->nombre,
                'parent_id' => $this->parent_id ?: null
            ]);

            session()->flash('message', 'Dependencia actualizada exitosamente.');
            $this->isFormOpen = false;
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $record = Dependencia::active()->findOrFail($id);
        
        if ($record->children()->active()->count() > 0) {
            session()->flash('error', 'No se puede eliminar la dependencia porque tiene dependencias hijas activas.');
            return;
        }

        $record->update(['activo' => false]);
        session()->flash('message', 'Dependencia eliminada exitosamente.');
    }
}
