<?php

    include("../../bd.php");

    if (isset($_GET["txtID"])) {
        $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

        $sentencia = $conexion -> prepare("SELECT * FROM `tbl-usuarios` WHERE ID=:ID");
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();

        $registro = $sentencia -> fetch(PDO::FETCH_LAZY);
        $usuario = $registro["Nombreusuario"];
        $password = $registro["Password"];
        $correo = $registro["Correo"];
    }

    if($_POST){

        var_dump($_POST);
        $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";
        $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
        $password = (isset($_POST["password"]) ? $_POST["password"] : "");
        $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");

        $sentencia = $conexion -> prepare("UPDATE `tbl-usuarios` SET Nombreusuario = :usuario, Password = :password, Correo = :correo WHERE ID = :ID");

        $sentencia -> bindParam(":usuario",$usuario);
        $sentencia -> bindParam(":password",$password);
        $sentencia -> bindParam(":correo",$correo);
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();
        $mensaje = "Registro Actualizado";
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
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?php echo $txtID;?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId">
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre del usuario:</label>
                <input type="text" value="<?php echo $usuario;?>" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ejemplo: Juan10">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" value="<?php echo $password;?>" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ejemplo: password">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" value="<?php echo $correo;?>" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ejemplo: correo@dominio.com">
            </div>
            <button type="submit" class="btn btn-outline-success">Actualizar</button>
            <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
        </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
<?php include("../../templates/footer.php") ?>