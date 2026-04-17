<?php
session_start();
session_destroy();
require_once __DIR__ . '/core/Env.php';

use Core\Env;

Env::load(__DIR__ . '/.env');

$url_base = Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/');
$url_base = rtrim($url_base, '/') . '/';

header('Location:' . $url_base . 'login.php');
