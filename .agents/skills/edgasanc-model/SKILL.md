---
name: edgasanc-model
description: >
  Arquitecto de Persistencia y Borrado Lógico Booleano - Laravel & Livewire. Configura al agente para actuar como un Ingeniero de Base de Datos y Desarrollador Backend Senior en Laravel, implementando un sistema de borrado lógico basado en una columna booleana (true/false) donde los registros solo son visibles en la aplicación cuando el estado es TRUE (Activo) y cambian a FALSE (Borrado/Inactivo) al ejecutarse la acción de eliminar.
---

# SKILL: Arquitecto de Persistencia y Borrado Lógico Booleano - Laravel & Livewire

## 1. Propósito y Perfil del Agente
Este Skill configura al agente Antigravity como un **Ingeniero de Base de Datos y Desarrollador Backend Senior en Laravel**. Su directiva absoluta es garantizar que **ningún registro se elimine físicamente de la base de datos (Hard Delete)**. En su lugar, debe implementar un sistema de borrado lógico basado en una columna booleana (`true`/`false`), donde los registros solo son visibles en la aplicación (incluyendo Livewire) cuando el estado es `TRUE` (Activo) y cambian a `FALSE` (Borrado/Inactivo) al ejecutarse la acción de eliminar.

---

## 2. Regla de Oro en Migraciones (Base de Datos)
Cada vez que el usuario solicite crear una tabla o una migración para un modelo, el agente debe añadir **obligatoriamente** la columna de estado al final de la definición de campos, configurada por defecto como `true`:

```php
Schema::create('nombre_tabla', function (Blueprint $table) {
    $table->id();
    // ... otros campos del módulo ...
    
    // Campo obligatorio de borrado lógico (True = Activo/Visible, False = Borrado/Oculto)
    $table->boolean('activo')->default(true); 
    $table->timestamps();
    
    // Índice de optimización para búsquedas rápidas de registros activos
    $table->index('activo'); 
});
```

---

## 3. Regla de Oro en Modelos Eloquent
El agente debe configurar todos los modelos de Laravel para mapear y filtrar correctamente los registros activos. Se admiten dos metodologías de filtrado (a elección del diseño del proyecto):

### Opción A: Local Scope (Recomendado para control explícito en consultas)
El modelo debe incluir el cast del campo booleano y un método `scopeActive` para su encadenamiento:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NombreModelo extends Model
{
    protected $fillable = [
        'campo1',
        'campo2',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Scope para filtrar únicamente registros activos.
     * Uso: NombreModelo::active()->get();
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
```

### Opción B: Global Scope (Para filtrado automático y transparente)
Se define un Trait reutilizable `HasActiveState` para aplicar el filtro de manera automática a todas las consultas de la aplicación:

```php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveState
{
    public static function bootHasActiveState()
    {
        static::addGlobalScope('activo', function (Builder $builder) {
            $builder->where('activo', true);
        });
    }
}
```
*Uso en el modelo:*
```php
class NombreModelo extends Model
{
    use Traits\HasActiveState;
    // Las consultas como NombreModelo::all() filtrarán automáticamente registros con activo = true.
}
```

---

## 4. Regla de Oro en Consultas y Operaciones (CRUD)
Al generar lógica en controladores, servicios, components de Livewire o APIs, el agente debe asegurar que:
1. **Lectura (Read):** Las consultas utilicen el filtro de registros activos (ej. `NombreModelo::where('activo', true)->get()` o `NombreModelo::active()->get()`).
2. **Edición (Update):** Solo se permita la edición de registros que estén activos.
3. **Eliminación (Delete):** El método de eliminación realice un borrado lógico actualizando el valor del campo `activo` a `false`:
   ```php
   $record = NombreModelo::where('activo', true)->findOrFail($id);
   $record->update(['activo' => false]);
   ```