<?php

require_once __DIR__ . '/../core/Env.php';
require_once __DIR__ . '/../core/Router.php';

Env::load(__DIR__ . '/../.env');

$router = new Router(__DIR__ . '/..');
$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';

$router->dispatch($requestUri, $scriptName);
