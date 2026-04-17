<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function requireLogin($loginUrl)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logueado'])) {
            header('Location:' . $loginUrl);
            exit();
        }
    }

    public function currentUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
    }
}
