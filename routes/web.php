<?php

use App\Controllers\WebController;
use Core\Env;
use Core\Router;

require_once __DIR__ . '/../app/Controllers/WebController.php';

return static function (Router $router, $projectRoot): void {
    $publicBaseUrl = rtrim((string)Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/'), '/') . '/public/';
    $controller = new WebController($projectRoot, $publicBaseUrl);

    $router->get('/', [$controller, 'home']);
    $router->get('/index', [$controller, 'homeAlias']);
    $router->get('/home', [$controller, 'homeAlias']);

    $router->get('/login', [$controller, 'showLogin']);
    $router->post('/login', [$controller, 'login']);
    $router->post('/cerrar', [$controller, 'logout']);

    $router->get('/empleados', [$controller, 'employeesIndex']);
    $router->get('/empleados-crear', [$controller, 'employeesCreateForm']);
    $router->post('/empleados-crear', [$controller, 'employeesCreate']);
    $router->get('/empleados-editar', [$controller, 'employeesEditForm']);
    $router->post('/empleados-editar', [$controller, 'employeesEdit']);
    $router->post('/empleados-eliminar', [$controller, 'employeesDelete']);
    $router->get('/empleados-carta-recomendacion', [$controller, 'employeeRecommendation']);

    $router->get('/puestos', [$controller, 'positionsIndex']);
    $router->get('/puestos-crear', [$controller, 'positionsCreateForm']);
    $router->post('/puestos-crear', [$controller, 'positionsCreate']);
    $router->get('/puestos-editar', [$controller, 'positionsEditForm']);
    $router->post('/puestos-editar', [$controller, 'positionsEdit']);
    $router->post('/puestos-eliminar', [$controller, 'positionsDelete']);

    $router->get('/usuarios', [$controller, 'usersIndex']);
    $router->get('/usuarios-crear', [$controller, 'usersCreateForm']);
    $router->post('/usuarios-crear', [$controller, 'usersCreate']);
    $router->get('/usuarios-editar', [$controller, 'usersEditForm']);
    $router->post('/usuarios-editar', [$controller, 'usersEdit']);
    $router->post('/usuarios-eliminar', [$controller, 'usersDelete']);
};
