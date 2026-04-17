<section class="card mt-4 mb-4">
  <div class="card-header">
    Empleados
  </div>
  <div class="card-body">
    <?php if (isset($mensaje) && $mensaje !== null && $mensaje !== '') { ?>
      <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php } ?>
    <form action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="primernombre" class="form-label">Primer Nombre</label>
        <input type="text" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId" placeholder="Ejemplo: Juan" required>
        <small id="helpId" class="form-text text-muted">Ingrese el primer nombre del empleado</small>
      </div>
      <div class="mb-3">
        <label for="segundonombre" class="form-label">Segundo Nombre</label>
        <input type="text" class="form-control" name="segundonombre" id="segundonombre" aria-describedby="helpId" placeholder="Ejemplo: Juanito">
        <small id="helpId" class="form-text text-muted">Ingrese el segundo nombre del empleado</small>
      </div>
      <div class="mb-3">
        <label for="primerapellido" class="form-label">Primer Apellido</label>
        <input type="text" class="form-control" name="primerapellido" id="primerapellido" aria-describedby="helpId" placeholder="Ejemplo: Gonzales" required>
        <small id="helpId" class="form-text text-muted">Ingrese el primer apellido del empleado</small>
      </div>
      <div class="mb-3">
        <label for="segundoapellido" class="form-label">Segundo Apellido</label>
        <input type="text" class="form-control" name="segundoapellido" id="segundoapellido" aria-describedby="helpId" placeholder="Ejemplo: Gutierrez" required>
        <small id="helpId" class="form-text text-muted">Ingrese el segundo apellido del empleado</small>
      </div>
      <div class="mb-3">
        <label for="foto" class="form-label">Foto:</label>
        <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
        <small id="helpId" class="form-text text-muted">Ingrese el nombre del archivo tipo imagen</small>
      </div>
      <div class="mb-3">
        <label for="CV" class="form-label">CV(PDF):</label>
        <input type="file" class="form-control" name="CV" id="CV" aria-describedby="helpId" placeholder="CV">
        <small id="helpId" class="form-text text-muted">Adjunte un archivo pdf</small>
      </div>
      <div class="mb-3">
        <label for="idpuesto" class="form-label">Puesto</label>
        <select class="form-select form-select-sm" name="idpuesto" id="idpuesto" required>
          <option value="" selected>Elige un puesto</option>
          <?php foreach ($lista_tbl_puestos as $registro) { ?>
            <option value="<?= $registro["ID"]; ?>"><?= $registro["Nombredelpuesto"]; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
        <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="helpId" placeholder="CV" required>
        <small id="helpId" class="form-text text-muted">Seleccione la fecha de ingreso del empleado</small>
      </div>
      <button type="submit" class="btn btn-outline-success">Agregar Registro</button>
      <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
    </form>
  </div>
  <div class="card-footer text-muted"></div>
</section>