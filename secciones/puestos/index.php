<?php 
    include("../../bd.php");

    if (isset($_GET['txtID'])) {
        $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

        $sentencia = $conexion -> prepare("DELETE FROM `tbl-puestos` WHERE ID=:ID");
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();
        $mensaje = "Registro Eliminado";
        header("Location:index.php?mensaje=".$mensaje);
    }
    $sentencia = $conexion -> prepare("SELECT * FROM `tbl-puestos`");
    $sentencia -> execute();
    $lista_tbl_puestos = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>
    <h2>Lista de puestos</h2>

    <div class="card text-bg-light">
        <div class="card-header">
            <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover" id="tabla_id">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre del puesto</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_tbl_puestos as $registro) { ?>
                            <tr>
                                <td><?php echo $registro['ID']; ?></td>
                                <td><?php echo $registro['Nombredelpuesto']; ?></td>
                                <td>
                                    <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="editar.php?txtID=<?php echo $registro['ID']; ?>" role="button">Editar</a>
                                    <a name="" class="btn btn-outline-danger" href="javascript:borrar(<?php echo $registro['ID'];?>);" role="button">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include("../../templates/footer.php"); ?>