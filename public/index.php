<?php

require_once __DIR__ . '/../core/Env.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../core/Router.php';

use Core\Env;
use Core\Router;
use Core\Security;

Env::load(__DIR__ . '/../.env');
Security::sendSecurityHeaders();

$router = new Router(__DIR__ . '/..');
$routesRegistrar = require __DIR__ . '/../routes/web.php';
if (is_callable($routesRegistrar)) {
    $routesRegistrar($router, __DIR__ . '/..');
}

$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';
$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

$router->dispatch($requestUri, $scriptName, $requestMethod);
