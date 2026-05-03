<?php

namespace App\Middleware;

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
