<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use Config\Database;
use Core\Env;

class AuthController
{
    private $authService;
    private $baseUrl;

    public function __construct(AuthService $authService, $baseUrl)
    {
        $this->authService = $authService;
        $this->baseUrl = rtrim($baseUrl, '/') . '/';
    }

    public static function fromEnvironment()
    {
        $baseUrl = Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/');
        $repository = new UserRepository(Database::getConnection());
        $service = new AuthService($repository);
        return new self($service, $baseUrl);
    }

    public function handleLogin($post)
    {
        if (empty($post['usuario']) || empty($post['password'])) {
            return ['mensaje' => 'Por favor, complete todos los campos.'];
        }

        $user = $this->authService->authenticate($post['usuario'], $post['password']);
        if ($user === null) {
            return ['mensaje' => 'El usuario o la contraseña son incorrectos'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario'] = $user['Nombreusuario'];
        $_SESSION['logueado'] = true;

        header('Location:' . $this->baseUrl . 'index.php');
        exit();
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
