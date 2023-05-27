<?php 
  session_start();
  if ($_POST) {
    include("./bd.php");

    $sentencia = $conexion -> prepare("SELECT *, count(*) as n_usuarios FROM `tbl-usuarios` WHERE Nombreusuario = :Nombreusuario AND Password = :Password");
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    $sentencia -> bindParam(":Nombreusuario",$usuario);
    $sentencia -> bindParam(":Password",$password);
    $sentencia -> execute();
    $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

    if ($registro["n_usuarios"] > 0) {
      $_SESSION['usuario'] = $registro["Nombreusuario"];
      $_SESSION['logueado'] = true;
      header("Location:index.php");
    } else {
      $mensaje = "Error: El usuario o contraseña son incorrectos";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <style>
    body {
      background-image: url("https://images.unsplash.com/photo-1579546929518-9e396f3cc809?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80");
      background-repeat: no-repeat;
      background-size: cover;
    }
    #tarjeta {
      -webkit-borde-radius: 10px;
      -webkit-box-shadow: 0 0 20px rgba(0,0,0,0.5);
      -moz-border-shadow: 10px;
      -moz-box-shadow: 0 0 20px  rgba(0,0,0,0.5);
      -o-border-radius: 10px;
      -o-border-shadow: 0 0 20px rgba(0,0,0,0.5);
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
      font-size: 20px;
      margin: 0 auto;
      padding: 5%;
      width: 90%;
    }
  </style>
</head>

<body>
  <header>
    <!-- place navbar here -->
  </header>
  <main class="container">
    <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        <div style="margin-top: 50%" class="card mb-3" id="tarjeta">
          <h4 style="text-align: center;">LOGIN</h4>
          <div class="card-body">
            <?php if(isset($mensaje)) { ?>
              <div class="alert alert-danger" role="alert">
                <strong><?php echo $mensaje; ?></strong> 
              </div>
            <?php } ?>
            <form action="" method="post">
              <label for="usuario" class="form-label">Usuario</label>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                  </svg>
                </span>
                <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Escriba su usuario">
              </div>
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                  <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                </svg>
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
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>