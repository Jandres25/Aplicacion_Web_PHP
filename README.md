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
