<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Middleware\AuthMiddleware;
use App\UseCases\AuthUseCase;
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
            'rememberEnabled' => $_ENV['REMEMBER_ME_ENABLED'] ?? 'true',
        ]);
    }

    public function login(): void
    {
        $rememberEnabled = $_ENV['REMEMBER_ME_ENABLED'] ?? 'true';

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

        $request = LoginRequest::fromArray($_POST);
        $errors  = $request->validate();
        if ($errors !== []) {
            View::render('auth/login.php', [
                'public_base'     => $this->publicBaseUrl,
                'formAction'      => 'login',
                'mensaje'         => reset($errors),
                'csrfToken'       => Security::getCsrfToken(),
                'rememberEnabled' => $rememberEnabled,
            ]);
            return;
        }

        $result = $this->authUseCase->handleLogin($request);

        if ($result->success) {
            $this->redirect('');
            return;
        }

        View::render('auth/login.php', [
            'public_base'     => $this->publicBaseUrl,
            'formAction'      => 'login',
            'mensaje'         => $result->message ?? '',
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
