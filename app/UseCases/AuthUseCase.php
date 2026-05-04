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

        $_SESSION['usuario']  = $user['Nombreusuario'];
        $_SESSION['logueado'] = true;
        $_SESSION['user_id']  = (int)$user['ID'];

        if (!empty($post['remember']) && Env::get('REMEMBER_ME_ENABLED', 'true') === 'true') {
            $plainToken = $this->authService->issueRememberToken((int)$user['ID']);
            Security::setRememberCookie($user['ID'] . ':' . $plainToken);
        }

        return ['success' => true];
    }

    public function handleRememberLogin(): bool
    {
        if (Env::get('REMEMBER_ME_ENABLED', 'true') !== 'true') {
            return false;
        }

        $cookie = Security::getRememberCookie();
        if ($cookie === null) {
            return false;
        }

        $user = $this->authService->validateRememberToken($cookie);
        if ($user === null) {
            Security::clearRememberCookie();
            return false;
        }

        Security::startSession();
        session_regenerate_id(true);

        $_SESSION['usuario']  = $user['Nombreusuario'];
        $_SESSION['logueado'] = true;
        $_SESSION['user_id']  = $user['ID'];

        $plainToken = $this->authService->issueRememberToken($user['ID']);
        Security::setRememberCookie($user['ID'] . ':' . $plainToken);

        return true;
    }

    public function handleLogout(): void
    {
        Security::startSession();

        if (isset($_SESSION['user_id'])) {
            $this->authService->revokeRememberToken((int)$_SESSION['user_id']);
        }

        session_unset();
        session_destroy();
        Security::clearRememberCookie();
    }
}
