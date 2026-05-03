<div align="center">
  <img src="public/img/deadpool.ico" width="80" alt="Logo">
  <h1>Sistema de Gestión Empresarial PHP</h1>
  <p>Aplicación web construida con arquitectura por capas en PHP, enfocada en la mantenibilidad, escalabilidad y una experiencia de usuario moderna.</p>

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Architecture](https://img.shields.io/badge/architecture-Layered-orange.svg)](#arquitectura)

</div>

---

## Descripción

Sistema integral para la gestión de recursos humanos: control de **empleados**, **puestos de trabajo** y **usuarios del sistema**, con generación de cartas de recomendación en PDF.

## Arquitectura

La aplicación usa un framework PHP propio con Composer PSR-4 y separación estricta de responsabilidades:

```
public/index.php          → Front controller (único punto de entrada)
routes/web.php            → Mapeo de rutas a HTTP controllers
app/Http/Controllers/     → Controllers HTTP por módulo (Home, Auth, Employees, Positions, Users)
app/UseCases/             → Lógica de dominio sin dependencias HTTP
app/Services/             → Lógica de negocio
app/Repositories/         → Acceso a datos (PDO)
app/Infrastructure/       → Almacenamiento de archivos (fotos, CVs)
app/Middleware/           → Autenticación
core/                     → Framework: Router, View, Flash, Security, Env, ErrorPage
config/                   → Conexión PDO singleton (Config\Database)
resources/views/          → Vistas PHP organizadas por módulo
```

**Flujo de una petición:**
`index.php` → `Router` → `Http\Controller` → `UseCase` → `Service` → `Repository` → DB

## Características

- Autenticación con `password_hash` / `password_verify` y sesiones PHP
- Protección CSRF en formularios y peticiones AJAX (meta tag + header)
- Eliminación asíncrona con AJAX + SweetAlert2 sin recargar la página
- Notificaciones Flash integradas con SweetAlert2
- DataTables con búsqueda, paginación y diseño responsivo
- Generación de cartas de recomendación en PDF (DomPDF)
- Prevención de SQL injection con sentencias preparadas PDO

## Instalación

**Requisitos:** PHP 8.0+, Apache/Nginx, MySQL/MariaDB, Composer.

```bash
# 1. Copiar y configurar el entorno
cp .env.example .env
# Editar .env con credenciales DB y APP_URL

# 2. Instalar dependencias
composer install

# 3. Importar base de datos
mysql -u root -p your_db < database/schema.sql
mysql -u root -p your_db < database/seeders.sql
```

Apuntar el servidor web a `public/` o acceder vía `http://localhost/Aplicacion_Web_PHP/public/`.

## Frontend

- Bootstrap 5 + FontAwesome 6 (CDN)
- jQuery + DataTables
- SweetAlert2

## Base de datos

Tablas: `tbl-empleados`, `tbl-puestos`, `tbl-usuarios` (los guiones requieren comillas en SQL).  
Archivos subidos en `public/storage/uploads/`.

---

<div align="center">
  <p>&copy; 2026 Desarrollado por <b>Jose Andres Meneces Lopez</b> - Proyecto de Ingeniería de Sistemas</p>
</div>
