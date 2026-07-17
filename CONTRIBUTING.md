# Guía de Contribución para EDLibre 🇨🇴

¡Gracias por tu interés en contribuir a **EDLibre**! Como un proyecto de código abierto enfocado en el sector público colombiano, dependemos de la comunidad para mejorar, auditar y hacer crecer esta plataforma. 

Esta guía detalla cómo puedes ser parte del proyecto, ya sea reportando errores, sugiriendo mejoras o enviando código.

---

## 🧭 Índice
1. [Código de Conducta](#-código-de-conducta)
2. [¿Cómo puedo contribuir?](#-cómo-puedo-contribuir)
    - [Reportar un Bug](#reportar-un-bug)
    - [Sugerir una Funcionalidad](#sugerir-una-funcionalidad)
    - [Contribuir con Código](#contribuir-con-código)
3. [Entorno de Desarrollo](#-entorno-de-desarrollo)
4. [Proceso para Pull Requests (PR)](#-proceso-para-pull-requests-pr)
5. [Estándares de Código y Seguridad](#-estándares-de-código-y-seguridad)
6. [Licencia](#-licencia)

---

## 📜 Código de Conducta

Al participar en este proyecto, aceptas mantener un ambiente de respeto, colaboración y profesionalismo. No se tolerará ningún tipo de acoso o discriminación. Mantén las discusiones enfocadas en la tecnología, la legislación y la mejora del software.

## 🤝 ¿Cómo puedo contribuir?

### Reportar un Bug
Si encuentras un error, por favor abre un **Issue** en el repositorio. Asegúrate de incluir:
- Una descripción clara del problema.
- Los pasos exactos para reproducirlo.
- El comportamiento esperado vs. el comportamiento actual.
- Tu entorno (versión de PHP, navegador, sistema operativo).
- Capturas de pantalla o logs de error si aplican.

### Sugerir una Funcionalidad
Dado que EDLibre está atado a normativas gubernamentales (como los lineamientos del DAFP), cualquier nueva funcionalidad debe estar alineada con la ley o aportar valor demostrable.
Abre un **Issue** tipo "Feature Request" explicando:
- Cuál es la necesidad o el problema.
- Cómo propones solucionarlo.
- (Opcional) Referencias a la normativa colombiana pertinente.

### Contribuir con Código
Si deseas arreglar un bug o implementar una nueva característica:
1. Revisa los *Issues* existentes y comenta en el que desees trabajar para evitar esfuerzos duplicados.
2. Si es una característica grande o un cambio arquitectónico, discútelo primero en un Issue antes de empezar a programar.

---

## 🛠️ Entorno de Desarrollo

EDLibre es una aplicación basada en **Laravel 11**, **Livewire 3** y **Bootstrap 5**. Para preparar tu entorno local:

1. **Haz un Fork** del repositorio a tu cuenta de GitHub.
2. **Clona** tu fork localmente:
   ```bash
   git clone https://github.com/TU_USUARIO/EDLibre.git
   cd EDLibre
   ```
3. **Instala las dependencias**:
   ```bash
   composer install
   npm install
   ```
4. **Configura tu entorno**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configura las credenciales de tu base de datos en el archivo `.env`.*
5. **Prepara la base de datos** (recuerda configurar el primer administrador en el `DatabaseSeeder` si lo requieres):
   ```bash
   php artisan migrate --seed
   ```
6. **Compila los assets frontend**:
   ```bash
   npm run build # o npm run dev
   ```

---

## 🔄 Proceso para Pull Requests (PR)

1. Crea una rama (`branch`) descriptiva desde `main` o la rama de desarrollo actual:
   ```bash
   git checkout -b feature/nueva-funcionalidad
   # o
   git checkout -b fix/correccion-error
   ```
2. Realiza tus cambios y haz *commits* descriptivos y claros.
3. Asegúrate de que tu código pasa las pruebas y estándares (ver sección de Estándares).
4. Haz push a tu fork:
   ```bash
   git push origin tu-rama
   ```
5. Abre un **Pull Request (PR)** en el repositorio original.
6. En la descripción del PR, explica detalladamente qué cambiaste, por qué lo cambiaste y enlaza cualquier Issue relacionado (ej. `Fixes #12`).

---

## ⚖️ Estándares de Código y Seguridad

Para mantener la calidad y seguridad de la plataforma institucional:

- **Estilo de Código (PHP):** Seguimos los estándares de Laravel. Antes de enviar tu PR, asegúrate de formatear tu código usando Pint:
  ```bash
  vendor/bin/pint
  ```
- **Pruebas (Testing):** Utilizamos **Pest PHP** para nuestras pruebas. Si agregas una nueva funcionalidad, por favor incluye las pruebas correspondientes. Ejecuta la suite antes de hacer el PR:
  ```bash
  php artisan test
  ```
- **Seguridad (ISO/IEC 27001):** Como software que maneja datos del sector público, el desarrollo debe orientarse por principios de seguridad en el diseño. Protege siempre las rutas, valida las entradas (Form Requests), usa autorización (Policies) y evita inyecciones o exposición de datos sensibles.
- **Borrado Lógico:** No eliminamos registros físicamente. Utilizamos un sistema de borrado lógico booleano (ej. `is_active`). Ten esto en cuenta al desarrollar modelos e interfaces.
- **UI/UX:** Utilizamos Bootstrap 5. Mantén la coherencia visual usando los componentes y clases existentes del panel de administración (SB Admin Pro - Dark Sidenav).

---

## 📄 Licencia

Al contribuir a EDLibre, aceptas que tus contribuciones se licenciarán bajo los mismos términos del proyecto original, es decir, bajo la **Licencia Pública General de GNU v3.0 (GPLv3)**. Esto garantiza que el software y tus mejoras seguirán siendo libres y abiertas para todas las instituciones del Estado.

¡Gracias de nuevo por ayudar a modernizar y transparentar la gestión pública en Colombia!
