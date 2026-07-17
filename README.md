# EDLibre 🇨🇴

> **La plataforma abierta para la evaluación del desempeño de servidores de Libre Nombramiento y Remoción en Colombia.**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Estado del Proyecto](https://img.shields.io/badge/Estado-En%20Desarrollo-orange.svg)]()

---

### **Eslogan**
> *"Transparencia en el servicio, confianza en el desempeño."*

---

## 📌 ¿Qué es EDLibre?

**EDLibre** es un sistema de información y software libre (licenciado bajo **GPLv3**) diseñado específicamente para registrar, gestionar y analizar la **Evaluación del Desempeño Laboral (EDL)** de los servidores públicos de Colombia pertenecientes a empleos de **Libre Nombramiento y Remoción (LNYR)**. 

A diferencia de los sistemas tradicionales enfocados en carrera administrativa, **EDLibre** se adapta a la naturaleza especial de estos cargos, permitiendo estructurar compromisos de gestión y evaluar de forma ágil a funcionarios en los niveles:
*   **Asesor**
*   **Profesional**
*   **Técnico**
*   **Asistencial**

## 💡 ¿Por qué EDLibre?

En el Estado colombiano, los cargos de Libre Nombramiento y Remoción exigen una relación de confianza institucional y un alto estándar técnico. **EDLibre** facilita a las Oficinas de Talento Humano y a los evaluadores un seguimiento transparente y objetivo basado en evidencias, de acuerdo con los lineamientos del Departamento Administrativo de la Función Pública (DAFP).

### Características Clave:
*   **Naturaleza Open Source (GPLv3):** Cero costos de licenciamiento para las alcaldías, gobernaciones y entidades nacionales. Impulsa la soberanía tecnológica del país.
*   **Adaptabilidad Jerárquica:** Formularios y criterios de evaluación dinámicos según el nivel (desde el apoyo operativo en el nivel asistencial hasta la toma de decisiones del nivel asesor).
*   **Enfoque LNYR:** Seguimiento de compromisos y metas institucionales orientados a la confianza y la gestión del cargo.
*   **Reportes y Trazabilidad:** Generación de reportes automáticos e históricos listos para auditorías internas.

## 🛠️ Tecnologías utilizadas
*(Completa esto con tu stack tecnológico actual, por ejemplo: Node.js, Python, PostgreSQL, React, etc.)*

## 🤝 Cómo contribuir
¡La construcción del software público es un esfuerzo colectivo! Puedes colaborar de las siguientes maneras:
1. Reportando errores (Issues).
2. Proponiendo mejoras en la legislación aplicable a la plataforma.
3. Enviando Pull Requests con nuevas funcionalidades.

Consulta nuestro archivo [CONTRIBUTING.md](CONTRIBUTING.md) para más detalles.

## 📄 Licencia
Este proyecto está licenciado bajo la **Licencia Pública General de GNU v3.0 (GPLv3)** - consulta el archivo [LICENSE](LICENSE) para más detalles.

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

## 🚀 Primeros Pasos (Primer Ingreso)

Dado que **EDLibre** es un sistema cerrado institucionalmente, **la ruta de registro público no está habilitada**. Para poder ingresar al sistema por primera vez, es necesario contar con un usuario Administrador creado desde la instalación.

**Antes de ejecutar las migraciones y seeders (Paso 5)**, configure las credenciales de este primer usuario administrador:

1. Abra el archivo `database/seeders/DatabaseSeeder.php`.
2. Localice el bloque donde se crea el "Administrador Sistema".
3. Modifique los campos `numero_documento`, `name`, `email` y `password` con los datos reales de la persona que administrará la plataforma.
4. Ejecute el comando: `php artisan migrate --seed`.

Una vez finalizado, inicie sesión en la aplicación utilizando el correo electrónico y la contraseña que especificó.

## 🛡 Consideraciones

- **Edgasanc UI Admin Pro**: Toda la interfaz utiliza utilidades nativas de Bootstrap 5 con personalizaciones en CSS orientadas a generar efectos glassmorfismo y esquemas oscuros de alta gama.
- **Bajas Lógicas**: Ningún registro central se destruye físicamente de la base de datos (con excepción del borrado en cascada), se utiliza el campo `activo` (`scopeActive`) para su visibilidad en el sistema.

## 📄 Licencia

Este software está licenciado bajo la **[GNU General Public License v3.0 (GPL-3.0)](LICENCE.md)**.

## 📞 Soporte Técnico

Para obtener soporte técnico profesional en la instalación, configuración y mantenimiento de la aplicación, por favor contacte a: **[gerencia@edgasanc.com](mailto:gerencia@edgasanc.com)**
