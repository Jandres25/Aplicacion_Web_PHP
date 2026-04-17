<section class="mt-5">
    <div class="card">
        <div class="card-header">
            Datos del usuario
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
                    <input type="text" value="<?= htmlspecialchars((string)$txtID, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId">
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Nombre del usuario:</label>
                    <input type="text" value="<?= htmlspecialchars((string)$usuario, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ejemplo: Juan10" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" value="<?= htmlspecialchars((string)$password, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ejemplo: password" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo:</label>
                    <input type="email" value="<?= htmlspecialchars((string)$correo, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ejemplo: correo@dominio.com" required>
                </div>
                <button type="submit" class="btn btn-outline-success">Actualizar</button>
                <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
