<?php

  include("../../bd.php");

  if ($_POST) {

    $primernombre = (isset($_POST["primernombre"]) ? $_POST["primernombre"] : "");
    $segundonombre = (isset($_POST["segundonombre"]) ? $_POST["segundonombre"] : "");
    $primerapellido = (isset($_POST["primerapellido"]) ? $_POST["primerapellido"] : "");
    $segundoapellido = (isset($_POST["segundoapellido"]) ? $_POST["segundoapellido"] : "");

    $foto = (isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "");
    $cv = (isset($_FILES["CV"]["name"]) ? $_FILES["CV"]["name"] : "");

    $idpuesto = (isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "");
    $fechadeingreso = (isset($_POST["fechadeingreso"]) ? $_POST["fechadeingreso"] : "");

    $sentencia = $conexion -> prepare("INSERT INTO `tbl-empleados`(ID, Primernombre, Segundonombre, Primerapellido, Segundoapellido, Foto, CV, Idpuesto, Fecha) 
    VALUES(null, :Primernombre, :Segundonombre, :Primerapellido, :Segundoapellido, :Foto, :CV, :Idpuesto, :Fecha)");

    $sentencia -> bindParam(":Primernombre", $primernombre);
    $sentencia -> bindParam(":Segundonombre", $segundonombre);
    $sentencia -> bindParam(":Primerapellido", $primerapellido);
    $sentencia -> bindParam(":Segundoapellido", $segundoapellido);

    $fecha = new DateTime();
    $nombreArchivo_foto = ($foto != "") ? $fecha -> getTimestamp()."_".$_FILES["foto"]["name"] : "";
    $temp_foto = $_FILES["foto"]["tmp_name"];

    if ($temp_foto != "") {
      move_uploaded_file($temp_foto,"./".$nombreArchivo_foto);
    }

    $sentencia -> bindParam(":Foto", $nombreArchivo_foto);

    $nombreArchivo_cv = ($cv != "") ? $fecha -> getTimestamp()."_".$_FILES["CV"]["name"] : "";
    $temp_cv = $_FILES["CV"]["tmp_name"];

    if ($temp_cv != "") {
      move_uploaded_file($temp_cv,"./".$nombreArchivo_cv);
    }

    $sentencia -> bindParam(":CV", $nombreArchivo_cv);
    $sentencia -> bindParam(":Idpuesto", $idpuesto);
    $sentencia -> bindParam(":Fecha", $fechadeingreso);
    $sentencia -> execute();
    $mensaje = "Registro Agregado";
    header("Location:index.php?mensaje=".$mensaje);

  }

  $sentencia = $conexion -> prepare("SELECT * FROM `tbl-puestos`");
  $sentencia -> execute();
  $lista_tbl_puestos = $sentencia -> fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../templates/header.php") ?>
  <div class="card" style="margin-top: 3%; margin-bottom: 3%;">
    <div class="card-header">
      Empleados
    </div>
    <div class="card-body">
      <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="primernombre" class="form-label">Primer Nombre</label>
          <input type="text" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId" placeholder="Ejemplo: Juan">
          <small id="helpId" class="form-text text-muted">Ingrese el primer nombre del empleado</small>
        </div>
        <div class="mb-3">
          <label for="segundonombre" class="form-label">Segundo Nombre</label>
          <input type="text" class="form-control" name="segundonombre" id="segundonombre" aria-describedby="helpId" placeholder="Ejemplo: Juanito">
          <small id="helpId" class="form-text text-muted">Ingrese el segundo nombre del empleado</small>
        </div>
        <div class="mb-3">
          <label for="primerapellido" class="form-label">Primer Apellido</label>
          <input type="text" class="form-control" name="primerapellido" id="primerapellido" aria-describedby="helpId" placeholder="Ejemplo: Gonzales">
          <small id="helpId" class="form-text text-muted">Ingrese el primer apellido del empleado</small>
        </div>
        <div class="mb-3">
          <label for="segundoapellido" class="form-label">Segundo Apellido</label>
          <input type="text" class="form-control" name="segundoapellido" id="segundoapellido" aria-describedby="helpId" placeholder="Ejemplo: Gutierrez">
          <small id="helpId" class="form-text text-muted">Ingrese el segundo apellido del empleado</small>
        </div>
        <div class="mb-3">
          <label for="foto" class="form-label">Foto:</label>
          <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
          <small id="helpId" class="form-text text-muted">Ingrese  el nombre del archivo tipo imagen</small>
        </div>
        <div class="mb-3">
          <label for="CV" class="form-label">CV(PDF):</label>
          <input type="file" class="form-control" name="CV" id="CV" aria-describedby="helpId" placeholder="CV">
          <small id="helpId" class="form-text text-muted">Adjunte un archivo pdf</small>
        </div>
        <div class="mb-3">
          <label for="idpuesto" class="form-label">Puesto</label>
          <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
            <option selected>Elige un puesto</option>
            <?php foreach($lista_tbl_puestos as $registro) { ?>
              <option value="<?php echo $registro["ID"];?>"><?php echo $registro["Nombredelpuesto"];?></option>
            <?php } ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
          <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="helpId" placeholder="CV">
          <small id="helpId" class="form-text text-muted">Seleccione la fecha de ingreso del empleado</small>
        </div>
        <button type="submit" class="btn btn-outline-success">Agregar Registro</button>
        <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
      </form>
    </div>
    <div class="card-footer text-muted"></div>
  </div>

<?php include("../../templates/footer.php") ?>