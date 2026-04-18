<div align="center">
  <img src="public/img/deadpool.ico" width="80" alt="Logo">
  <h1>🚀 Sistema de Gestión Empresarial PHP</h1>
  <p>Una aplicación web robusta construida con arquitectura por capas en PHP Vanilla, enfocada en la mantenibilidad, escalabilidad y una experiencia de usuario moderna.</p>

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Architecture](https://img.shields.io/badge/architecture-Layered-orange.svg)](#arquitectura-por-capas)

</div>

---

## 📋 Descripción del Proyecto

Este sistema es una solución integral para la gestión de recursos humanos, que permite el control de **empleados**, **puestos de trabajo** y **usuarios del sistema**. Ha evolucionado de un script simple a una aplicación empresarial estructurada siguiendo los principios de **Clean Architecture**.

## 🏗️ Arquitectura por Capas

La aplicación implementa una separación estricta de responsabilidades para facilitar el mantenimiento y las pruebas:

- **Controladores (`Controllers`)**: Manejan las peticiones HTTP y coordinan la respuesta.
- **Servicios (`Services`)**: Orquestan la lógica de negocio pura.
- **Repositorios (`Repositories`)**: Abstraen el acceso a datos (SQL/PDO).
- **Middleware**: Gestionan la seguridad y autenticación en las rutas.
- **Infraestructura**: Servicios externos como almacenamiento de archivos y generación de PDFs (DomPDF).

## 🚀 Características Principales

- **Frontend Moderno**: Interfaz basada en **Bootstrap 5** con un diseño limpio y profesional.
- **DataTables Avanzados**: Listados responsivos con búsqueda rápida y paginación.
- **Notificaciones Dinámicas**: Sistema de mensajes Flash integrados con **SweetAlert2**.
- **Seguridad**: Autenticación centralizada, protección de rutas y prevención de inyección SQL mediante PDO.
- **Generación de Documentos**: Creación automatizada de cartas de recomendación en formato PDF.
- **Estructura Front Controller**: Enrutamiento amigable y centralizado en `public/index.php`.

## 🎨 Diseño e Interfaz de Usuario (UI/UX)

Se ha implementado un sistema de diseño consistente y moderno a lo largo de todos los módulos de la aplicación, elevando la calidad visual y la experiencia del usuario:

### 🌟 Patrón de Diseño Consistente
Todos los módulos (Usuarios, Empleados y Puestos) comparten una estructura visual uniforme que facilita la navegación:
- **Tarjetas con Sombra (`shadow-sm`)**: Proporcionan profundidad y una separación clara de las secciones del sistema.
- **Iconografía Intuitiva**: Integración extensiva de **FontAwesome 6** en encabezados, grupos de entrada y botones de acción para una navegación visual más rápida.
- **Tipografía y Jerarquía**: Uso de fuentes legibles y contrastes adecuados, con etiquetas en negrita y textos de ayuda claros.

### 📊 Listados Inteligentes
- **Tablas Modernas**: Implementación de tablas interactivas con encabezados oscuros, efectos de hover y alineación vertical media.
- **Estados Visuales**: Uso de insignias (`badges`) de colores para puestos, IDs y estados, permitiendo una lectura rápida de la información.
- **Acciones Agrupadas**: Botones de acción (Editar, Eliminar, Ver) organizados en grupos estilizados con tooltips para evitar el desorden visual.

### 📝 Formularios Optimizados
- **Grupos de Entrada con Iconos**: Entradas de datos enriquecidas visualmente para indicar el tipo de información requerida.
- **Distribución Multicolumna**: Formularios complejos (como el de Empleados) organizados en rejillas de dos columnas para reducir el desplazamiento vertical.
- **Gestión de Archivos Mejorada**: Previsualización circular de fotos de perfil y enlaces directos a documentos CV dentro de los formularios de edición.
- **Feedback en Tiempo Real**: Alertas de validación y mensajes de error integrados de forma elegante en la parte superior de los formularios.

---

## 🛠️ Instalación y Configuración

1. **Requisitos**: PHP 8.0+, Servidor Web (Apache/Nginx) y MySQL/MariaDB.
2. **Clonar y Preparar**:
   ```bash
   cp .env.example .env
   ```
3. **Configuración**: Ajustar las credenciales de base de datos y la `APP_URL` en el archivo `.env`.
4. **Base de Datos**: Importar el archivo `app.sql` en tu gestor de base de datos.
5. **Acceso**: Apuntar tu servidor web a la carpeta `public/` o acceder vía `http://localhost/Aplicacion_Web_PHP/public/`.

## 📂 Estructura del Directorio

```text
├── app/                # Lógica de la aplicación (MVC + Capas)
├── config/             # Configuración y Conexiones Singleton
├── core/               # Componentes núcleo (Router, Flash, Env)
├── public/             # Único punto de entrada y activos estáticos
├── routes/             # Definición de rutas del sistema
└── libs/               # Librerías externas (DomPDF, etc.)
```

---

<div align="center">
  <p>&copy; 2026 Desarrollado por <b>Jose Andres Meneces Lopez</b> - Proyecto de Ingeniería de Sistemas</p>
  <p><i>Construido con ❤️ para la gestión eficiente de datos.</i></p>
</div>
