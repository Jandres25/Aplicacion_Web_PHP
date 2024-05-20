<?php

include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT Foto, CV FROM `tbl-empleados` WHERE ID=:ID");
    $sentencia->bindParam(':ID', $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

    if (isset($registro_recuperado["Foto"]) && $registro_recuperado["Foto"] != "") {
        if (file_exists("./" . $registro_recuperado["Foto"])) {
            unlink("./" . $registro_recuperado["Foto"]);
        }
    }

    if (isset($registro_recuperado["CV"]) && $registro_recuperado["CV"] != "") {
        if (file_exists("./" . $registro_recuperado["CV"])) {
            unlink("./" . $registro_recuperado["CV"]);
        }
    }

    $sentencia = $conexion->prepare("DELETE FROM `tbl-empleados` WHERE ID=:ID");
    $sentencia->bindParam(":ID", $txtID);
    $sentencia->execute();
    $mensaje = "Registro Eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
    header("Location:index.php");
}

$sentencia = $conexion->prepare("SELECT *, (SELECT Nombredelpuesto FROM `tbl-puestos` WHERE ID=Idpuesto limit 1) as puesto FROM `tbl-empleados`");
$sentencia->execute();
$lista_tbl_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php"); ?>
<section class="mt-4 mb-4">
    <h2>Lista Empleados</h2>

    <div class="card text-bg-light" style="margin-bottom: 3%;">
        <div class="card-header">
            <a name="" id="" class="btn btn-outline-primary" href="crear.php" role="button">Agregar Registro</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-hover" id="tabla_id">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Foto</th>
                            <th scope="col">CV</th>
                            <th scope="col">Puesto</th>
                            <th scope="col">Fecha Ingreso</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_tbl_empleados as $registro) { ?>
                            <tr>
                                <td><?php echo $registro["ID"]; ?></td>
                                <td style="width: 20%;">
                                    <?php echo $registro["Primernombre"]; ?> <?php echo $registro["Segundonombre"]; ?>
                                    <?php echo $registro["Primerapellido"]; ?> <?php echo $registro["Segundoapellido"]; ?>
                                </td>
                                <td>
                                    <img width="50" src="<?php echo $registro["Foto"]; ?>" class="img-fluid rounded" alt="">
                                </td>
                                <td>
                                    <a href="<?php echo $registro["CV"]; ?>"><?php echo $registro["CV"]; ?></a>
                                </td>
                                <td><?php echo $registro["puesto"]; ?></td>
                                <td><?php echo $registro["Fecha"]; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $registro['ID']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?php echo $registro['ID']; ?>">
                                            <li>
                                                <a class="dropdown-item" href="carta_recomendacion.php?txtID=<?php echo $registro['ID']; ?>">
                                                    <i class="fas fa-file-alt"></i> Carta
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['ID']; ?>">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:borrar(<?php echo $registro['ID']; ?>);">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php include("../../templates/footer.php"); ?>