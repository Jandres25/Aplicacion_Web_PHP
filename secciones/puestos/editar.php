<?php

include("../../bd.php");

if (isset($_GET["txtID"])) {
    $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

    $sentencia = $conexion->prepare("SELECT * FROM `tbl-puestos` WHERE ID=:ID");
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    $nombredelpuesto = $registro["Nombredelpuesto"];
}

if ($_POST) {

    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";
    $nombredelpuesto = (isset($_POST["nombredelpuesto"]) ? $_POST["nombredelpuesto"] : "");

    $sentencia = $conexion->prepare("UPDATE `tbl-puestos` SET Nombredelpuesto = :nombredelpuesto WHERE ID=:ID");

    $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();
    $mensaje = "Registro Actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
}
?>

<?php include("../../templates/header.php") ?>
<section class="mt-5">
    <div class="card">
        <div class="card-header">
            Datos del puesto
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="txtID" class="form-label">ID:</label>
                    <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
                </div>
                <div class="mb-3">
                    <label for="nombredelpuesto" class="form-label">Puesto:</label>
                    <input type="text" value="<?php echo $nombredelpuesto; ?>" class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Puesto">
                </div>
                <button type="submit" class="btn btn-outline-success">Actualizar</button>
                <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
<?php include("../../templates/footer.php") ?>