<?php

namespace App\Middleware;

require_once __DIR__ . '/../../core/Security.php';

use Core\Security;

class AuthMiddleware
{
    public function requireLogin($loginUrl)
    {
        Security::startSession();

        if (!isset($_SESSION['logueado'])) {
            header('Location:' . $loginUrl);
            exit();
        }
    }

    public function currentUser()
    {
        Security::startSession();

        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
    }
}
