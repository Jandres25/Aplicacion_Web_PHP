<section class="mt-5">
    <div class="card">
        <div class="card-header">
            Datos del puesto
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
                    <input type="text" value="<?= htmlspecialchars((string)$txtID, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
                </div>
                <div class="mb-3">
                    <label for="nombredelpuesto" class="form-label">Puesto:</label>
                    <input type="text" value="<?= htmlspecialchars((string)$nombredelpuesto, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Puesto" required>
                </div>
                <button type="submit" class="btn btn-outline-success">Actualizar</button>
                <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
