<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\ErrorPage;
use Core\Env;
use Core\Router;
use Core\Security;

Env::load(__DIR__ . '/../.env');
Security::sendSecurityHeaders();

$projectRoot = __DIR__ . '/..';
$publicBaseUrl = rtrim((string)Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/'), '/') . '/public/';

register_shutdown_function(static function () use ($projectRoot, $publicBaseUrl): void {
    $lastError = error_get_last();
    if ($lastError === null) {
        return;
    }

    $fatalErrorTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if (!in_array((int)$lastError['type'], $fatalErrorTypes, true)) {
        return;
    }

    error_log('Fatal error: ' . (string)$lastError['message'] . ' in ' . (string)$lastError['file'] . ':' . (string)$lastError['line']);
    if (!headers_sent()) {
        ErrorPage::render($projectRoot, $publicBaseUrl, 500);
    }
});

try {
    $router = new Router($projectRoot, 'public', $publicBaseUrl);
    $routesRegistrar = require __DIR__ . '/../routes/web.php';
    if (is_callable($routesRegistrar)) {
        $routesRegistrar($router, $projectRoot);
    }

    $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';
    $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

    $router->dispatch($requestUri, $scriptName, $requestMethod);
} catch (\Throwable $exception) {
    error_log('Unhandled exception: ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());
    ErrorPage::render($projectRoot, $publicBaseUrl, 500);
}
