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

La aplicación usa un framework PHP propio con Composer PSR-4 y separación estricta de responsabilidades por capas:

```
┌─────────────────────────────────────────────────────────────┐
│  HTTP Layer                                                  │
│  routes/web.php              ← Container->resolve(Ctrl)     │
│  app/Http/Controllers/       ← un controller por módulo     │
│  app/Http/Requests/          ← DTOs tipados desde $_POST    │
│  app/Middleware/AuthMiddleware                               │
└──────────────────────────┬──────────────────────────────────┘
                           │ OperationResult / typed Requests
┌──────────────────────────▼──────────────────────────────────┐
│  Application Layer                                           │
│  app/UseCases/               ← orquesta sin awareness HTTP  │
│  app/UseCases/DTOs/          ← OperationResult              │
└──────────────────────────┬──────────────────────────────────┘
                           │ Domain Models / Contracts
┌──────────────────────────▼──────────────────────────────────┐
│  Domain Layer                                                │
│  app/Domain/Models/          ← POPOs (Employee, Position…)  │
│  app/Domain/Contracts/       ← interfaces de repositorio    │
│  app/Services/               ← lógica de negocio            │
└──────────────────────────┬──────────────────────────────────┘
                           │ PDO / archivos
┌──────────────────────────▼──────────────────────────────────┐
│  Infrastructure Layer                                        │
│  app/Repositories/           ← implementan los contratos    │
│  app/Infrastructure/         ← EmployeeFileStorage          │
│  config/Database.php         ← PDO singleton                │
└─────────────────────────────────────────────────────────────┘
  Cross-cutting: core/Container · Router · View · Flash · Security · Env
```

**Flujo de una petición POST:**
`index.php` → `Router` → `Controller` → `XxxRequest::fromArray($_POST)` → `validate()` → `UseCase` → `Service` → `Repository` → DB → `OperationResult`

## Características

- Arquitectura por capas clásica: HTTP → Application → Domain → Infrastructure
- DI Container liviano con resolución por reflexión (`core/Container.php`)
- Request DTOs tipados — `$_POST` nunca cruza la frontera HTTP
- Domain Models como POPOs con `fromRow()` / `toArray()`
- Interfaces de repositorio para inversión de dependencias
- Autenticación con `password_hash` / `password_verify` y sesiones PHP
- "Recuérdame" con token rotante almacenado hasheado en DB y cookie `HttpOnly`/`SameSite=Lax`
- Protección CSRF en formularios y peticiones AJAX (meta tag + header)
- Eliminación asíncrona con AJAX + SweetAlert2 sin recargar la página
- Notificaciones Flash integradas con SweetAlert2
- DataTables con búsqueda, paginación y diseño responsivo
- Generación de cartas de recomendación en PDF con dompdf (abre inline en el visor del navegador)
- Prevención de SQL injection con sentencias preparadas PDO

## Dependencias

| Paquete         | Versión | Uso                                        |
| --------------- | ------- | ------------------------------------------ |
| `dompdf/dompdf` | ^3.1    | Generación de PDF (carta de recomendación) |

Todas las dependencias se instalan con `composer install`.

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
`tbl-usuarios` incluye columnas `remember_token` y `remember_token_expires` para la función "Recuérdame".  
Archivos subidos en `public/storage/uploads/`.

---

<div align="center">
  <p>&copy; 2026 Desarrollado por <b>Jose Andres Meneces Lopez</b> - Proyecto de Ingeniería de Sistemas</p>
</div>
