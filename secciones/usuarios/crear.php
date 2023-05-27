<?php 

  include("../../bd.php");

  if ($_POST) {

    $nombredelusuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
    $passworddelusuario = (isset($_POST["password"]) ? $_POST["password"] : "");
    $correodelusuario = (isset($_POST["correo"]) ? $_POST["correo"] : "");

    $sentencia = $conexion -> prepare("INSERT INTO `tbl-usuarios`(ID,Nombreusuario,Password,Correo) VALUES(null, :Nombreusuario,:Password,:Correo)");

    $sentencia -> bindParam(":Nombreusuario",$nombredelusuario);
    $sentencia -> bindParam(":Password",$passworddelusuario);
    $sentencia -> bindParam(":Correo",$correodelusuario);
    $sentencia -> execute();
    $mensaje = "Registro Agregado";
    header("Location:index.php?mensaje=".$mensaje);
  }

?>

<?php include("../../templates/header.php") ?>
    
  </br>
  <div class="card">
    <div class="card-header">
      Datos del usuario
    </div>
    <div class="card-body">
      <form action="" method="post" enctype="multipart/form-data">
        <label label for="usuario" class="form-label">Nombre del usuario:</label>
        <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1">@</span>
          <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ejemplo: Juan10">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password:</label>
          <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ejemplo: password">
          <small id="helpId" class="form-text text-muted">Ingrese su contraseña</small>
        </div>
        <label for="correo" class="form-label">Correo:</label>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ejemplo: correo@dominio.com">
          <span class="input-group-text" id="basic-addon2">@domino.com</span>
        </div>
        <button type="submit" class="btn btn-outline-success">Agregar</button>
        <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
      </form>
    </div>
    <div class="card-footer text-muted"></div>
  </div>

<?php include("../../templates/footer.php") ?>