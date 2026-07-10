---
name: edgasanc-crud
description: >
  Generador de CRUD para Laravel 11 con Livewire 3 y Bootstrap 5. Configura al agente para actuar como un Ingeniero de Software Senior especializado en el desarrollo rápido de interfaces de administración (CRUD) basadas en el stack moderno de Laravel.
---

# SKILL: Generador Estándar de Componentes CRUD por Defecto - Laravel & Livewire

## 1. Propósito y Perfil del Agente
Este Skill configura al agente Antigravity como un **Ingeniero de Software Principal enfocado en Productividad y Automatización de Arquitectura**. Su función principal es acelerar el desarrollo generando componentes de Livewire que sigan un patrón estricto de **CRUD (Create, Read, Update, Delete)**. El agente asume que cualquier nuevo componente solicitado requiere un CRUD completo, a menos que el usuario especifique de forma explícita: *"Este componente NO es un CRUD"*.

---

## 2. La Regla de Oro: "CRUD por Defecto"
Cuando el usuario mencione una entidad, tabla o concepto (ej. "Haz un componente para gestionar Proveedores"), el agente **no debe preguntar** si requiere un CRUD ni generar un archivo vacío. De forma proactiva, asumirá e implementará:
1. **Búsqueda y Paginación** (Read - filtrando solo registros activos `activo = true`).
2. **Formulario de Creación** con validación (Create).
3. **Formulario de Edición** precargado (Update - validando estado activo).
4. **Acción de Eliminación Lógica** (Delete - cambiando `activo` a `false` en lugar de borrar físicamente).
5. **Integración UI/UX:** Clases de SB Admin Pro.
6. **Integración de Seguridad:** Permisos basados en la matriz RBAC por módulo (`view`, `create`, `edit`, `delete`).

---

## 3. Estructura Estándar del Componente PHP (Livewire 3)
El backend del componente generado siempre debe incluir las siguientes propiedades y métodos estructurados. **Tanto la clase como la vista Blade deben crearse obligatoriamente dentro de una carpeta que tenga el nombre de la tabla en la base de datos** (ej: para la tabla `productos`, la clase irá en `app/Livewire/Productos/` y la vista en `resources/views/livewire/productos/`):

```php
namespace App\Livewire\NombreTabla; // Carpeta con nombre de la tabla (PascalCase/Plural, ej: Productos)

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NombreModelo; // Reemplazar dinámicamente
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NombreModeloComponent extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';
    protected $moduleSlug = 'nombre-modulo'; // Para el RBAC

    // Propiedades de Búsqueda y Estado
    public $search = '';
    public $perPage = 10;
    public $selected_id, $campo1, $campo2; // Campos del modelo
    
    // Estado de Modales o Vistas de Formulario
    public $isFormOpen = false;
    public $isEditMode = false;

    // Reglas de Validación
    protected function rules() {
        return [
            'campo1' => 'required|string|max:255',
            'campo2' => 'required|numeric',
        ];
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function mount() {
        $this->authorize('view-module', $this->moduleSlug);
    }

    public function render() {
        // Filtrar estrictamente registros activos (activo = true) según la regla de persistencia
        $records = NombreModelo::where('activo', true)
            ->where(function($query) {
                $query->where('campo1', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        // Retornar vista dentro de la carpeta con nombre de la tabla (ej. livewire.productos.producto-component)
        return view('livewire.nombre-tabla.nombre-modelo-component', compact('records'));
    }

    public function resetInputFields() {
        $this->campo1 = '';
        $this->campo2 = '';
        $this->selected_id = null;
        $this->isEditMode = false;
    }

    public function create() {
        $this->authorize('create-module', $this->moduleSlug);
        $this->resetInputFields();
        $this->isFormOpen = true;
    }

    public function store() {
        $this->authorize('create-module', $this->moduleSlug);
        $this->validate();

        NombreModelo::create([
            'campo1' => $this->campo1,
            'campo2' => $this->campo2,
            'activo' => true // Por defecto activo
        ]);

        session()->flash('message', 'Registro creado exitosamente.');
        $this->isFormOpen = false;
        $this->resetInputFields();
    }

    public function edit($id) {
        $this->authorize('edit-module', $this->moduleSlug);
        // Validar que el registro esté activo
        $record = NombreModelo::where('activo', true)->findOrFail($id);
        $this->selected_id = $id;
        $this->campo1 = $record->campo1;
        $this->campo2 = $record->campo2;
        
        $this->isEditMode = true;
        $this->isFormOpen = true;
    }

    public function update() {
        $this->authorize('edit-module', $this->moduleSlug);
        $this->validate();

        if ($this->selected_id) {
            // Validar que el registro esté activo
            $record = NombreModelo::where('activo', true)->findOrFail($this->selected_id);
            $record->update([
                'campo1' => $this->campo1,
                'campo2' => $this->campo2,
            ]);

            session()->flash('message', 'Registro actualizado exitosamente.');
            $this->isFormOpen = false;
            $this->resetInputFields();
        }
    }

    public function delete($id) {
        $this->authorize('delete-module', $this->moduleSlug);
        
        // En lugar de Hard Delete, ejecutamos borrado lógico cambiando el estado a false
        $record = NombreModelo::where('activo', true)->findOrFail($id);
        $record->update(['activo' => false]);
        
        session()->flash('message', 'Registro eliminado exitosamente.');
    }
}