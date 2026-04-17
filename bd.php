<?php

require_once __DIR__ . '/core/Env.php';
require_once __DIR__ . '/config/database.php';

Env::load(__DIR__ . '/.env');

try {
    $conexion = Database::getConnection();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
