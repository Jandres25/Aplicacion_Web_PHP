<?php

namespace Core;

class Flash
{
    public static function set($message, $icon = 'success')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['message'] = (string)$message;
        $_SESSION['icon'] = (string)$icon;
    }

    public static function consume()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['message']) || !isset($_SESSION['icon'])) {
            return null;
        }

        $payload = [
            'message' => (string)$_SESSION['message'],
            'icon' => (string)$_SESSION['icon']
        ];

        unset($_SESSION['message'], $_SESSION['icon']);

        return $payload;
    }
}
