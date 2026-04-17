# Aplicacion web en PHP

La aplicación web que realize esta basada en un video del canal de develoteca: [Crea una aplicación web en php](https://youtu.be/J2LW5__bDkI "Crea una aplicación web con php").

Realize algunos cambios en el login y agregue un paginador que baje cuando haya mas contenido.

## Configuración de entorno

1. Copiar `.env.example` a `.env`.
2. Ajustar credenciales de base de datos y `APP_URL` en `.env`.
3. Ejecutar la aplicación en tu entorno local (XAMPP/LAMPP).

## Front controller (bloque 2)

Se agregó un front controller en `public/index.php` y un router base en `core/Router.php` para iniciar la migración por capas.

- Acceso recomendado en transición: `http://localhost/Aplicacion_Web_PHP/public/`
- Ejemplos de rutas: `/public/login`, `/public/secciones/empleados`, `/public/estilos/style.css`
- Las rutas legacy directas siguen funcionando mientras se migran módulos.

## Migración Auth (bloque 3)

Se migró la autenticación a capas:

- `app/Controllers/AuthController.php`
- `app/Services/AuthService.php`
- `app/Repositories/UserRepository.php`
- `app/Middleware/AuthMiddleware.php`
- `app/Views/auth/login.php`

`login.php` ahora actúa como punto de entrada del módulo Auth y delega la lógica a las capas.

## Convención de namespaces

Durante la migración, los nuevos componentes y los componentes base actualizados deben declararse con `namespace`.

## Singleton en Database

`config/database.php` ahora reutiliza una única instancia de conexión PDO (patrón Singleton) mediante `Database::getConnection()`.

## Migración Empleados (bloque 4)

Se migró el CRUD de empleados y el manejo de archivos a capas:

- `app/Controllers/EmployeeController.php`
- `app/Services/EmployeeService.php`
- `app/Repositories/EmployeeRepository.php`
- `app/Infrastructure/EmployeeFileStorage.php`
- `app/Views/employees/*`
- `secciones/empleados/index.php`, `crear.php`, `editar.php` y `carta_recomendacion.php` ahora delegan lógica de negocio, datos y archivos a las capas.

Estado: **completado**.

## Notificaciones Flash (SweetAlert2 Toast)

Se reemplazó el patrón de mensajes por query string (`?mensaje=...`) por mensajes flash en sesión:

- `core/Flash.php` centraliza `set/consume`.
- `templates/header.php` renderiza toast tipo `top-end`.
- CRUD de `empleados`, `puestos` y `usuarios` ahora redirigen sin contaminar URL.

## Migración Puestos (bloque 5)

Se migró el CRUD de puestos a capas:

- `app/Controllers/PositionController.php`
- `app/Services/PositionService.php`
- `app/Repositories/PositionRepository.php`
- `app/Views/positions/*`
- `secciones/puestos/index.php`, `crear.php` y `editar.php` ahora delegan lógica de negocio y persistencia a las capas.

Estado: **completado**.

## Migración Usuarios (bloque 6)

Se migró el CRUD de usuarios a capas:

- `app/Controllers/UserController.php`
- `app/Services/UserService.php`
- `app/Repositories/UserRepository.php` (extendido para CRUD sin romper Auth)
- `app/Views/users/*`
- `secciones/usuarios/index.php`, `crear.php` y `editar.php` ahora delegan lógica de negocio y persistencia a las capas.

Estado: **completado**.
