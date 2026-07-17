<p align="center">
  <img src="https://edlibre.edgasanc.com/logo_horizontal.svg" alt="EDLibre Logo" width="400">
</p>

# EDLibre 🇨🇴

> **La plataforma abierta para la evaluación del desempeño de servidores de Libre Nombramiento y Remoción en Colombia.**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Estado del Proyecto](https://img.shields.io/badge/Estado-BETA-blueviolet.svg)]()

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

## 🛠 Instalación y Configuración

1. **Clonar Repositorio**: `git clone https://github.com/edgasanc85/EDLibre`
2. **Dependencias**: `composer install` y `npm install`
3. **Entorno**: Copiar `.env.example` a `.env` y configurar credenciales de base de datos.
4. **Key**: `php artisan key:generate`
5. **Base de Datos**: `php artisan migrate --seed`
6. **Compilar Activos**: `npm run build` o `npm run dev`

## 🚀 Primeros Pasos (Primer Ingreso)

**Antes de ejecutar las migraciones y seeders (Paso 5)**, configure las credenciales de este primer usuario administrador:

1. Abra el archivo `database/seeders/DatabaseSeeder.php`.
2. Localice el bloque donde se crea el "Administrador Sistema".
3. Modifique los campos `numero_documento`, `name`, `email` y `password` con los datos reales de la persona que administrará la plataforma.
4. Ejecute el comando: `php artisan migrate --seed`.

Dado que **EDLibre** es un sistema cerrado institucionalmente, **la ruta de registro público no está habilitada**. Para poder ingresar al sistema por primera vez, es necesario contar con un usuario Administrador creado desde la instalación.

Una vez finalizado, inicie sesión en la aplicación utilizando el correo electrónico y la contraseña que especificó.

## 📚 Documentación Oficial

La documentación detallada del sistema, guías paso a paso e información de roles está publicada y siempre actualizada en:
**[https://edlibre.edgasanc.com/documentacion](https://edlibre.edgasanc.com/documentacion)**

## 📄 Licencia

Este software está licenciado bajo la **[GNU General Public License v3.0 (GPL-3.0)](LICENCE.md)**.

## 📞 Soporte Técnico

Para obtener soporte técnico profesional en la instalación, configuración y mantenimiento de la aplicación, por favor contacte a: **[gerencia@edgasanc.com](mailto:gerencia@edgasanc.com)**
