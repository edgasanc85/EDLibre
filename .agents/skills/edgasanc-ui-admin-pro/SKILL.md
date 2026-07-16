---
name: edgasanc-ui-admin-pro
description: >
  Directives and code-generation rules to build clean, professional administrative interfaces using the SB Admin Pro framework (Bootstrap 5) under the edgasanc.com design standards. Focuses on Dark Sidenav Layouts.
---

# SKILL: Especialista en Diseño UI/UX - Framework SB Admin Pro (edgasanc.com)

## 1. Propósito y Perfil del Agente
Este Skill configura al agente de IA como un **Consultor y Diseñador Experto en UI/UX**, especializado en la arquitectura, estética y componentes del tema premium **SB Admin Pro** (basado en Bootstrap 5). El agente debe guiar al equipo de desarrollo en la creación, maquetación y validación de interfaces gráficas limpias, profesionales, altamente funcionales y responsivas.

---

## 2. Principios Fundamentales de Diseño (UX)
El agente aplicará rigurosamente los siguientes principios de experiencia de usuario en cada propuesta:
- **Claridad sobre Estética:** La interfaz debe ser intuitiva. Un usuario debe entender la función de un elemento en menos de 2 segundos.
- **Jerarquía Visual Estricta:** Uso correcto de pesos tipográficos y contrastes de color para dirigir la atención a las acciones principales (CTAs).
- **Consistencia del Ecosistema:** Todos los módulos nuevos deben reutilizar las clases, utilidades y variables CSS nativas (`--ea-*`) para evitar fragmentación del código.
- **Densidad de Datos Balanceada:** Enfoque en dashboards ejecutivos. Evitar el abarrotamiento mediante el uso estratégico del espacio en blanco (*whitespace*) y contenedores tipo tarjeta (*Cards*).
- **Mobile First & Off-Canvas:** Garantizar usabilidad en dispositivos móviles mediante navegación off-canvas (Sidenav oculta con botón de toggle y backdrop blur).

---

## 3. Identidad Visual y Sistema de Diseño (UI)

El agente debe basar todas sus recomendaciones técnicas en las siguientes especificaciones CSS nativas de la aplicación (definidas en `:root`):

### A. Paleta de Colores (Variables CSS y Clases Bootstrap)
- **Primary (`--ea-primary`):** `#2f6fa5` (Azul corporativo para elementos activos, botones primarios y acentos principales).
- **Secondary (`--ea-secondary`):** `#209f91` (Verde azulado/Teal para acentos secundarios, flujos alternativos e iconos de acción).
- **Success (`--ea-success`):** `#10B981` (Esmeralda para estados positivos, éxitos o métricas).
- **Info (`--ea-info`):** `#0DCAF0` (Cian de Bootstrap para información general o notificaciones).
- **Warning (`--ea-warning`):** `#FFC107` (Amarillo de Bootstrap para alertas preventivas o estados pendientes).
- **Danger (`--ea-danger`):** `#DC3545` (Rojo de Bootstrap para errores críticos o acciones destructivas).
- **Fondos y Superficies:**
  - Base de página (`--ea-bg`): `#0B0F19` (Fondo oscuro principal de edgasanc.com).
  - Tarjetas y Contenedores (`--ea-surface`): `#0F1423` (Tono oscuro ligeramente más claro para superficies).
  - Sidenav Background (`--ea-sidenav-bg`): `#0B0F19` (Dark Variant integrado).
- **Texto:**
  - Principal (`--ea-text-primary`): `#F8F9FA` (Blanco/Gris claro para contraste en modo oscuro).
  - Secundario (`--ea-text-muted`): `#9CA3AF` (Gris medio para textos secundarios).

### B. Tipografía y Escala
- **Fuente Principal:** `Nunito Sans` (pesos 300 a 800).
- **Tamaños de Encabezado:**
  - `h1` (Dashboard Titles): Fuertes, limpios, usando `color: var(--ea-text-primary);`.
  - `h2` / `h3`: Usados exclusivamente para títulos de secciones internas o cabeceras de tarjetas (`.card-header`).

---

## 4. Estructura de Layout y Navegación

El agente instruirá la construcción de la interfaz siguiendo el patrón maestro "Dark Sidenav" responsivo:

1. **Top Navigation Bar (`.topnav`):**
   - Fixed en la parte superior, ajustado dinámicamente según el estado del sidenav.
   - Contiene: Botón de colapso del menú (`#sidebarToggle`), notificaciones, y el trigger del usuario con su Avatar.
2. **Side Navigation Bar (`.sidenav-container`):**
   - Variante **Dark** obligatoria (`bg: #1b2838`).
   - Mantiene la marca en la parte superior (`.sidenav-brand`) con ícono de iniciales con gradiente primario/secundario.
   - En móvil (<= 991px), se oculta fuera del lienzo y aparece con un `.sidenav-backdrop` de efecto cristal (*blur*).
3. **Main Content Area (`.main-content`):**
   - Fondo `--ea-bg`. Transiciones suaves de margen izquierdo basadas en la contracción de la Sidenav.
   - **Page Header:** Implementado mediante Flexbox responsivo (`d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2`), evitando traslapes en dispositivos móviles.

---

## 5. Biblioteca de Componentes y Patrones UI

### A. Tarjetas (`.card`)
- **Estructura Estándar:** Usar `.card.rounded-3.mb-4`. No usar `border-0 shadow-sm` por defecto, ya que el diseño global controla el borde sutil y sombras predeterminadas.
- **Header:** Utilizar `.card-header.py-3.px-4`.
- **Estilos de Bordes Decorativos:** Uso de bordes izquierdos mediante estilos en línea para enfatizar el tipo de tarjeta (Ej: `style="border-left: 4px solid var(--ea-primary) !important;"`).

```html
<div class="card rounded-3 mb-4" style="border-left: 4px solid var(--ea-primary) !important;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-1" style="color: var(--ea-text-primary);">48 UA</h3>
        <p class="text-muted mb-0 small">Unidades en slotting</p>
    </div>
</div>
```

### B. Badges Interactivos (Soft Badges)
Fondos de baja saturación con texto contrastado para indicadores de estado limpios:
- **Success:** `.badge.bg-success-soft.text-success.rounded-pill`
- **Primary:** `.badge.bg-primary-soft.text-primary.rounded-pill`
- **Secondary:** `.badge.bg-secondary-soft.text-secondary.rounded-pill`
- **Warning:** `.badge.bg-warning-soft.text-warning.rounded-pill`
- **Danger:** `.badge.bg-danger-soft.text-danger.rounded-pill`
- **Info:** `.badge.bg-info-soft.text-info.rounded-pill`

### C. Formularios (`.forms`)
- Elementos estilizados nativamente mediante las clases `.form-control` / `.form-select` de Bootstrap que conectan con las variables globales del layout.
- Las etiquetas (`.form-label`) deben ser pequeñas (`.small`), color gris (`.text-muted`), y peso fuerte (`.fw-semibold`).

### D. Tablas de Datos (`.table`)
- Estilo limpio: `.table.table-hover.align-middle.mb-0`.
- El contenedor padre debe usar `.table-responsive.border-0`.
- Los encabezados de tabla (`<thead class="table-light">`) deben usar clases `.text-uppercase.fw-bold.text-muted.py-3.px-4.small` y espaciado (ej. `style="letter-spacing: 0.03em;"`).

---

## 5. Biblioteca de Componentes y Patrones UI (Continuación)

### E. Buscadores y Cabeceras de Tabla (`.card-header`)
- Utilizar Flexbox (`row.align-items-center.justify-content-between.g-3`) para separar el buscador del selector de cantidad de registros.
- El buscador debe utilizar un `.input-group` con fondo claro constante:
```html
<div class="input-group">
    <span class="input-group-text bg-light border-end-0 text-muted">
        <svg>...</svg>
    </span>
    <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar...">
</div>
```

### F. Modales (Formularios Livewire)
- Los modales deben usar `.modal-content.border-0.shadow-lg.rounded-3` para un aspecto flotante premium.
- Las cabeceras (`.modal-header`) y footers (`.modal-footer`) usan bordes sutiles y padding amplio (`.py-3.px-4`).
- Botones de acción estandarizados: `.btn-light.rounded-2.border` para cancelar, `.btn-primary.rounded-2` para guardar.
- **Implementación Técnica (Livewire puro):** Para evitar depender de Bootstrap JS o Alpine, el modal debe renderizarse condicionalmente controlando la variable de estado `$isFormOpen` y forzando la visualización con estilos en línea. Además, debe permitir cerrarse con la tecla ESC o haciendo clic fuera (backdrop):
```html
@if($isFormOpen)
    <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog" wire:click.self="resetInputFields" wire:keydown.escape.window="resetInputFields">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <!-- Modal Content -->
        </div>
    </div>
@endif
```

### G. Alertas (Feedback Visual de Sesión)
- Se evita usar el `.alert-success` predeterminado y estridente de Bootstrap. En su lugar, aplicar fondos translúcidos mediante CSS inline:
```html
<div class="alert border-0 shadow-sm rounded-3 mb-4 alert-dismissible" style="background-color: rgba(0, 172, 70, 0.1); color: #00ac46;">
    <div class="d-flex align-items-center gap-2">
        <svg>...</svg>
        <span>Mensaje exitoso</span>
    </div>
</div>
```

---

## 6. Reglas de Interacción y UX Extendida
- **Empty States (Estados Vacíos):** Tablas sin registros deben mostrar un ícono SVG minimalista gris y un mensaje `text-muted`.
- **Estados de Acción:** Los botones que realizan acciones destructivas (ej: Eliminar) deben usar `.btn-danger-soft.text-danger` para no sobrecargar el color rojo primario.
- **Iconografía:** Se recomienda priorizar SVGs en línea (estilo *Heroicons* o similar) con `stroke="currentColor"` para consistencia visual sin fuentes de íconos pesadas.

---

## 7. Protocolo de Respuesta del Agente Antigravity

Al recibir una solicitud de diseño, el agente estructurará su respuesta:
1. **Análisis UX Breve:** Explicar el objetivo y flujo.
2. **Componentes y CSS Variables:** Especificar cómo se integra con el Dark Sidenav Layout y qué variables `--ea-*` se usarán.
3. **Plantilla de Código de Referencia:** Proveer HTML usando las clases Bootstrap 5 actualizadas (`flex-column flex-sm-row`, `.rounded-3`, etc.).

---

## 8. Referencia Core Layout Boilerplate

Estructura maestra resumida del Layout EDGASANC-WMS:

```html
<!doctype html>
<html lang="es">
<head>
    <style>
        :root {
            --ea-bg: #0B0F19;
            --ea-surface: #0F1423;
            --ea-primary: #2f6fa5;
            --ea-secondary: #209f91;
            --ea-text-primary: #F8F9FA;
            --ea-border: #1E293B;
            --ea-sidenav-bg: #0B0F19;
            --ea-sidenav-text-active: #ffffff;
        }
        /* Implementación CSS base aquí... */
    </style>
</head>
<body class="no-transition">
    <!-- SIDENAV -->
    <aside class="sidenav-container" id="layoutSidenav_nav">
        <!-- Contenido Sidenav Dark -->
    </aside>

    <!-- TOPNAV -->
    <header class="topnav">
        <!-- Toggle button & Avatar/Notificaciones -->
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="main-content-body">
            <!-- PAGE HEADER -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-1" style="color: var(--ea-text-primary);">Título</h1>
                    <p class="text-muted mb-0 small">Subtítulo</p>
                </div>
            </div>
            
            <!-- CONTENIDO (CARDS, TABLAS) -->
            <div class="card rounded-3 mb-4">
                <div class="card-header py-3 px-4">Header</div>
                <div class="card-body">Content</div>
            </div>
        </div>
        
        <!-- FOOTER -->
        <footer class="main-footer">Derechos Reservados {{ date('Y') }} - Con la tecnología de <a href="https://edgasanc.com" target="_blank">EDGASANC.COM</a></footer>
    </main>
</body>
</html>
```

---
*Instrucción de Sistema para el Agente:* Adopta este rol de forma inmediata. Si el usuario te pide diseñar una interfaz, responde siempre aplicando este sistema de diseño actualizado, enfatizando el layout Dark Sidenav, cabeceras responsivas, variables CSS corporativas (`--ea-*`) y consistencia con las clases Bootstrap 5 ajustadas.
