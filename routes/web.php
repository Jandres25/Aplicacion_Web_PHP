<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\UsersController;
use Core\Env;
use Core\Router;

return static function (Router $router, $projectRoot): void {
    $publicBaseUrl = rtrim((string)Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/'), '/') . '/public/';

    $home      = new HomeController($projectRoot, $publicBaseUrl);
    $auth      = new AuthController($projectRoot, $publicBaseUrl);
    $employees = new EmployeesController($projectRoot, $publicBaseUrl);
    $positions = new PositionsController($projectRoot, $publicBaseUrl);
    $users     = new UsersController($projectRoot, $publicBaseUrl);

    $router->get('/',      [$home, 'index']);
    $router->get('/index', [$home, 'alias']);
    $router->get('/home',  [$home, 'alias']);

    $router->get('/login',   [$auth, 'showLogin']);
    $router->post('/login',  [$auth, 'login']);
    $router->post('/cerrar', [$auth, 'logout']);

    $router->get('/empleados',                    [$employees, 'index']);
    $router->get('/empleados-crear',              [$employees, 'createForm']);
    $router->post('/empleados-crear',             [$employees, 'create']);
    $router->get('/empleados-editar',             [$employees, 'editForm']);
    $router->post('/empleados-editar',            [$employees, 'edit']);
    $router->post('/empleados-eliminar',          [$employees, 'delete']);
    $router->get('/empleados-carta-recomendacion', [$employees, 'recommendation']);

    $router->get('/puestos',         [$positions, 'index']);
    $router->get('/puestos-crear',   [$positions, 'createForm']);
    $router->post('/puestos-crear',  [$positions, 'create']);
    $router->get('/puestos-editar',  [$positions, 'editForm']);
    $router->post('/puestos-editar', [$positions, 'edit']);
    $router->post('/puestos-eliminar', [$positions, 'delete']);

    $router->get('/usuarios',         [$users, 'index']);
    $router->get('/usuarios-crear',   [$users, 'createForm']);
    $router->post('/usuarios-crear',  [$users, 'create']);
    $router->get('/usuarios-editar',  [$users, 'editForm']);
    $router->post('/usuarios-editar', [$users, 'edit']);
    $router->post('/usuarios-eliminar', [$users, 'delete']);
};
