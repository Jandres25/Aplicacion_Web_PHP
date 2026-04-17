<?php

require_once __DIR__ . '/core/Env.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Repositories/UserRepository.php';
require_once __DIR__ . '/app/Services/AuthService.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';

use App\Controllers\AuthController;
use Core\Env;

Env::load(__DIR__ . '/.env');

$controller = AuthController::fromEnvironment();
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $resultado = $controller->handleLogin($_POST);
  $mensaje = isset($resultado['mensaje']) ? $resultado['mensaje'] : null;
}

require __DIR__ . '/app/Views/auth/login.php';
