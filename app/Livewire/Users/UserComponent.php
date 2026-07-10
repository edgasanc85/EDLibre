<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserComponent extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    // Propiedades de Búsqueda y Estado
    public $search = '';
    public $perPage = 10;
    
    // Campos del modelo
    public $selected_id;
    public $tipo_documento, $numero_documento, $name, $email, $password;
    public $is_admin = false;
    
    // Estado de Modales o Vistas de Formulario
    public $isFormOpen = false;
    public $isEditMode = false;

    // Reglas de Validación
    protected function rules() {
        return [
            'tipo_documento' => 'required|in:Cédula Ciudadanía,Cédula Extranjería,Pasaporte',
            'numero_documento' => 'required|string|max:50|unique:users,numero_documento,' . $this->selected_id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->selected_id,
            'is_admin' => 'boolean',
        ];
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function mount() {
        // Simple admin check
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acceso denegado. Solo administradores pueden ver esta sección.');
        }
    }

    public function render() {
        // Filtrar estrictamente registros activos (is_admin = true) según la regla de persistencia
        $records = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('numero_documento', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.users.user-component', compact('records'))
            ->layout('layouts.app');
    }

    public function resetInputFields() {
        $this->tipo_documento = '';
        $this->numero_documento = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->is_admin = false;
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
        
        $this->validate([
            'password' => 'required|min:8'
        ]);

        User::create([
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => $this->is_admin,
            'is_admin' => true // Por defecto activo
        ]);

        session()->flash('message', 'Usuario creado exitosamente.');
        $this->isFormOpen = false;
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = User::where('is_admin', true)->findOrFail($id);
        $this->selected_id = $id;
        $this->tipo_documento = $record->tipo_documento;
        $this->numero_documento = $record->numero_documento;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->is_admin = $record->is_admin;
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate();

        if ($this->selected_id) {
            $record = User::where('is_admin', true)->findOrFail($this->selected_id);
            
            $data = [
                'tipo_documento' => $this->tipo_documento,
                'numero_documento' => $this->numero_documento,
                'name' => $this->name,
                'email' => $this->email,
                'is_admin' => $this->is_admin,
            ];
            
            if (!empty($this->password)) {
                $this->validate(['password' => 'min:8']);
                $data['password'] = Hash::make($this->password);
            }
            
            $record->update($data);

            session()->flash('message', 'Usuario actualizado exitosamente.');
            $this->isFormOpen = false;
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        // En lugar de Hard Delete, ejecutamos borrado lógico cambiando el estado a false
        $record = User::where('is_admin', true)->findOrFail($id);
        
        if ($record->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propio usuario activo.');
            return;
        }
        
        $record->update(['is_admin' => false]);
        
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }
}
