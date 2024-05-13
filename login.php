<?php
session_start();
if ($_POST) {
  include("./bd.php");

  $sentencia = $conexion->prepare("SELECT *, count(*) as n_usuarios FROM `tbl-usuarios` WHERE Nombreusuario = :Nombreusuario AND Password = :Password");
  $usuario = $_POST["usuario"];
  $password = $_POST["password"];

  $sentencia->bindParam(":Nombreusuario", $usuario);
  $sentencia->bindParam(":Password", $password);
  $sentencia->execute();
  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

  if ($registro["n_usuarios"] > 0) {
    $_SESSION['usuario'] = $registro["Nombreusuario"];
    $_SESSION['logueado'] = true;
    header("Location:index.php");
  } else {
    $mensaje = "Rellene los campos vacios";
  }
}
?>

<!doctype html>
<html lang="es">

<head>
  <title>Login</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="estilos/style.css">
  <link rel="icon" type="image/x-icon" href="deadpool.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
  <main class="container">
    <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4 login-form">
        <div style="margin-top: 50%" class="card mb-3" id="tarjeta">
          <h1 style="text-align: center;">LOGIN</h1>
          <div class="card-body">
            <?php if (isset($mensaje)) { ?>
              <div class="alert alert-danger" role="alert">
                <strong><?php echo $mensaje; ?></strong>
              </div>
            <?php } ?>
            <form action="" method="post">
              <label for="usuario" class="form-label">Usuario</label>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <i class="bi bi-person-circle"></i>
                </span>
                <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Escriba su usuario">
              </div>
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Escriba su contraseña">
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-outline-primary ">Iniciar Sesión</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="square"></div>
    <div class="square"></div>
    <div class="square"></div>
    <div class="square"></div>
    <div class="square"></div>

    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>
    <div class="circle"></div>

    <div class="triangle"></div>
    <div class="triangle"></div>
    <div class="triangle"></div>
    <div class="triangle"></div>
    <div class="triangle"></div>
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
  <script src="estilos/main.js"></script>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>