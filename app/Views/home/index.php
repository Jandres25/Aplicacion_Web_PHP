<section class="mt-4 mb-4">
    <div class="card">
        <div class="card-header">
            Inicio
        </div>
        <div class="card-body">
            <h2 class="card-title">Bienvenido<?= isset($nombreUsuario) && $nombreUsuario !== '' ? ', ' . htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
            <p class="card-text">Seleccione una sección para continuar.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" href="empleados">Empleados</a>
                <a class="btn btn-outline-primary" href="puestos">Puestos</a>
                <?php if (!empty($esAdministrador)) : ?>
                    <a class="btn btn-outline-primary" href="usuarios">Usuarios</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
