<section class="mt-5">
    <div class="card">
        <div class="card-header">
            Creación del puesto
        </div>
        <div class="card-body">
            <?php if (isset($mensaje) && $mensaje !== null && $mensaje !== '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>
            <form action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombredelpuesto" class="form-label">Puesto</label>
                    <input type="text" class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Ejemplo: Programador Jr." required>
                    <small id="helpId" class="form-text text-muted">Ingrese el nombre del puesto</small>
                </div>
                <button type="submit" class="btn btn-outline-success">Agregar</button>
                <a name="" id="" class="btn btn-outline-primary" href="puestos" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
