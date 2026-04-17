<?php
require_once __DIR__ . '/../../../core/Env.php';
require_once __DIR__ . '/../../../core/Flash.php';
require_once __DIR__ . '/../../../app/Middleware/AuthMiddleware.php';

use App\Middleware\AuthMiddleware;
use Core\Env;
use Core\Flash;

Env::load(__DIR__ . '/../.env');

$url_base = Env::get('APP_URL', 'http://localhost/Aplicacion_Web_PHP/');
$url_base = rtrim($url_base, '/') . '/';
$public_base = $url_base . 'public/';

$authMiddleware = new AuthMiddleware();
$authMiddleware->requireLogin($public_base . 'login');
$nombreUsuario = $authMiddleware->currentUser();

if (isset($_GET['mensaje'])) {
  Flash::set($_GET['mensaje'], 'success');
  $queryParams = $_GET;
  unset($queryParams['mensaje']);

  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $path = is_string($path) ? $path : '';
  $redirectUrl = $path;
  if (!empty($queryParams)) {
    $redirectUrl .= '?' . http_build_query($queryParams);
  }

  header('Location:' . $redirectUrl);
  exit();
}

$flash = Flash::consume();
?>

<!doctype html>
<html lang="es">

<head>
  <title>Aplicación Web</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" type="image/x-icon" href="<?= $public_base; ?>img/deadpool.ico">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

  <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex-grow: 1;
    }
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand navbar-dark bg-dark">
      <div class="container-fluid" style="display: flex; justify-content: space-between;">
        <ul class="nav navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" href="<?= $public_base; ?>" aria-current="page">Sistema Web</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $public_base; ?>empleados">Empleados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $public_base; ?>puestos">Puestos</a>
          </li>
          <?php if ($nombreUsuario == "Administrador") : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $public_base; ?>usuarios">Usuarios</a>
            </li>
          <?php endif; ?>
        </ul>
        <ul class="nav navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?= $public_base; ?>cerrar">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main class="container">
    <?php if ($flash !== null) : ?>
      <script>
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: <?= json_encode($flash['icon']); ?>,
          title: <?= json_encode($flash['message']); ?>
        });
      </script>
    <?php endif; ?>
