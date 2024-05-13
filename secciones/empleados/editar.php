<?php

include("../../bd.php");

if (isset($_GET["txtID"])) {
    $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

    $sentencia = $conexion->prepare("SELECT * FROM `tbl-empleados` WHERE ID=:ID");
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $primernombre = $registro["Primernombre"];
    $segundonombre = $registro["Segundonombre"];
    $primerapellido = $registro["Primerapellido"];
    $segundoapellido = $registro["Segundoapellido"];

    $foto = $registro["Foto"];
    $cv = $registro["CV"];

    $idpuesto = $registro["Idpuesto"];
    $fechadeingreso = $registro["Fecha"];

    $sentencia = $conexion->prepare("SELECT * FROM `tbl-puestos`");
    $sentencia->execute();
    $lista_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
}

if ($_POST) {
    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";
    $primernombre = (isset($_POST["primernombre"]) ? $_POST["primernombre"] : "");
    $segundonombre = (isset($_POST["segundonombre"]) ? $_POST["segundonombre"] : "");
    $primerapellido = (isset($_POST["primerapellido"]) ? $_POST["primerapellido"] : "");
    $segundoapellido = (isset($_POST["segundoapellido"]) ? $_POST["segundoapellido"] : "");

    $idpuesto = (isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "");
    $fechadeingreso = (isset($_POST["fechadeingreso"]) ? $_POST["fechadeingreso"] : "");

    $sentencia = $conexion->prepare("UPDATE `tbl-empleados` SET Primernombre=:primernombre, Segundonombre=:segundonombre, Primerapellido=:primerapellido, Segundoapellido=:segundoapellido,
        Idpuesto=:idpuesto, Fecha=:fechadeingreso WHERE ID=:ID");

    $sentencia->bindParam(":primernombre", $primernombre);
    $sentencia->bindParam(":segundonombre", $segundonombre);
    $sentencia->bindParam(":primerapellido", $primerapellido);
    $sentencia->bindParam(":segundoapellido", $segundoapellido);
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechadeingreso", $fechadeingreso);
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();

    $foto = (isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "");

    $fecha = new DateTime();

    $nombreArchivo_foto = ($foto != "") ? $fecha->getTimestamp() . "_" . $_FILES["foto"]["name"] : "";
    $temp_foto = $_FILES["foto"]["tmp_name"];

    if ($temp_foto != "") {
        move_uploaded_file($temp_foto, "./" . $nombreArchivo_foto);

        $sentencia = $conexion->prepare("SELECT Foto FROM `tbl-empleados` WHERE ID=:ID");
        $sentencia->bindParam(":ID", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registro_recuperado["Foto"]) && $registro_recuperado["Foto"] != "") {
            if (file_exists("./" . $registro_recuperado["Foto"])) {
                unlink("./" . $registro_recuperado["Foto"]);
            }
        }

        $sentencia = $conexion->prepare("UPDATE `tbl-empleados` SET Foto = :foto WHERE ID=:ID");
        $sentencia->bindParam(":foto", $nombreArchivo_foto);
        $sentencia->bindParam(":ID", $txtID);
        $sentencia->execute();
    }

    $cv = (isset($_FILES["CV"]["name"]) ? $_FILES["CV"]["name"] : "");

    $nombreArchivo_cv = ($cv != "") ? $fecha->getTimestamp() . "_" . $_FILES["CV"]["name"] : "";
    $temp_cv = $_FILES["CV"]["tmp_name"];

    if ($temp_cv != "") {
        move_uploaded_file($temp_cv, "./" . $nombreArchivo_cv);

        $sentencia = $conexion->prepare("SELECT CV FROM `tbl-empleados` WHERE ID=:ID");
        $sentencia->bindParam(":ID", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registro_recuperado["CV"]) && $registro_recuperado["CV"] != "") {
            if (file_exists("./" . $registro_recuperado["CV"])) {
                unlink("./" . $registro_recuperado["CV"]);
            }
        }

        $sentencia = $conexion->prepare("UPDATE `tbl-empleados` SET CV = :CV WHERE ID=:ID");
        $sentencia->bindParam(":CV", $nombreArchivo_cv);
        $sentencia->bindParam(":ID", $txtID);
        $sentencia->execute();
    }
    $mensaje = "Registro Actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
}

?>

<?php include("../../templates/header.php") ?>
<div class="card mt-4 mb-4">
    <div class="card-header">
        Datos del empleado
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" value="<?php echo $primernombre; ?>" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId" placeholder="Ejemplo: Juan">
            </div>
            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" value="<?php echo $segundonombre; ?>" class="form-control" name="segundonombre" id="segundonombre" aria-describedby="helpId" placeholder="Ejemplo: Juanito">
            </div>
            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" value="<?php echo $primerapellido; ?>" class="form-control" name="primerapellido" id="primerapellido" aria-describedby="helpId" placeholder="Ejemplo: Gonzales">
            </div>
            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" value="<?php echo $segundoapellido; ?>" class="form-control" name="segundoapellido" id="segundoapellido" aria-describedby="helpId" placeholder="Ejemplo: Gutierrez">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <br>
                <img width="70" src="<?php echo $foto; ?>" class="rounded" alt="">
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
            </div>
            <div class="mb-3">
                <label for="CV" class="form-label">CV(PDF):</label>
                <br>
                <a href="<?php echo $cv; ?>"><?php echo $cv; ?></a>
                <input type="file" class="form-control" name="CV" id="CV" aria-describedby="helpId" placeholder="CV">
            </div>
            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto:</label>
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach ($lista_tbl_puestos as $registro) { ?>
                        <option <?php echo ($idpuesto == $registro["ID"]) ? "selected" : ""; ?> value="<?php echo $registro["ID"]; ?>"><?php echo $registro["Nombredelpuesto"]; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
                <input type="date" value="<?php echo $fechadeingreso; ?>" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="helpId" placeholder="CV">
            </div>
            <button type="submit" class="btn btn-outline-success">Actualizar</button>
            <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</div>
<?php include("../../templates/footer.php") ?>