<?php

namespace App\Http\Controllers;

use App\UseCases\AuthUseCase;
use Core\Security;
use Core\View;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        Security::startSession();

        if (isset($_SESSION['logueado'])) {
            $this->redirect('');
        }

        View::render('auth/login.php', [
            'public_base' => $this->publicBaseUrl,
            'formAction'  => 'login',
            'mensaje'     => '',
            'csrfToken'   => Security::getCsrfToken(),
        ]);
    }

    public function login(): void
    {
        if (!$this->hasValidCsrfToken($_POST)) {
            View::render('auth/login.php', [
                'public_base' => $this->publicBaseUrl,
                'formAction'  => 'login',
                'mensaje'     => 'Solicitud inválida, recargue la página e intente nuevamente.',
                'csrfToken'   => Security::getCsrfToken(),
            ]);
            return;
        }

        $authUseCase = AuthUseCase::fromEnvironment();
        $result = $authUseCase->handleLogin($_POST);

        if ($result['success']) {
            $this->redirect('');
            return;
        }

        View::render('auth/login.php', [
            'public_base' => $this->publicBaseUrl,
            'formAction'  => 'login',
            'mensaje'     => (string)$result['mensaje'],
            'csrfToken'   => Security::getCsrfToken(),
        ]);
    }

    public function logout(): void
    {
        if (!$this->hasValidCsrfToken($_POST)) {
            \Core\Flash::set('Solicitud inválida, recargue la página e intente nuevamente.', 'error');
            $this->redirect('');
        }

        Security::startSession();
        session_unset();
        session_destroy();
        $this->redirect('login');
    }
}
