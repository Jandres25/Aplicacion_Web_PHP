<?php
include("../../bd.php");

if ($_POST) {

    $nombredelpuesto = (isset($_POST["nombredelpuesto"]) ? $_POST["nombredelpuesto"] : "");

    $sentencia = $conexion->prepare("INSERT INTO `tbl-puestos`(ID,Nombredelpuesto) VALUES(null, :Nombredelpuesto)");

    $sentencia->bindParam(":Nombredelpuesto", $nombredelpuesto);
    $sentencia->execute();
    $mensaje = "Registro Agregado";
    header("Location:index.php?mensaje=" . $mensaje);
}
?>

<?php include("../../templates/header.php") ?>
<section class="mt-5">
    <div class="card">
        <div class="card-header">
            Creación del puesto
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombredelpuesto" class="form-label">Puesto</label>
                    <input type="text" class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Ejemplo: Programador Jr.">
                    <small id="helpId" class="form-text text-muted">Ingrese el nombre del puesto</small>
                </div>
                <button type="submit" class="btn btn-outline-success">Agregar</button>
                <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
<?php include("../../templates/footer.php") ?>