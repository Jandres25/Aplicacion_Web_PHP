<?php
/**
 * Core Bootstrap - Orquestador global del sistema
 */

require_once __DIR__ . '/Env.php';
require_once __DIR__ . '/../config/app.php'; 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Flash.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;
use Core\Flash;

// Iniciar Middleware de Autenticación
$authMiddleware = new AuthMiddleware();

// Solo requerir login si NO estamos en la página de login
$currentUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
if (strpos($currentUri, '/login') === false) {
    $authMiddleware->requireLogin(PUBLIC_PATH . 'login');
}

/**
 * Estas variables deben estar disponibles en el scope de las vistas
 */
$nombreUsuario = $authMiddleware->currentUser();
$public_base = PUBLIC_PATH; 

// Manejo de redirecciones con mensajes legacy (?mensaje=...)
if (isset($_GET['mensaje'])) {
    Flash::set($_GET['mensaje'], 'success');
    $queryParams = $_GET;
    unset($queryParams['mensaje']);

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = is_string($path) ? $path : '';
    $redirectUrl = $path;
    if (!empty($queryParams)) {
        $redirectUrl .= '?' . http_build_query($queryParams);
    }

    header('Location:' . $redirectUrl);
    exit();
}

// Consumir flash para la vista
$flash = Flash::consume();
