<?php
session_start();
$url_base = "http://localhost/app/";

// Verificar si el usuario ha iniciado sesión
$nombreUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
if (!isset($_SESSION['logueado'])) {
  header("Location:" . $url_base . "login.php");
}
?>

<!doctype html>
<html lang="es">

<head>
  <title>Aplicación Web</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" type="image/x-icon" href="http://localhost/app/deadpool.ico">
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
            <a class="nav-link active" href="<?php echo $url_base; ?>" aria-current="page">Sistema Web</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>secciones/empleados/">Empleados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>secciones/puestos/">Puestos</a>
          </li>
          <?php if ($nombreUsuario == "Administrador") { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $url_base; ?>secciones/usuarios/">Usuarios</a>
            </li>
          <?php } ?>
        </ul>
        <ul class="nav navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main class="container">
    <?php if (isset($_GET['mensaje'])) { ?>
      <script>
        Swal.fire({
          icon: "success",
          title: "<?php echo $_GET['mensaje']; ?>"
        });
      </script>
    <?php } ?>