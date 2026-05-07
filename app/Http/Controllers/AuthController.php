<?php

namespace App\Http\Controllers;

use App\Middleware\AuthMiddleware;
use App\UseCases\AuthUseCase;
use Core\Env;
use Core\Security;
use Core\View;

class AuthController extends Controller
{
    private AuthUseCase $authUseCase;

    public function __construct(AuthMiddleware $authMiddleware, AuthUseCase $authUseCase)
    {
        parent::__construct($authMiddleware);
        $this->authUseCase = $authUseCase;
    }

    public function showLogin(): void
    {
        Security::startSession();

        if (isset($_SESSION['logueado'])) {
            $this->redirect('');
        }

        View::render('auth/login.php', [
            'public_base'     => $this->publicBaseUrl,
            'formAction'      => 'login',
            'mensaje'         => '',
            'csrfToken'       => Security::getCsrfToken(),
            'rememberEnabled' => Env::get('REMEMBER_ME_ENABLED', 'true'),
        ]);
    }

    public function login(): void
    {
        $rememberEnabled = Env::get('REMEMBER_ME_ENABLED', 'true');

        if (!$this->hasValidCsrfToken($_POST)) {
            View::render('auth/login.php', [
                'public_base'     => $this->publicBaseUrl,
                'formAction'      => 'login',
                'mensaje'         => 'Solicitud inválida, recargue la página e intente nuevamente.',
                'csrfToken'       => Security::getCsrfToken(),
                'rememberEnabled' => $rememberEnabled,
            ]);
            return;
        }

        $result = $this->authUseCase->handleLogin($_POST);

        if ($result['success']) {
            $this->redirect('');
            return;
        }

        View::render('auth/login.php', [
            'public_base'     => $this->publicBaseUrl,
            'formAction'      => 'login',
            'mensaje'         => (string)$result['mensaje'],
            'csrfToken'       => Security::getCsrfToken(),
            'rememberEnabled' => $rememberEnabled,
        ]);
    }

    public function logout(): void
    {
        if (!$this->hasValidCsrfToken($_POST)) {
            \Core\Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('');
        }

        $this->authUseCase->handleLogout();
        $this->redirect('login');
    }
}
