<?php

namespace App\Livewire\Evaluadores;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluador;
use App\Models\User;
use App\Models\Dependencia;
use Illuminate\Support\Facades\Hash;

class EvaluadorComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    
    public $selected_id;
    public $user_id, $dependencia_id, $cargo, $fecha_ingreso, $fecha_retiro;
    
    // Propiedades para crear un usuario nuevo
    public $isCreatingUser = false;
    public $new_tipo_documento = 'Cédula Ciudadanía';
    public $new_numero_documento, $new_name, $new_email, $new_password;

    public $isFormOpen = false;
    public $isEditMode = false;

    protected function rules() {
        $rules = [
            'dependencia_id' => 'required|exists:dependencias,id',
            'cargo' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'fecha_retiro' => 'nullable|date|after_or_equal:fecha_ingreso',
        ];

        if ($this->isCreatingUser) {
            $rules['new_tipo_documento'] = 'required|string|in:Cédula Ciudadanía,Cédula Extranjería,Pasaporte';
            $rules['new_numero_documento'] = 'required|string|max:20|unique:users,numero_documento';
            $rules['new_name'] = 'required|string|max:255';
            $rules['new_email'] = 'required|email|max:255|unique:users,email';
            $rules['new_password'] = 'required|string|min:6';
        } else {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    protected $messages = [
        'user_id.required' => 'Debe seleccionar un usuario o crear uno nuevo.',
        'dependencia_id.required' => 'Debe seleccionar una dependencia.',
        'cargo.required' => 'Debe especificar el cargo.',
        'fecha_retiro.after_or_equal' => 'La fecha de retiro no puede ser anterior a la fecha de ingreso.',
    ];

    public function updatingSearch() {
        $this->resetPage();
    }

    public function render() {
        $records = Evaluador::active()
            ->with(['user', 'dependencia'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('numero_documento', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('dependencia', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->orWhere('cargo', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        $usuarios = User::orderBy('name')->get();
        $dependencias = Dependencia::active()->orderBy('nombre')->get();

        return view('livewire.evaluadores.evaluador-component', compact('records', 'usuarios', 'dependencias'))
            ->layout('layouts.app');
    }
    
    public function toggleCreateUser() {
        $this->isCreatingUser = !$this->isCreatingUser;
        if (!$this->isCreatingUser) {
            $this->resetNewUserFields();
        } else {
            $this->user_id = null; // Limpiar selección si va a crear uno
        }
    }

    private function resetNewUserFields() {
        $this->new_tipo_documento = 'Cédula Ciudadanía';
        $this->new_numero_documento = '';
        $this->new_name = '';
        $this->new_email = '';
        $this->new_password = '';
    }

    public function resetInputFields() {
        $this->user_id = null;
        $this->dependencia_id = null;
        $this->cargo = '';
        $this->fecha_ingreso = '';
        $this->fecha_retiro = null;
        $this->selected_id = null;
        $this->isCreatingUser = false;
        $this->resetNewUserFields();
        $this->isEditMode = false;
        $this->isFormOpen = false;
    }

    public function create() {
        $this->resetInputFields();
        $this->fecha_ingreso = date('Y-m-d');
        $this->isFormOpen = true;
    }

    public function store() {
        $this->validate();

        $userIdToAssign = $this->user_id;

        // Si está creando un usuario desde cero
        if ($this->isCreatingUser) {
            $user = User::create([
                'tipo_documento' => $this->new_tipo_documento,
                'numero_documento' => $this->new_numero_documento,
                'name' => $this->new_name,
                'email' => $this->new_email,
                'password' => Hash::make($this->new_password),
                'is_admin' => false,
            ]);
            $userIdToAssign = $user->id;
        }

        Evaluador::create([
            'user_id' => $userIdToAssign,
            'dependencia_id' => $this->dependencia_id,
            'cargo' => $this->cargo,
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_retiro' => empty($this->fecha_retiro) ? null : $this->fecha_retiro,
            'activo' => true
        ]);

        session()->flash('message', 'Evaluador asignado exitosamente.');
        $this->resetInputFields();
    }

    public function edit($id) {
        $record = Evaluador::active()->findOrFail($id);
        $this->selected_id = $id;
        $this->user_id = $record->user_id;
        $this->dependencia_id = $record->dependencia_id;
        $this->cargo = $record->cargo;
        $this->fecha_ingreso = $record->fecha_ingreso ? $record->fecha_ingreso->format('Y-m-d') : '';
        $this->fecha_retiro = $record->fecha_retiro ? $record->fecha_retiro->format('Y-m-d') : null;
        
        $this->isCreatingUser = false; // No se puede cambiar de usuario o crear uno en edición de la asignación
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'dependencia_id' => 'required|exists:dependencias,id',
            'cargo' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'fecha_retiro' => 'nullable|date|after_or_equal:fecha_ingreso',
        ]);

        if ($this->selected_id) {
            $record = Evaluador::active()->findOrFail($this->selected_id);
            
            $record->update([
                'user_id' => $this->user_id,
                'dependencia_id' => $this->dependencia_id,
                'cargo' => $this->cargo,
                'fecha_ingreso' => $this->fecha_ingreso,
                'fecha_retiro' => empty($this->fecha_retiro) ? null : $this->fecha_retiro,
            ]);

            session()->flash('message', 'Asignación de evaluador actualizada exitosamente.');
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $record = Evaluador::active()->findOrFail($id);
        $record->update(['activo' => false]);
        session()->flash('message', 'Asignación eliminada exitosamente.');
    }
}
