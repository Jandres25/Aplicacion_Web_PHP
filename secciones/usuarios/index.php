<?php

    include("../../bd.php");

    if (isset($_GET['txtID'])) {
        $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

        $sentencia = $conexion -> prepare("DELETE FROM `tbl-usuarios` WHERE ID=:ID");
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();
        $mensaje = "Registro Eliminado";
        header("Location:index.php?mensaje=".$mensaje);
    }
    $sentencia = $conexion -> prepare("SELECT * FROM `tbl-usuarios`");
    $sentencia -> execute();
    $lista_tbl_usuarios = $sentencia -> fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php"); ?>
    <h2>Lista Usuarios</h2>

    <div class="card">
        <div class="card-header">
            <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover" id="tabla_id">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre del usuario</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_tbl_usuarios as $registro) { ?>
                            <tr>
                                <td><?php echo $registro['ID']; ?></td>
                                <td><?php echo $registro['Nombreusuario']; ?></td>
                                <td><?php echo str_repeat("*", strlen($registro['Password'])); ?></td>
                                <td><?php echo $registro['Correo']; ?></td>
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