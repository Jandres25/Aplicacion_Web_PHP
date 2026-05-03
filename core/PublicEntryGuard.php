<?php

namespace Core;

class PublicEntryGuard
{
    public static function redirectToPublicIfNeeded($currentFile)
    {
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string)$_SERVER['SCRIPT_NAME']) : '';
        if (self::endsWith($scriptName, '/public/index.php') || $scriptName === 'public/index.php') {
            return;
        }

        Env::load(__DIR__ . '/../.env');
        $baseUrl = Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/');
        $baseUrl = rtrim((string)$baseUrl, '/') . '/';

        $route = self::routeFromFile($currentFile);
        $destination = $baseUrl . 'public/' . ltrim($route, '/');

        $query = isset($_SERVER['QUERY_STRING']) ? trim((string)$_SERVER['QUERY_STRING']) : '';
        if ($query !== '') {
            $destination .= '?' . $query;
        }

        header('Location: ' . $destination);
        exit();
    }

    private static function routeFromFile($filePath)
    {
        $projectRoot = realpath(__DIR__ . '/..');
        $resolved = realpath((string)$filePath);
        if ($projectRoot === false || $resolved === false) {
            return 'index';
        }

        $normalizedRoot = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $normalizedFile = str_replace('\\', '/', $resolved);

        $relative = ltrim(substr($normalizedFile, strlen($normalizedRoot)), '/');
        if ($relative === '' || strpos($relative, '..') !== false) {
            return 'index';
        }

        if (self::endsWith($relative, '/index.php')) {
            $relative = substr($relative, 0, -10);
        } elseif (self::endsWith($relative, '.php')) {
            $relative = substr($relative, 0, -4);
        }

        return $relative === '' ? 'index' : $relative;
    }

    private static function endsWith($value, $suffix)
    {
        $value = (string)$value;
        $suffix = (string)$suffix;
        if ($suffix === '') {
            return true;
        }
        return substr($value, -strlen($suffix)) === $suffix;
    }
}
