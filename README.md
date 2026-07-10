<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
    <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+">
    <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13.x">
    <img src="https://img.shields.io/badge/Livewire-4.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 4.x">
    <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5.x">
</p>

# Sistema de Evaluación del Desempeño Laboral (EDL)

El **Sistema de Evaluación del Desempeño Laboral (EDL)** es una plataforma construida en **Laravel 13** y **Livewire 4** para gestionar, administrar y ejecutar los ciclos de evaluación de rendimiento de los colaboradores de una organización. El sistema utiliza una arquitectura orientada al borrado lógico booleano (`activo = true/false`) y un diseño de interfaz administrativo moderno (Dark Sidenav, Glassmorphism).

## 🏢 Modelos y Lógica de Negocio

El sistema se fundamenta en los siguientes modelos centrales (`app/Models`):

- **User**: Representa a las personas registradas en el sistema. Pueden ser administradores, evaluadores o evaluados.
- **Dependencia**: Define la estructura organizacional jerárquica de la entidad (Entidad Raíz, Subdirecciones, Oficinas, etc.).
- **Nivel**: Agrupa jerárquicamente los diferentes niveles de la entidad (Ej: Directivo, Asesor, Profesional, Técnico). El Nivel ID = 1 representa competencias "Comunes a todos los niveles".
- **Competencia** y **Conducta**: Forman el diccionario o catálogo de evaluación. Las competencias pertenecen a un nivel, y las conductas son los comportamientos observables de cada competencia.
- **Periodo**: Establece las ventanas de tiempo (vigencias como "2026-2027", fechas de inicio y fin) para cada ciclo de evaluación.
- **Evaluador**: Modelo asociativo que le otorga a un `User` el rol de calificar a otras personas dentro de una `Dependencia` específica.
- **Evaluado**: Modelo asociativo que registra a un empleado (`User`) en un cargo específico dentro de una `Dependencia` durante un lapso de tiempo.

## ⚡ Componentes Reactivos (Livewire)

Toda la administración del sistema se realiza sin recargar la página gracias a los componentes interactivos ubicados en `app/Livewire`:

- **`UserComponent`**: Módulo CRUD para la gestión segura de las cuentas de acceso.
- **`DependenciaComponent`**: Permite organizar visualmente el árbol de áreas de la organización.
- **`NivelComponent`**: Define la parametrización de niveles para el esquema de competencias.
- **`CompetenciaComponent`**: Componente Maestro-Detalle complejo que gestiona simultáneamente las Competencias y sus Conductas asociadas.
- **`PeriodoComponent`**: Control de ciclos temporales y cálculo automático de estados (Ej: "En Curso", "Próximo", "Finalizado").
- **`EvaluadorComponent`**: Asignación ágil de evaluadores. Permite incluso la creación de nuevos usuarios directamente desde el formulario (on-the-fly).
- **`EvaluadoComponent`**: Asignación rápida de los colaboradores que serán objeto de medición dentro de la organización.

## 🛠 Instalación y Configuración

1. **Clonar Repositorio**: `git clone https://edgasanc.visualstudio.com/EDL/_git/EDL`
2. **Dependencias**: `composer install` y `npm install`
3. **Entorno**: Copiar `.env.example` a `.env` y configurar credenciales de base de datos.
4. **Key**: `php artisan key:generate`
5. **Base de Datos**: `php artisan migrate --seed`
6. **Compilar Activos**: `npm run build` o `npm run dev`

## 🛡 Consideraciones

- **Edgasanc UI Admin Pro**: Toda la interfaz utiliza utilidades nativas de Bootstrap 5 con personalizaciones en CSS orientadas a generar efectos glassmorfismo y esquemas oscuros de alta gama.
- **Bajas Lógicas**: Ningún registro central se destruye físicamente de la base de datos (con excepción del borrado en cascada), se utiliza el campo `activo` (`scopeActive`) para su visibilidad en el sistema.

## 📄 Licencia

Este software está licenciado bajo la **[GNU General Public License v3.0 (GPL-3.0)](LICENCE.md)**.

## 📞 Soporte Técnico

Para obtener soporte técnico profesional en la instalación, configuración y mantenimiento de la aplicación, por favor contacte a: **[gerencia@edgasanc.com](mailto:gerencia@edgasanc.com)**
