<section class="card mt-4 mb-4">
    <div class="card-header">
        Datos del empleado
    </div>
    <div class="card-body">
        <?php if (isset($mensaje) && $mensaje !== null && $mensaje !== '') { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>
        <form action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?= $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer Nombre</label>
                <input type="text" value="<?= $primernombre; ?>" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId" placeholder="Ejemplo: Juan">
            </div>
            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo Nombre</label>
                <input type="text" value="<?= $segundonombre; ?>" class="form-control" name="segundonombre" id="segundonombre" aria-describedby="helpId" placeholder="Ejemplo: Juanito">
            </div>
            <div class="mb-3">
                <label for="primerapellido" class="form-label">Primer Apellido</label>
                <input type="text" value="<?= $primerapellido; ?>" class="form-control" name="primerapellido" id="primerapellido" aria-describedby="helpId" placeholder="Ejemplo: Gonzales">
            </div>
            <div class="mb-3">
                <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                <input type="text" value="<?= $segundoapellido; ?>" class="form-control" name="segundoapellido" id="segundoapellido" aria-describedby="helpId" placeholder="Ejemplo: Gutierrez">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <br>
                <img width="70" src="<?= $foto; ?>" class="rounded" alt="">
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
            </div>
            <div class="mb-3">
                <label for="CV" class="form-label">CV(PDF):</label>
                <br>
                <a href="<?= $cv; ?>"><?= $cv; ?></a>
                <input type="file" class="form-control" name="CV" id="CV" aria-describedby="helpId" placeholder="CV">
            </div>
            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto:</label>
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach ($lista_tbl_puestos as $puesto) { ?>
                        <option <?= ($idpuesto == $puesto["ID"]) ? "selected" : ""; ?> value="<?= $puesto["ID"]; ?>"><?= $puesto["Nombredelpuesto"]; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
                <input type="date" value="<?= $fechadeingreso; ?>" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="helpId" placeholder="CV">
            </div>
            <button type="submit" class="btn btn-outline-success">Actualizar</button>
            <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"></div>
</section>
