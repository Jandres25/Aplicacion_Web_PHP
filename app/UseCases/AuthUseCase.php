<?php

namespace App\UseCases;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use Config\Database;
use Core\Env;
use Core\Security;

class AuthUseCase
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public static function fromEnvironment(): self
    {
        $repository = new UserRepository(Database::getConnection());
        $service = new AuthService($repository);
        return new self($service);
    }

    public function handleLogin(array $post): array
    {
        if (empty($post['usuario']) || empty($post['password'])) {
            return ['success' => false, 'mensaje' => 'Por favor, complete todos los campos.'];
        }

        $user = $this->authService->authenticate($post['usuario'], $post['password']);
        if ($user === null) {
            return ['success' => false, 'mensaje' => 'El usuario o la contraseña son incorrectos'];
        }

        Security::startSession();
        session_regenerate_id(true);

        $_SESSION['usuario'] = $user['Nombreusuario'];
        $_SESSION['logueado'] = true;

        return ['success' => true];
    }
}
