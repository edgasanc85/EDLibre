# Registro de Cambios (Changelog)

Todos los cambios notables realizados en este proyecto están documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y este proyecto se adhiere a las directrices de la **Comisión Nacional del Servicio Civil (CNSC)** para la **Evaluación del Desempeño Laboral (EDL)**.

---

## [1.2.0] - 2026-08-20

### 🚀 Añadido
- **Servicio de Consolidación Definitiva de Evaluaciones (`EvaluacionConsolidacionService`)**:
  - Ponderación matemática exacta por número real de días evaluados: $\frac{\sum (\text{Puntaje}_i \times \text{Días}_i)}{\sum \text{Días}_i}$.
  - Generación y actualización automática de la *Evaluación Consolidada Definitiva del Periodo* al notificar la *Evaluación Parcial del Segundo Semestre*.
  - Ponderación desagregada tanto para los totales institucionales (85% Funcional / 15% Comportamental) como para cada compromiso funcional y conducta individual.
  - Sincronización idempotente (`updateOrCreate`) para evitar registros duplicados.
- **Asociación de Evidencias a Eventos de Evaluación (`evaluacion_id`)**:
  - Migración `2026_08_20_153452_add_evaluacion_id_to_evidencia_funcionals_table` que añade la clave foránea `evaluacion_id` a la tabla `evidencia_funcionals`.
  - Relaciones Eloquent bidireccionales entre `EvidenciaFuncional` y `Evaluacion`.
  - Selector obligatorio de evaluación en el formulario de cargue de evidencias.
  - Columna identificadora de *Evaluación Asociada* en la tabla de evidencias del servidor público.
  - Resaltado y etiquetado con badge `Esta Evaluación` en el modal de calificación del evaluador.
- **Flujo Integral de Aprobación de Concertación para Evaluadores**:
  - Botón directo de **Aprobar Concertación** (`approveConcertacion`) en la vista [MisEvaluadosComponent](app/Livewire/MisEvaluados/MisEvaluadosComponent.php).
  - Indicadores visuales de estado de concertación: `Pendiente Aprobación` (Badge amarillo), `Concertación Aprobada` (Badge verde), `En Borrador` y `Sin Concertar`.
- **Carga Continua de Evidencias a lo largo del Periodo**:
  - Eliminación de bloqueos permanentes (`evidencias_enviadas`) permitiendo al evaluado registrar evidencias en cualquier momento del año.
- **Suite Completa de Pruebas Automatizadas con Pest**:
  - [EvaluacionConsolidadaTest.php](tests/Feature/EvaluacionConsolidadaTest.php): Ponderación de días, generación por Livewire, consolidación a 100 puntos y exportación a PDF.
  - [ConcertacionAprobacionTest.php](tests/Feature/ConcertacionAprobacionTest.php): Validación del flujo de aprobación directa e indirecta.
  - [EvidenciasContinuasTest.php](tests/Feature/EvidenciasContinuasTest.php): Registro continuo de evidencias asociadas a evaluaciones.

### 🔧 Corregido
- **Cálculo de Ponderación Funcional (Error 87.25 pts corregido a 100.00 pts)**:
  - Se corrigió la doble aplicación del factor de escala del 85% en `saveCalificaciones()` dentro de [EvaluacionComponent.php](app/Livewire/Evaluaciones/EvaluacionComponent.php).
  - Se implementó la fórmula de porcentaje de cumplimiento normalizada por la suma de pesos:
    $$\text{Puntaje Funcional} = \left(\frac{\sum \text{Nota}_i \times \text{Peso}_i}{\sum \text{Peso}_i}\right) \times 0.85$$
  - Al obtener 100 en todas las calificaciones, el puntaje funcional resultante es exactamente **85.00 / 85.00**, el comportamental **15.00 / 15.00** y el total institucional **100.00 / 100.00**.
- **Persistencia de Calificaciones al Notificar**:
  - Se aseguró que `notificarEvaluacion()` ejecute primero la validación y guardado completo en base de datos (`saveCalificaciones()`) antes de emitir la notificación al evaluado.
  - Se inicializan de forma segura mediante `firstOrCreate` todos los registros de `EvaluacionCompromiso` y `EvaluacionComportamental` al abrir el modal de calificación.
- **Contraste y Estilos en Gestión de Evidencias**:
  - Corrección de etiquetas, títulos y tablas en [evidencia-component.blade.php](resources/views/livewire/evidencias/evidencia-component.blade.php) aplicando clases explícitas `text-dark` para garantizar máxima legibilidad sobre fondos claros.

---

## [1.1.0] - 2026-08-15

### 🚀 Añadido
- **Fechas de Periodo Evaluado**: Adición de `periodo_evaluado_inicio` y `periodo_evaluado_fin` a la tabla `evaluacions` para soportar periodos semestrales y eventuales con cálculo dinámico de días.
- **Exportación Oficial a PDF**: Controladores [ConcertacionPdfController](app/Http/Controllers/ConcertacionPdfController.php) y [EvaluacionPdfController](app/Http/Controllers/EvaluacionPdfController.php) con plantillas adaptadas al formato legal de evaluación del desempeño.

### 🛡️ Seguridad
- Control de acceso por dependencia activa para evaluadores y evaluados.
- Validación de estados de solo lectura para concertaciones finalizadas (`aprobado`, `fijado_de_oficio`).
- Aceptación obligatoria de calificaciones por parte del funcionario evaluado antes de permitir la descarga del certificado PDF definitivo.

---

## [1.0.0] - 2026-07-15

### 🚀 Lanzamiento Inicial
- Arquitectura base en Laravel 11, Livewire 3 y Bootstrap 5.
- Módulos administrativos: Usuarios, Dependencias, Niveles Jerárquicos, Competencias Comportamentales, Conductas Asociadas y Periodos.
- Fijación de compromisos funcionales (85%) y competencias comportamentales (15%).
- Sistema de borrado lógico booleano (`activo = true/false`).
