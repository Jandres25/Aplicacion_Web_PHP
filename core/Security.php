<?php

namespace Core;

class Security
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', $isHttps ? '1' : '0');
        ini_set('session.cookie_samesite', 'Lax');

        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => $isHttps,
                'samesite' => 'Lax',
            ]);
        }

        session_start();
    }

    public static function getCsrfToken(): string
    {
        self::startSession();

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function isValidCsrfToken($token): bool
    {
        self::startSession();

        if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            return false;
        }

        if (!is_string($token) || $token === '') {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function sendSecurityHeaders(): void
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }
}
