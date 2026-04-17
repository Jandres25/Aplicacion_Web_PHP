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
                <label label for="usuario" class="form-label">Nombre del usuario:</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ejemplo: Juan10" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ejemplo: password" required>
                    <small id="helpId" class="form-text text-muted">Ingrese su contraseña</small>
                </div>
                <label for="correo" class="form-label">Correo:</label>
                <div class="input-group mb-3">
                    <input type="email" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ejemplo: correo@dominio.com" required>
                    <span class="input-group-text" id="basic-addon2">@domino.com</span>
                </div>
                <button type="submit" class="btn btn-outline-success">Agregar</button>
                <a name="" id="" class="btn btn-outline-primary" href="usuarios" role="button">Cancelar</a>
            </form>
        </div>
        <div class="card-footer text-muted"></div>
    </div>
</section>
